<?php
namespace App\Shell\Task;

use Cake\Console\Shell;
use Cake\Event\Event;
use Cake\Network\Http\Client;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;
use Cake\Utility\Text;
use Cake\Datasource\ConnectionManager;
use Google\Cloud\BigQuery\BigQueryClient;
use Cake\Core\App;
use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Robotusers\Excel\Registry;
use Robotusers\Excel\Excel\Manager;
use App\Controller\ProductsController;
use App\Controller\RobotReportsController;
use App\Controller\EmailsController;
use Cake\Filesystem\Folder;
use Cake\Filesystem\File;

class SodimacTask extends Shell
{
    private $connection;
    private $sftp;

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('ProductsStores');
        $this->loadModel('ProductsStoresAisles');
        $this->loadModel('Stores');
        $this->loadModel('Products');
        $this->loadModel('Sections');
        $this->loadModel('Categories');
        $this->loadModel('SubCategories');
        $this->loadModel('ThirdCategories');
        $this->loadModel('CatalogUpdates');
        $this->loadModel('PriceUpdates');
        $this->loadModel('Companies');
        $this->loadModel('MeasurementUnits');
        $this->loadModel('Aisles');
    }

    public function main()
    {
    	$this->out('llego a sodimac');
    }

    public function doMasterProcess($company_keyword = null){

        $send_email = false;
        //Validar objeto tienda
        if($company_keyword == null){
            $this->out(__('Store object not found'));
            return false;
        }
    	
        $company = $this->Companies->find('all')
            ->contain([
                'Stores' => function (\Cake\ORM\Query $query){
                    return $query->where(['Stores.active' => 1]);
                }
            ])
            ->where([
                'Companies.company_keyword' => $company_keyword
            ])
            ->first();

        if(count($company->stores) == 0){
            $this->out(__('Stores not found'));
            return false;
        }
        else{

            foreach($company->stores as $store){

                /*$file_name = '20180517_HC67_RNET.xls';
                //Excel con maestra de cencosud
                $data_file = ROOT . DIRECTORY_SEPARATOR . 'webroot' . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . 'active_masters'. DIRECTORY_SEPARATOR . $store_data->company->company_keyword . DIRECTORY_SEPARATOR . $file_name;

                if(!file_exists($data_file)) {
                    $this->out(__('File not exist'));
                    return false;
                }*/




                $dir = new Folder(ROOT . DIRECTORY_SEPARATOR . 'webroot' . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . 'active_masters'. DIRECTORY_SEPARATOR . $company->company_keyword);

                //Obtener csv del FTP
                //$this->connectionFtp($dir);
                //die();

                $files = $dir->find('.*\.csv', true);

                if(count($files) > 0){

                    print_r($files);

                    for($z=0; $z < count($files); $z++){
                        //Excel con maestra de cencosud
                        $data_file = ROOT . DIRECTORY_SEPARATOR . 'webroot' . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . 'active_masters'. DIRECTORY_SEPARATOR . $company->company_keyword . DIRECTORY_SEPARATOR . $files[$z];


                        //Si el archivo existe
                        if (file_exists($data_file)) {

                            $this->out(__('Processing file: {0}', $files[$z]));

                            $filename = explode('.', $data_file);
                            debug($filename);

                            if($filename[1] != 'csv'){
                                $this->out(__('El archivo no es de formato CSV'));
                                return false;
                            }

                            
                            /* Lectura del excel */
                            //$file = new File($data_file);
                            //$registry = Registry::instance();
                            //$spreadsheet = $registry->getManager()->getSpreadsheet($file); // \PhpOffice\PhpSpreadsheet\Spreadsheet instance
                            //$worksheet = $spreadsheet->getActiveSheet();

                            //$excel_data = $worksheet->toArray();
                            /* Fin Lectura del excel */

                            //if(count($excel_data) > 0){

                                //Mensaje de inicio del proceso
                                $this->out(__('[Status: Startup at {0}] {1} - [{2}] {3} Cataloged Master for {4}', [date('d-m-Y H:i:s'), $company->company_name, $store->store_code, $store->store_name, date('d-m-Y')]));

                                //Se obtiene fecha de la maestra del nombre del archivo
                                $split_file_array = explode('.', $files[$z]);
                                $file_info = explode('Mix', $split_file_array[0]);
                                $catalog_date = new Time(substr($file_info[1], 0, 4).'-'.substr($file_info[1], 4, 2).'-'.substr($file_info[1], 6, 2));

                                $catalog_date_format = $catalog_date->format('Y-m-d');

                                //print_r($file_info);

                                if($send_email == true){
                                    //$catalog_date = new Time(date('Y-m-d'));
                                    $data = [
                                        'store' => [
                                            'store_name' => $store->store_name,
                                            'store_code' => $store->store_code,
                                        ],
                                        'company' => [
                                            'company_name' => $company->company_name,
                                            'company_logo' => $company->company_logo
                                        ],
                                        'master_date' => $catalog_date,
                                        //'products_quantity' => count($excel_data)
                                    ];

                                    $email = new EmailsController;
                                    $email->sendInitMasterProcessEmail($data);
                                }

                                /*if($upload_cloud == true){

                                    $products_stores_start = '';
                                    $products_start = '';
                                    $sections_start = '';
                                    $categories_start = '';
                                    $sub_categories_start = '';
                                    $third_categories_start = '';
                                }*/

                                $handle = fopen($data_file, "r");

                                $x = 0;
                                while (($row = fgetcsv($handle, 1000, ";")) !== FALSE){

                                    /*if($x == 0){
                                        continue;
                                    }

                                    if(intval($row [$x][6]) == 0){
                                        continue;
                                    }

                                    if($row [$x][5] == 'X' || $row [$x][5] == ''){
                                        continue;
                                    }*/

                                    //print_r($row);

                                    /*if($x == 100){
                                        break;
                                    }
                                    else{
                                        continue;
                                    }*/
                                    

                                    $store_id = $store->id;

                                    $product_store = $this->ProductsStores->find('all')
                                        ->contain([
                                            'CatalogUpdates' => function (\Cake\ORM\Query $query) use ($catalog_date_format, $store_id){
                                                return $query
                                                    ->select(['CatalogUpdates.id', 'CatalogUpdates.product_store_id', 'CatalogUpdates.store_id'])
                                                    ->where(['DATE(CatalogUpdates.catalog_date)' => $catalog_date_format, 'CatalogUpdates.store_id' => $store_id])
                                                    ->order(['CatalogUpdates.catalog_date' => 'DESC'])
                                                    ->limit(1);
                                            }
                                        ])
                                        ->contain([
                                            'StockUpdates' => function (\Cake\ORM\Query $query){
                                                return $query
                                                    ->select(['StockUpdates.id', 'StockUpdates.product_store_id', 'StockUpdates.current_stock'])
                                                    ->order(['StockUpdates.stock_updated' => 'DESC'])
                                                    ->limit(1);
                                            }
                                        ])
                                        ->contain('PriceUpdates', function ($q) use ($store_id){
                                            return $q
                                                ->select(['PriceUpdates.id', 'PriceUpdates.product_store_id', 'PriceUpdates.store_id', 'PriceUpdates.price', 'PriceUpdates.company_updated'])
                                                ->where([
                                                    'PriceUpdates.store_id' => $store_id
                                                ])
                                                ->order([
                                                    'PriceUpdates.company_updated' => 'DESC'
                                                ])
                                                ->limit(1);
                                        })
                                        ->select([
                                            'ProductsStores.id',
                                            'ProductsStores.section_id',
                                            'ProductsStores.category_id',
                                            'ProductsStores.sub_category_id',
                                            'ProductsStores.company_id',
                                            'ProductsStores.internal_code',
                                            'ProductsStores.ean13',
                                            'ProductsStores.description'
                                        ])
                                        ->where([
                                            'ProductsStores.company_id' => $company->id, 
                                            'ProductsStores.internal_code' => $row[0], 
                                            //'ProductsStores.ean13' => $excel_data[$x][4]
                                        ])
                                        ->first();

                                    //$section_code = intval(substr($excel_data[$x][9], 0, 2));
                                    //$category_code = intval(substr($excel_data[$x][9], 2, 2));
                                    //$subcategory_code = intval(substr($excel_data[$x][9], 4, 2));



                                    $section = $this->getSectionData($company->id, $row[9], $row[9]);

                                    //print_r($section);

                                    //Se obtiene categoria
                                    $category = $this->getCategoryData($company->id, $row[10], $row[10], $section->id);

                                    //print_r($category);

                                    //Se obtiene sub categoria
                                    $sub_category = $this->getSubCategoryData($company->id, $row[11], $row[11], $category->id);

                                    //print_r($sub_category);

                                    //Se obtiene tercera categoria
                                    $third_category = $this->getThirdCategoryData($company->id, $row[12], $row[12], $sub_category->id);

                                    //print_r($third_category);

                                    // Si no existe producto, se agrega a la base de datos
                                    if(count($product_store) == 0){
                                        //if(strlen($excel_data[$x][4]) <= 13 && utf8_encode(ucwords(strtolower($excel_data[$x][3]))) != '' && $excel_data[$x][2] != ''){
                                            


                                        $product_store = $this->ProductsStores->newEntity();
                                        $product_store->company_id = $company->id;
                                        $product_store->section_id = ($section != null) ? $section->id : null;
                                        $product_store->category_id = ($category != null) ? $category->id : null;
                                        $product_store->sub_category_id = ($sub_category != null) ? $sub_category->id : null;
                                        $product_store->third_category_id = ($third_category != null) ? $third_category->id : null;
                                        $product_store->description = utf8_encode(ucwords(strtolower($row[1])));
                                        $product_store->internal_code = $row[0];
                                        //Ignorar 14 digitos
                                        $product_store->ean13 = null;

                                        if(!$this->ProductsStores->save($product_store)){

                                            $this->out(__('<error>Error while saving the product [EAN: {0} INT.CODE: {1}]</error>', [$product_store->ean13, $product_store->internal_code]));
                                        }
                                        else{
                                            $this->out(__('<success>Se agrego producto inicial [EAN: {0} INT.CODE: {1} DESCRIPTION: {2}]</success>', [$product_store->ean13, $product_store->internal_code, $product_store->description]));
                                        }
                                        /*}
                                        else{
                                            $this->out(__('<comment>Producto con EAN de 14 dígitos [EAN: {0} INT.CODE: {1} DESCRIPTION: {2}] - Ignorando</comment>', [$excel_data[$x][4], $excel_data[$x][2], utf8_encode(ucwords(strtolower($excel_data[$x][3])))]));
                                            $stores_list[$excel_data[$x][1]]['ignore_count'] = $stores_list[$excel_data[$x][1]]['ignore_count'] + 1;
                                            continue;
                                        }*/
                                    }
                                    else{
                                        $this->out(__('<info>Ya existe el producto [EAN: {0} INT.CODE: {1} DESCRIPTION: {2}]</info>', [$product_store->ean13, $product_store->internal_code, $product_store->description]));


                                        if($product_store->section_id == null && $section != null){
                                            $product_store->section_id = $section->id;
                                            $this->ProductsStores->save($product_store);

                                            $this->out(__('<info>Se actualizo categoria 1 [INT.CODE: {0} EAN: {1} DESCRIPTION: {2}]</info>', [$product_store->internal_code, $product_store->ean13, $product_store->description]));
                                        }

                                        if($product_store->category_id == null && $category != null){
                                            $product_store->category_id = $category->id;
                                            $this->ProductsStores->save($product_store);

                                            $this->out(__('<info>Se actualizo categoria 2 [INT.CODE: {0} EAN: {1} DESCRIPTION: {2}]</info>', [$product_store->internal_code, $product_store->ean13, $product_store->description]));
                                        }

                                        if($product_store->sub_category_id == null && $sub_category != null){
                                            $product_store->sub_category_id = $sub_category->id;
                                            $this->ProductsStores->save($product_store);

                                            $this->out(__('<info>Se actualizo categoria 3 [INT.CODE: {0} EAN: {1} DESCRIPTION: {2}]</info>', [$product_store->internal_code, $product_store->ean13, $product_store->description]));
                                        }

                                        if($product_store->third_category_id == null && $third_category != null){
                                            $product_store->sub_category_id = $sub_category->id;
                                            $this->ProductsStores->save($product_store);

                                            $this->out(__('<info>Se actualizo categoria 3 [INT.CODE: {0} EAN: {1} DESCRIPTION: {2}]</info>', [$product_store->internal_code, $product_store->ean13, $product_store->description]));
                                        }
                                    }

                                    //Validar que ya tenga catalogado si no agregarlo
                                    if(isset($product_store->catalog_updates) && count($product_store->catalog_updates) > 0){
                                        $this->out(__('<info>Ya existe registro de catalogado para el {0} [EAN: {1} INT.CODE: {2} DESC: {3}]</info>', [$catalog_date->format('d-m-Y'), $product_store->ean13, $product_store->internal_code, $product_store->description]));
                                    }
                                    else{

                                        //Si no tiene precio, se agrega el precio de la API
                                        $catalog_update = $this->CatalogUpdates->newEntity();
                                        $catalog_update->product_store_id = $product_store->id;
                                        $catalog_update->store_id = $store_id;
                                        $catalog_update->enabled = 1;
                                        $catalog_update->cataloged = 1;
                                        $catalog_update->catalog_date = $catalog_date;


                                        if(!$this->CatalogUpdates->save($catalog_update)){
                                
                                            $this->out(__('<error>Error while saving the new cataloged [EAN: {0} INT.CODE: {1}]</error>', [$product_store->ean13, $product_store->internal_code]));
                                        }
                                        else{
                                            $this->out(__('<success>new cataloged[DESC: {0} INT.CODE: {1} Store: {2}]</success>', [$product_store->description, $product_store->internal_code, $store->store_name]));
                                            //$stores_list[$excel_data[$x][1]]['catalog_count'] = $stores_list[$excel_data[$x][1]]['catalog_count'] + 1;
                                        }
                                    }

                                    if(isset($product_store->price_updates) && count($product_store->price_updates) > 0){
                                        foreach ($product_store->price_updates as $exist_price_update) {
                                            
                                            if(intval($exist_price_update->price) != $row[21]){

                                                //Si no tiene precio, se agrega el precio de la API
                                                $price_update = $this->PriceUpdates->newEntity();
                                                $price_update->product_store_id = $product_store->id;
                                                $price_update->store_id = $store->id;
                                                $price_update->price = $row[21];
                                                $price_update->previous_price = $exist_price_update->price;

                                                $price_update->company_updated = new Time($row[20]);


                                                if(!$this->PriceUpdates->save($price_update)){
                                        
                                                    $this->out(__('<error>Error while saving the new update [INT.CODE: {0} EAN: {1} PRICE: {2}]</error>', [$row[1], $row[3].$row[4], $row[11]]));
                                                }
                                                else{
                                                    $this->out(__('<success>Cambio de precio [DESC: {0} INT.CODE: {1} EAN: {2} NEW PRICE: {3} LAST PRICE: {4}]</success>', [utf8_encode(ucwords(strtolower($row[1]))),$row[0], null, $row[21], $exist_price_update->price]));
                                                }
                                            }
                                            else{
                                                $this->out(__('<info>Se mantuvo el precio [INT.CODE: {0} EAN: {1} PRICE: {2}]</info>', [$row[0], null, $row[21]]));
                                            }
                                        }
                                    }
                                    else{
                                        //Si no tiene precio, se agrega el precio de la API
                                        $price_update = $this->PriceUpdates->newEntity();
                                        $price_update->product_store_id = $product_store->id;
                                        $price_update->store_id = $store->id;
                                        $price_update->price = $row[21];
                                        $price_update->previous_price = $row[21];


                                        $price_update->company_updated = new Time($row[20]);

                                        if(!$this->PriceUpdates->save($price_update)){

                                            $this->out(__('<error>Error while saving the initial update [EAN: {0} INT.CODE: {1} PRICE: {2}]</error>', [$product_store->description, $row[1], $row[3].$row[4], $row[11]]));
                                        }
                                        else{
                                            $this->out(__('<success>Se agrego el precio inicial [DESC: {0} EAN: {1} INT.CODE: {2} PRICE: {3}]</success>', [$product_store->description, null, $row[0], $row[21]]));
                                        }
                                    }

                                    //Validar que tenga stock si no agregarlo si corresponde
                                    /*if(isset($product_store->stock_updates) && count($product_store->stock_updates) > 0){

                                        foreach($product_store->stock_updates as $update){
                                            if( ($catalog_date > $update->stock_updated) && ($excel_data[$x][19] != '') && ($excel_data[$x][19] != $update->current_stock)){

                                                //Si no tiene precio, se agrega el precio de la API
                                                $stock_update = $this->StockUpdates->newEntity();
                                                $stock_update->product_store_id = $product_store->id;
                                                $stock_update->store_id = $store_id;
                                                $stock_update->current_stock = $excel_data[$x][19];
                                                $stock_update->last_stock = $update->current_stock;
                                                $stock_update->stock_updated = $catalog_date;

                                                

                                                if(!$this->StockUpdates->save($stock_update)){
                                        
                                                    $this->out(__('<error>Error while saving the new stock [EAN: {0} INT.CODE: {1}]</error>', [$product_store->ean13, $product_store->internal_code]));
                                                }
                                                else{
                                                    $this->out(__('<question>Stock Update [EAN: {0} INT.CODE: {1} Store: {2} LAST STOCK: {3} NEW STOCK: {4}]</question>', [$product_store->ean13, $product_store->internal_code, $excel_data[$x][1], $update->current_stock, $stock_update->current_stock]));

                                                    $stores_list[$excel_data[$x][1]]['stock_count'] = $stores_list[$excel_data[$x][1]]['stock_count'] + 1;
                                                }
                                            }
                                            else{

                                                $this->out(__('<info>Sin cambio de stock para el {0} [EAN: {1} INT.CODE: {2} DESC: {3} Store: {4}]</info>', [$catalog_date->format('d-m-Y'), $product_store->ean13, $product_store->internal_code, $product_store->description, $excel_data[$x][1]]));
                                            }
                                        }
                                    }
                                    else{
                                        if($excel_data[$x][19] != ''){

                                            //Si no tiene precio, se agrega el precio de la API
                                            $stock_update = $this->StockUpdates->newEntity();
                                            $stock_update->product_store_id = $product_store->id;
                                            $stock_update->store_id = $store_id;
                                            $stock_update->current_stock = $excel_data[$x][19];
                                            $stock_update->last_stock = $excel_data[$x][19];
                                            $stock_update->stock_updated = $catalog_date;

                                            

                                            if(!$this->StockUpdates->save($stock_update)){
                                    
                                                $this->out(__('<error>Error while saving the new stock [EAN: {0} INT.CODE: {1}]</error>', [$product_store->ean13, $product_store->internal_code]));
                                            }
                                            else{
                                                $this->out(__('<question>First Stock[EAN: {0} INT.CODE: {1} Store: {2}]</question>', [$product_store->ean13, $product_store->internal_code, $excel_data[$x][1]]));
                                                $stores_list[$excel_data[$x][1]]['stock_count'] = $stores_list[$excel_data[$x][1]]['stock_count'] + 1;
                                            }
                                        }
                                    }*/
                                }

                                if($send_email == true){
                                    $email->sendFinishMasterProcessEmail($data, $count);
                                }

                                $this->out(__('[Status: Startup at {0}] {1} - [{2}] {3} Cataloged Master for {4}', [date('d-m-Y H:i:s'), $company->company_name, $store->store_code, $store->store_name, date('d-m-Y')]));

                            //}
                            /*else{
                                $this->out(__('Excel without rows'));
                                return false;
                            }*/
                        }
                    }
                }

            }
        }
    }

    /**
    **
    Busca la seccion por su codigo, de no encontrarla crea una nueva y devuelve el objeto
    **
    **/
    function getSectionData($company_id = null, $section_name = null, $section_code = null, $upload_cloud = false){

        $section = $this->ProductsStores->Sections->find('all', ['conditions' => ['Sections.section_code' => $section_code, 'Sections.company_id' => $company_id]])->select(['Sections.id', 'Sections.section_code'])->first();

        if(count($section) == 0){

            $section = $this->Sections->newEntity();
            $section->company_id = $company_id;
            $section->section_name = ucwords(strtolower($section_name));
            $section->section_code = $section_code;

            if(!$this->Sections->save($section)){

                $this->out(__('Error while trying saved the section'));
                return false;
            }
        }

        return $section;
    }

    /**
    **
    Busca la categoria por su codigo, de no encontrarla crea una nueva y devuelve el objeto
    **
    **/
    function getCategoryData($company_id = null, $category_code = null, $category_name = null, $section_id = null){

        $category = $this->ProductsStores->Categories->find('all', ['conditions' => ['Categories.category_code' => $category_code, 'Categories.company_id' => $company_id]])->select(['Categories.id', 'Categories.category_code'])->first();

        if(count($category) == 0 && $category_name != null && $section_id != null){

            $category = $this->Categories->newEntity();
            $category->company_id = $company_id;
            $category->section_id = $section_id;
            $category->category_name = ucwords(strtolower($category_name));
            $category->category_code = $category_code;

            if(!$this->Categories->save($category)){

                $this->out(__('Error while trying saved the category'));
                return false;
            }
        }

        return $category;
    }

    /**
    **
    Busca la sub categoria por su codigo, de no encontrarla crea una nueva y devuelve el objeto
    **
    **/
    function getSubCategoryData($company_id = null, $sub_category_code = null, $sub_category_name = null, $category_id = null){

        $sub_category = $this->ProductsStores->SubCategories->find('all', ['conditions' => ['SubCategories.sub_category_code' => $sub_category_code, 'SubCategories.company_id' => $company_id]])->select(['SubCategories.id', 'SubCategories.sub_category_code'])->first();

        if(count($sub_category) == 0 && $sub_category_name != null && $category_id != null){

            $sub_category = $this->SubCategories->newEntity();
            $sub_category->company_id = $company_id;
            $sub_category->category_id = $category_id;
            $sub_category->sub_category_name = ucwords(strtolower($sub_category_name));
            $sub_category->sub_category_code = ucwords(strtolower($sub_category_code));

            if(!$this->SubCategories->save($sub_category)){

                $this->out(__('Error while trying saved the sub category'));
                return false;
            }
        }

        return $sub_category;
    }

    function getThirdCategoryData($company_id = null, $third_category_code = null, $third_category_name = null, $sub_category_id = null){

        $third_category = $this->ProductsStores->ThirdCategories->find('all', ['conditions' => ['ThirdCategories.third_category_code' => $third_category_code, 'ThirdCategories.company_id' => $company_id]])->select(['ThirdCategories.id', 'ThirdCategories.third_category_code'])->first();

        if(count($third_category) == 0 && $third_category_name != null && $sub_category_id != null){

            $third_category = $this->ThirdCategories->newEntity();
            $third_category->company_id = $company_id;
            $third_category->sub_category_id = $sub_category_id;
            $third_category->third_category_name = ucwords(strtolower($third_category_name));
            $third_category->third_category_code = $third_category_code;

            if(!$this->ThirdCategories->save($third_category)){

                $this->out(__('Error while trying saved the sub category'));
                return false;
            }
        }

        return $third_category;
    }

    function getAisleData($company_id, $store_id, $aisle_number, $upload_cloud){

        $aisle = $this->Aisles->find('all', ['conditions' => ['Aisles.aisle_number' => $aisle_number, 'Aisles.company_id' => $company_id, 'Aisles.store_id' => $store_id]])->select(['Aisles.id', 'Aisles.aisle_number'])->first();

        if(count($aisle) == 0){

            $aisle = $this->Aisles->newEntity();
            $aisle->company_id = $company_id;
            $aisle->store_id = $store_id;
            $aisle->aisle_number = $aisle_number;
            $aisle->enabled = 1;

            if(!$this->Aisles->save($aisle)){

                $this->out(__('Error while trying saved the aisle'));
                return false;
            }
        }

        return $aisle;
    }

    /**
    **
    Genera un nuevo producto y la asociada a la maestra determinada
    **
    **/
    function generateNewProduct($product_name = null, $internal_code = null, $ean_code = null, $company_id = null, $store_id = null, $aisle_id = null, $section_id = null, $category_id = null, $sub_category_id = null, $catalog_date = null, $price = null, $cataloged = null, $enabled = null, $stock_up_to_date = null){

        //Object producto
        $product = $this->Products->newEntity();

        $product->product_name = utf8_encode(ucwords(strtolower($product_name)));
        $product->product_description = utf8_encode(ucwords(strtolower($product_name)));

        if($ean_code != null){
            $ean_original_length = strlen($ean_code);
            $ean_new_length = $ean_original_length -1;
            $product->ean128 = substr($ean_code, 0, $ean_new_length);
            $product->ean128_digit = substr($ean_code, -1);
        }
        

        if ($this->Products->save($product)) {

            $this->out(__('<question>Product {0}: Saved successful on {1}</question>', [$product->product_name, date('d-m-Y H:i:s')]));

            //return $product;
            $master_date = new Time($catalog_date);

            $new_product_store = $this->ProductsStores->newEntity();
            $new_product_store->product_id = $product->id;
            $new_product_store->company_id = $company_id;
            $new_product_store->store_id = $store_id;
            //$new_product_store->aisle_id = $aisle_id;
            $new_product_store->section_id = $section_id;
            $new_product_store->category_id = $category_id;
            $new_product_store->sub_category_id = $sub_category_id;
            $new_product_store->master_catalog_date = $master_date;
            $new_product_store->strip_price = $price;
            $new_product_store->company_internal_code = $internal_code;
            $new_product_store->cataloged = ($cataloged != '') ? intval($cataloged) : null;
            $new_product_store->enabled = ($enabled != '') ? intval($enabled) : null;
            $new_product_store->stock_up_to_date = ($enabled != '') ? intval($enabled) : null;

            if(!$this->ProductsStores->save($new_product_store)){

                $this->out(__('<error>Error while saving the product {0} [SAP: {1}] and relationship</error>', [$product_name, $internal_code]));
                return false;
            }
            else{
                $this->out(__('<question>NEW Product {0}: Saved on cataloged products on {1}</question>', [$product->product_name, $master_date->format('d-m-Y')]));

                //Se guarda el pasillo que viene asociado al producto en la maestra
                if($aisle_id != null){
                    $product_store_aisle = $this->ProductsStoresAisles->newEntity();
                    $product_store_aisle->product_store_id = $new_product_store->id;
                    $product_store_aisle->aisle_id = $aisle_id;

                    $this->ProductsStoresAisles->save($product_store_aisle);
                }

                $new_product_store->product = $product; 

                return $new_product_store;
            }
        }
        else{
            $this->out(__('<error>Error while saving the product {0}</error>', [$product_name]));
            return false;
        }
    }

    function connectionFtp($local_dir = null){
        $ftp_server = "updates.zippedi.cl";
        $conn_id = ftp_connect($ftp_server);

        if($conn_id){
            // login with username and password
            $ftp_user_name = "hc_data";
            $ftp_user_pass = "2HDhJGanwfaMbkjR";
            $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
            ftp_pasv($conn_id, true);
            // check connection
            if ((!$conn_id) || (!$login_result)) {
                    echo "FTP connection has failed!";
                    echo "Attempted to connect to $ftp_server for user $ftp_user_name";
                    exit;
                } else {
                    echo "Connected to $ftp_server, for user $ftp_user_name";
                }

            $buff = ftp_rawlist($conn_id, '.');
             var_dump($buff);
            ftp_close($conn_id); 
        }
        else{
            echo 'no hay identificador para la conexion FTP';
        }
    }


    function connectFtp($local_dir = null){
        $host = 'updates.zippedi.cl';
        $port = 22;
        $username = 'hc_data';
        $password = '2HDhJGanwfaMbkjR';
        $remoteDir = 'HC67/prices/';
        $localDir = $local_dir;

        if (!function_exists("ssh2_connect"))
            die('Function ssh2_connect not found, you cannot use ssh2 here');

        if (!$connection = ssh2_connect($host, $port))
            die('Unable to connect');

        if (!ssh2_auth_password($connection, $username, $password))
            die('Unable to authenticate.');

        if (!$stream = ssh2_sftp($connection))
            die('Unable to create a stream.');

        if (!$dir = opendir("ssh2.sftp://{$stream}{$remoteDir}"))
            die('Could not open the directory');

        $files = array();
        while (false !== ($file = readdir($dir)))
        {
            if ($file == "." || $file == "..")
                continue;
            $files[] = $file;
        }

        foreach ($files as $file)
        {
            echo "Copying file: $file\n";
            if (!$remote = @fopen("ssh2.sftp://{$stream}/{$remoteDir}{$file}", 'r'))
            {
                echo "Unable to open remote file: $file\n";
                continue;
            }

            if (!$local = @fopen($localDir . $file, 'w'))
            {
                echo "Unable to create local file: $file\n";
                continue;
            }

            $read = 0;
            $filesize = filesize("ssh2.sftp://{$stream}/{$remoteDir}{$file}");
            while ($read < $filesize && ($buffer = fread($remote, $filesize - $read)))
            {
                $read += strlen($buffer);
                if (fwrite($local, $buffer) === FALSE)
                {
                    echo "Unable to write to local file: $file\n";
                    break;
                }
            }
            fclose($local);
            fclose($remote);
        }
    }

    /**
    * Stream a row of data into your BigQuery table
    * Example:
    * ```
    * $data = [
    *     "field1" => "value1",
    *     "field2" => "value2",
    * ];
    * stream_row($projectId, $datasetId, $tableId, $data);
    * ```.
    *
    * @param string $projectId The Google project ID.
    * @param string $datasetId The BigQuery dataset ID.
    * @param string $tableId   The BigQuery table ID.
    * @param string $data      An associative array representing a row of data.
    * @param string $insertId  An optional unique ID to guarantee data consistency.
    */
    function stream_row($projectId, $datasetId, $tableId, $data, $insertId = null)
    {
        $keyFile = ROOT . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'Cencosud-Chile-532be363cdb8.json';

        // instantiate the bigquery table service
        $bigQuery = new BigQueryClient([
            'projectId' => $projectId,
            'keyFile' => json_decode(file_get_contents($keyFile), true)
        ]);

        $dataset = $bigQuery->dataset($datasetId);
        $table = $dataset->table($tableId);

        $insertResponse = $table->insertRows([
            ['insertId' => $insertId, 'data' => $data],
            // additional rows can go here
        ]);
        if ($insertResponse->isSuccessful()) {
            //print('Data streamed into BigQuery successfully' . PHP_EOL);
            return true;
        } 
        else{
            foreach ($insertResponse->failedRows() as $row){
                foreach ($row['errors'] as $error) {
                    printf('%s: %s' . PHP_EOL, $error['reason'], $error['message']);
                }
            }
            return false;
        }
    }

    public function doUpdateProcess(){
    	$this->out('llego a sodimac update');
    }

    public function doAnalyzedProductsProcess(){
    	$this->out('llego a sodimac zippedi');
    }

    public function printLabels(){

    
        $http = new Client();
        $products_api_response = $http->get('https://apiapp.pechera.p.azurewebsites.net:443/v1/Productos/CL/67/3046931%2C3316181%2C1849166/Imprimir/Flejes',[],['headers' => [], 'type' => 'json']);

        print_r($products_api_response);
        print_r($products_api_response->body);
        print_r($products_api_response->json);

        die();
    }
}