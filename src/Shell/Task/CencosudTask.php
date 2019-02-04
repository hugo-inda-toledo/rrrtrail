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

class CencosudTask extends Shell
{
	public function initialize()
    {
        parent::initialize();
        $this->loadModel('ProductsStores');
        $this->loadModel('Stores');
        $this->loadModel('CatalogUpdates');
        $this->loadModel('PriceUpdates');
        $this->loadModel('StockUpdates');
        $this->loadModel('DealUpdates');
        $this->loadModel('Sections');
        $this->loadModel('Categories');
        $this->loadModel('SubCategories');
        $this->loadModel('Companies');
        $this->loadModel('MeasurementUnits');
        $this->loadModel('Aisles');
        $this->loadModel('AnalyzedProducts');

        /*Log::config('master_catalog_date_process_'.$master_catalog_date->format('Ymd').'_', [
            'className' => 'File',
            'path' => ROOT. '/logs',
            'scopes' => ['master_catalog_date_process']
        ]);*/
    }

    public function main()
    {
    	$this->out('llego a cencosud main');
    }

    //Proceso para carga de maestra de productos para cencosud en base a excel enviado diariamente
    public function doMasterProcess($company_keyword = null){
        
        $robot_reports = new RobotReportsController;

        $send_email = true;
        
        //Si no existe tienda omite la fila
        if($company_keyword == null){
            
            $this->out(__('Company keyword not found'));
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
                $stores_list[$store->store_code]['store_name'] = $store->store_name;
                $stores_list[$store->store_code]['company_name'] = $company->company_name;
                $stores_list[$store->store_code]['id'] = $store->id;
                $stores_list[$store->store_code]['count'] = 0;
                $stores_list[$store->store_code]['new_products_count'] = 0;
                $stores_list[$store->store_code]['stock_count'] = 0;
                $stores_list[$store->store_code]['ignore_count'] = 0;
            }
        }

        $dir = new Folder(ROOT . DIRECTORY_SEPARATOR . 'webroot' . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . 'active_masters'. DIRECTORY_SEPARATOR . $company->company_keyword);

        $files = $dir->find('.*\.xlsx', true);

        if(count($files) > 0){

            print_r($files);
            for($z=0; $z < count($files); $z++){
                //Excel con maestra de cencosud
                $data_file = ROOT . DIRECTORY_SEPARATOR . 'webroot' . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . 'active_masters'. DIRECTORY_SEPARATOR . $company->company_keyword . DIRECTORY_SEPARATOR . $files[$z];


                //Si el archivo existe
                if (file_exists($data_file)) {

                    $this->out(__('Processing file: {0}', $files[$z]));


                    ini_set('memory_limit','2048M');

                    /* Lectura del excel */
                    $file = new File($data_file);
                    $registry = Registry::instance();
                    $spreadsheet = $registry->getManager()->getSpreadsheet($file); // \PhpOffice\PhpSpreadsheet\Spreadsheet instance
                    $worksheet = $spreadsheet->getActiveSheet();

                    $excel_data = $worksheet->toArray();
                    /* Fin Lectura del excel */

                    if(count($excel_data) > 0){
                        
                        $date_array = explode('-', $excel_data[1][0]);

                        if(count($date_array) == 1){
                            $date_array = explode('/', $excel_data[1][0]);
                        }

                        $catalog_date = New Time($date_array[2].'-'.$date_array[1].'-'.$date_array[0]);
                        $catalog_date_format = $catalog_date->format('Y-m-d');


                        if($send_email == true){
                            $data = [
                                'store' => [
                                    'store_name' => __('All'),
                                    'store_code' => __('All'),
                                ],
                                'company' => [
                                    'company_name' => $company->company_name,
                                    'company_logo' => $company->company_logo
                                ],
                                'master_date' => $catalog_date,
                                'products_quantity' => count($excel_data)
                            ];

                            $email = new EmailsController;
                            $email->sendInitMasterProcessEmail($data);
                        }

                        for($x=0; $x < count($excel_data); $x++){

                            if($x == 0){
                                continue;
                            }

                            /*echo '<pre>';
                            print_r($excel_data[$x]);
                            echo '</pre>';

                            $section_code = intval(substr($excel_data[$x][9], 0, 2));
                            $category_code = intval(substr($excel_data[$x][9], 2, 2));
                            $subcategory_code = intval(substr($excel_data[$x][9], 4, 2));



                            $this->out(__('section: {0} category: {1} sub category: {2}', [$section_code, $category_code, $subcategory_code]));
                            


                            if($x == 200){
                                die();
                            }

                            continue;*/

                            //$date_array = explode('-', $excel_data[1][0]);
                            //$date_array = explode('-', $excel_data[$x][0]);
                            //$catalog_date = New Time($date_array[2].'-'.$date_array[1].'-'-$date_array[0]);
                            //$catalog_date_format = $catalog_date->format('Y-m-d');
                            
                            $store_id = $stores_list[$excel_data[$x][1]]['id'];

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
                                    'ProductsStores.internal_code' => $excel_data[$x][2], 
                                    'ProductsStores.ean13' => $excel_data[$x][4]
                                ])
                                ->first();

                            $section_code = intval(substr($excel_data[$x][9], 0, 2));
                            $category_code = intval(substr($excel_data[$x][9], 2, 2));
                            $subcategory_code = intval(substr($excel_data[$x][9], 4, 2));



                            /*$this->out(__('section: {0} category: {1} sub category: {2}', [$section_code, $category_code, $subcategory_code]));
                            continue;*/



                            $section = $this->getSectionData($company->id, $section_code, $excel_data[$x][6]);

                            //Se obtiene categoria
                            $category = $this->getCategoryData($company->id, $category_code, $excel_data[$x][8], $section->id);

                            //Se obtiene sub categoria
                            $sub_category = $this->getSubCategoryData($company->id, $subcategory_code, $excel_data[$x][10], $category->id);

                            // Si no existe producto, se agrega a la base de datos
                            if($product_store == null){
                                if(strlen($excel_data[$x][4]) <= 13 && utf8_encode(ucwords(strtolower($excel_data[$x][3]))) != '' && $excel_data[$x][2] != ''){
                                    


                                    $product_store = $this->ProductsStores->newEntity();
                                    $product_store->company_id = $company->id;
                                    $product_store->section_id = ($section != null) ? $section->id : null;
                                    $product_store->category_id = ($category != null) ? $category->id : null;
                                    $product_store->sub_category_id = ($sub_category != null) ? $sub_category->id : null;
                                    $product_store->description = utf8_encode(ucwords(strtolower($excel_data[$x][3])));
                                    $product_store->internal_code = $excel_data[$x][2];
                                    //Ignorar 14 digitos
                                    $product_store->ean13 = $excel_data[$x][4];

                                    if(!$this->ProductsStores->save($product_store)){

                                        $this->out(__('<error>Error while saving the product [EAN: {0} INT.CODE: {1}]</error>', [$product_store->ean13, $product_store->internal_code]));
                                    }
                                    else{
                                        $this->out(__('<success>Se agrego producto inicial [EAN: {0} INT.CODE: {1} DESCRIPTION: {2}]</success>', [$product_store->ean13, $product_store->internal_code, $product_store->description]));
                                        $stores_list[$excel_data[$x][1]]['new_products_count'] = $stores_list[$excel_data[$x][1]]['new_products_count'] + 1;
                                    }
                                }
                                else{
                                    $this->out(__('<comment>Producto con EAN de 14 dígitos [EAN: {0} INT.CODE: {1} DESCRIPTION: {2}] - Ignorando</comment>', [$excel_data[$x][4], $excel_data[$x][2], utf8_encode(ucwords(strtolower($excel_data[$x][3])))]));
                                    $stores_list[$excel_data[$x][1]]['ignore_count'] = $stores_list[$excel_data[$x][1]]['ignore_count'] + 1;
                                    continue;
                                }
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
                                $catalog_update->enabled = ($excel_data[$x][18] != '') ? intval($excel_data[$x][18]) : null;
                                $catalog_update->cataloged = ($excel_data[$x][17] != '') ? intval($excel_data[$x][17]) : null;
                                $catalog_update->catalog_date = $catalog_date;


                                if(!$this->CatalogUpdates->save($catalog_update)){
                        
                                    $this->out(__('<error>Error while saving the new cataloged [EAN: {0} INT.CODE: {1}]</error>', [$product_store->ean13, $product_store->internal_code]));
                                }
                                else{
                                    $this->out(__('<success>new cataloged[EAN: {0} INT.CODE: {1} Store: {2}]</success>', [$product_store->ean13, $product_store->internal_code, $excel_data[$x][1]]));
                                    //$stores_list[$excel_data[$x][1]]['catalog_count'] = $stores_list[$excel_data[$x][1]]['catalog_count'] + 1;
                                }
                            }

                            //Validar que tenga stock si no agregarlo si corresponde
                            if(isset($product_store->stock_updates) && count($product_store->stock_updates) > 0){

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
                            }
                        }

                        print_r($stores_list);

                        if($send_email == true){
                            $email->sendFinishMasterProcessEmail($data, ($x - 1));
                        }

                        
                        $this->out(__('[Status: Finish at {0}] {1} - [{2}] {3} Cataloged Master for {4}', [date('d-m-Y H:i:s'), $company->company_name, __('All'), __('All'), $catalog_date->format('d-m-Y')]));
                    }

                    unlink($data_file);
                }
                else{
                    $this->out(__('It was not found Excel file for importing data'));
                    return false;
                }
            }
        }
        else{
            $this->out(__('No xlsx files in folder'));
            return false;
        }
    }

    function doPutPriceInEmptyProducts($store_data = null){

        //Si no existe tienda omite la fila
        if($store_data == null){
            
            $this->out(__('Store object not found'));
            return false;
        }

        $products = $this->ProductsStores->find('all')
            ->contain(['PriceUpdates'])
            ->where(['ProductsStores.company_id' => $store_data->company_id])
            ->toArray();



        if(count($products) > 0){

            $http = new Client();

            https://api.cencosud.cl/v1.0/sm/cl/articulos/precios?ean13=780200000406&idLocalSap=J501

            foreach($products as $product){

                if(count($product->price_updates) == 0){
                    
                    //$api_response = $http->get('https://api.cencosud.cl/v1.0/sm/cl/articulos/precios',['idLocalSap' => $store_data->store_code, 'CodigoSap' => $product->internal_code],['headers' => ['apiKey' => 'CdRpbGmRfLcq2XbVNwLgHtU9zuLp4w6W'], 'type' => 'json']);

                    $api_response = $http->get('https://api.cencosud.cl/v1.0/sm/cl/articulos/precios?idLocalSap=J512&CodigoSap='.$product->internal_code,[],['headers' => ['apiKey' => 'CdRpbGmRfLcq2XbVNwLgHtU9zuLp4w6W'], 'type' => 'json']);

                    
                    $this->out($product->internal_code);

                    if(count($api_response->json) > 0){
                        print_r($api_response);
                        print_r($api_response->json);
                        die();
                    }

                    /*$ean13 = $product->ean13;
                    if(strlen($ean13) != 13){
                        $difference = 13 - strlen($ean13);

                        $before = '';
                        for($x=0; $x < $difference; $x++){
                            $before .= '0';
                        }

                        $ean13 = $before.$ean13;
                    }

                    $ean_original_length = strlen($ean13);
                    $ean_new_length = $ean_original_length -1;
                    $ean13_without_digit = substr($ean13, 0, $ean_new_length);
                    
                    if(strlen($ean13_without_digit) == 12){
                        $api_response = $http->get('https://api.cencosud.cl/v1.0/sm/cl/articulos/precios',['ean13' => $ean13_without_digit, 'idLocalSap' => $store_data->store_code],['headers' => ['apiKey' => 'CdRpbGmRfLcq2XbVNwLgHtU9zuLp4w6W'], 'type' => 'json']);

                        $this->out($ean13_without_digit);

                        if(count($api_response->json) > 0){
                            print_r($api_response);
                            print_r($api_response->json);
                            die();
                        }
                    }*/
                }
            }
        }
    }

    //Actualiza precios de una tienda determinada para cencosud atraves de su API de precios
    function doUpdateProcess($store_data = null, $from_date = null, $from_time = null){

        //Si no existe tienda omite la fila
        if($store_data == null){
            
            $this->out(__('Store object not found'));
            return false;
        }

        $opts = [];
        $opts_query = [];

        $last_price_update = [];
        /*$last_price_update = $this->PriceUpdates->find('all')
            ->order([
                'PriceUpdates.company_updated' => 'DESC'
            ])
            ->first();

        if($last_price_update != null){
            $from_date = $last_price_update->company_updated->format('Y-m-d');
            $from_time = $last_price_update->company_updated->format('H:i:s');
        }*/

        if($from_date != null && $from_time != null){

            $array_hour = explode(':', $from_time);
            $date = new Time($from_date.' '.$array_hour[0].':'.$array_hour[1].':'.$array_hour[1]);
            $opts['fechaDesde'] = $from_date.'%20'.$array_hour[0].'%3A'.$array_hour[1].'%3A'.$array_hour[2];
            $this->out(__('Last update: {0} {1}', [$from_date, $from_time]));
        }

        $http = new Client();
        $products_api_response = $http->get('https://api.cencosud.cl/v1.0/sm/cl/articulos/precios',['idLocalSap' => $store_data->store_code, $opts],['headers' => ['apiKey' => 'CdRpbGmRfLcq2XbVNwLgHtU9zuLp4w6W'], 'type' => 'json']);

        if(count($products_api_response->json) > 0){
            
            $store_id = $store_data->id;

            $arr['new_products_count'] = 0;
            $arr['update_count'] = 0;
            $arr['keep_count'] = 0;
            $arr['initial_update_count'] = 0;



            //Iteración productos de la api de precio de cencosud
            foreach($products_api_response->json as $api_product){

                $product_store = $this->ProductsStores->find('all')
                    ->contain('PriceUpdates', function ($q) use ($store_id){
                        return $q
                            ->select(['PriceUpdates.id', 'PriceUpdates.product_store_id', 'PriceUpdates.store_id', 'PriceUpdates.price', 'PriceUpdates.ppums_price', 'PriceUpdates.company_updated'])
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
                        'ProductsStores.company_id',
                        'ProductsStores.section_id',
                        'ProductsStores.category_id',
                        'ProductsStores.sub_category_id',
                        'ProductsStores.internal_code',
                        'ProductsStores.description',
                        'ProductsStores.ean13'
                    ])
                    ->where([
                        'ProductsStores.company_id' => $store_data->company->id, 
                        'ProductsStores.internal_code' => $api_product['codigoMaterial'], 
                        'ProductsStores.ean13' => $api_product['ean13'].$api_product['digitoVerificador'],
                    ])
                    ->first();

                $datetime = explode('T', $api_product['timeStampActualizacion']);
                $new_company_update = new Time($datetime[0].' '.$datetime[1]);

                $api_product_section_code = intval(substr($api_product['jerarquia'], 0, 2));
                $api_product_category_code = intval(substr($api_product['jerarquia'], 2, 2));
                $api_product_sub_category_code = intval(substr($api_product['jerarquia'], 4, 2));


                if($product_store == null){

                    //Agregar product
                    $section = $this->getSectionData($store_data->company_id, $api_product_section_code, $api_product_section_code);

                    //Se obtiene categoria
                    $category = $this->getCategoryData($store_data->company_id, $api_product_category_code, $api_product_category_code, $section->id);

                    //Se obtiene sub categoria
                    $sub_category = $this->getSubCategoryData($store_data->company_id, $api_product_sub_category_code, $api_product_sub_category_code, $category->id);

                    $product_store = $this->ProductsStores->newEntity();
                    $product_store->company_id = $store_data->company_id;
                    $product_store->section_id = ($section != null) ? $section->id : null;
                    $product_store->category_id = ($category != null) ? $category->id : null;
                    $product_store->sub_category_id = ($sub_category != null) ? $sub_category->id : null;
                    $product_store->description = utf8_encode(ucwords(strtolower($api_product['descripcionLarga'])));
                    $product_store->internal_code = $api_product['codigoMaterial'];
                    $product_store->ean13 = $api_product['ean13'].$api_product['digitoVerificador'];

                    if(!$this->ProductsStores->save($product_store)){

                        $this->out(__('<error>Error while saving the product {0} [SAP: {1}] and relationship</error>', [$product_name, $internal_code]));
                    }
                    else{
                        $this->out(__('<success>Se agrego producto nuevo [INT.CODE: {0} EAN: {1} DESCRIPTION: {2}]</success>', [$api_product['codigoMaterial'], $api_product['ean13'].$api_product['digitoVerificador'], $api_product['descripcionLarga']]));        

                        $arr['new_products_count'] = $arr['new_products_count'] + 1;
                    }

                }
                else{
                    $this->out(__('<info>Producto existente [INT.CODE: {0} EAN: {1} DESCRIPTION: {2}]</info>', [$api_product['codigoMaterial'], $api_product['ean13'].$api_product['digitoVerificador'], $api_product['descripcionLarga']]));



                    if($product_store->section_id == null){

                        //Agregar seccion
                        $section = $this->getSectionData($store_data->company_id, $api_product_section_code, $api_product_section_code);

                        $product_store->section_id = $section->id;
                        $this->ProductsStores->save($product_store);

                        $this->out(__('<info>Se actualizo categoria 1 [INT.CODE: {0} EAN: {1} DESCRIPTION: {2}]</info>', [$api_product['codigoMaterial'], $api_product['ean13'].$api_product['digitoVerificador'], $api_product['descripcionLarga']]));
                    }

                    if($product_store->category_id == null){

                        //Se obtiene categoria
                        $category = $this->getCategoryData($store_data->company_id, $api_product_category_code, $api_product_category_code, $section->id);

                        $product_store->category_id = $category->id;
                        $this->ProductsStores->save($product_store);

                        $this->out(__('<info>Se actualizo categoria 2 [INT.CODE: {0} EAN: {1} DESCRIPTION: {2}]</info>', [$api_product['codigoMaterial'], $api_product['ean13'].$api_product['digitoVerificador'], $api_product['descripcionLarga']]));
                    }

                    if($product_store->sub_category_id == null){
                        
                        //Se obtiene sub categoria
                        $sub_category = $this->getSubCategoryData($store_data->company_id, $api_product_sub_category_code, $api_product_sub_category_code, $category->id);

                        $product_store->sub_category_id = $sub_category->id;
                        $this->ProductsStores->save($product_store);

                        $this->out(__('<info>Se actualizo categoria 3 [INT.CODE: {0} EAN: {1} DESCRIPTION: {2}]</info>', [$api_product['codigoMaterial'], $api_product['ean13'].$api_product['digitoVerificador'], $api_product['descripcionLarga']]));
                    }
                }

                if(isset($product_store->price_updates) && count($product_store->price_updates) > 0){
                    foreach ($product_store->price_updates as $exist_price_update) {
                        
                        if(intval($exist_price_update->price) != $api_product['precioFlejes'] || $new_company_update != $exist_price_update->company_updated){

                            //Si no tiene precio, se agrega el precio de la API
                            $price_update = $this->PriceUpdates->newEntity();
                            $price_update->product_store_id = $product_store->id;
                            $price_update->store_id = $store_data->id;
                            $price_update->price = $api_product['precioFlejes'];
                            $price_update->previous_price = $exist_price_update->price;
                            $price_update->ppums_price = $api_product['importePPUMS'];
                            $price_update->previous_ppums_price = $exist_price_update->previous_ppums_price;
                            $price_update->company_updated = $new_company_update;


                            if(!$this->PriceUpdates->save($price_update)){
                    
                                $this->out(__('<error>Error while saving the new update [INT.CODE: {0} EAN: {1} PRICE: {2}]</error>', [$api_product['codigoMaterial'], $api_product['ean13'].$api_product['digitoVerificador'], $api_product['precioFlejes']]));
                            }
                            else{
                                $this->out(__('<success>Cambio de precio [DESC: {0} INT.CODE: {1} EAN: {2} NEW PRICE: {3} LAST PRICE: {4}]</success>', [utf8_encode(ucwords(strtolower($api_product['descripcionLarga']))),$api_product['codigoMaterial'], $api_product['ean13'].$api_product['digitoVerificador'], $api_product['precioFlejes'], $exist_price_update->price]));

                                $arr['update_count'] = $arr['update_count'] + 1;
                            }
                        }
                        else{
                            $this->out(__('<info>Se mantuvo el precio [INT.CODE: {0} EAN: {1} PRICE: {2}]</info>', [$api_product['codigoMaterial'], $api_product['ean13'].$api_product['digitoVerificador'], $api_product['precioFlejes']]));

                            $arr['keep_count'] = $arr['keep_count'] + 1;
                        }
                    }
                }
                else{
                    //Si no tiene precio, se agrega el precio de la API
                    $price_update = $this->PriceUpdates->newEntity();
                    $price_update->product_store_id = $product_store->id;
                    $price_update->store_id = $store_data->id;
                    $price_update->price = $api_product['precioFlejes'];
                    $price_update->ppums_price = $api_product['importePPUMS'];
                    $price_update->previous_price = $api_product['precioFlejes'];
                    $price_update->previous_ppums_price = $api_product['importePPUMS'];
                    $price_update->company_updated = $new_company_update;

                    if(!$this->PriceUpdates->save($price_update)){

                        $this->out(__('<error>Error while saving the initial update [EAN: {0} INT.CODE: {1} PRICE: {2}]</error>', [$product_store->description, $api_product['codigoMaterial'], $api_product['ean13'].$api_product['digitoVerificador'], $api_product['precioFlejes']]));
                    }
                    else{
                        $this->out(__('<success>Se agrego el precio inicial [DESC: {0} EAN: {1} INT.CODE: {2} PRICE: {3}]</success>', [$product_store->description, $api_product['codigoMaterial'], $api_product['ean13'].$api_product['digitoVerificador'], $api_product['precioFlejes']]));

                        $arr['initial_update_count'] = $arr['initial_update_count'] + 1;
                    }
                }
            }

            print_r($arr);

            $send_email = true;

            
            if($send_email == true){
                $data = [
                    'store' => [
                        'store_name' => $store_data->store_name,
                        'store_code' => $store_data->store_code,
                    ],
                    'company' => [
                        'company_name' => $store_data->company->company_name,
                        'company_keyword' => $store_data->company->company_keyword,
                        'company_logo' => $store_data->company->company_logo,
                    ],
                    'stats' => $arr
                ];

                $email = new EmailsController;
                $email->sendUpdatePricesProcessEmail($data);
            }
        }
    }

    function doLoadInitialPrices($store_data = null){

        //Si no existe tienda omite la fila
        if($store_data == null){
            
            $this->out(__('Store object not found'));
            return false;
        }

        $file_name = $store_data->store_code.'.csv';
        //Excel con maestra de cencosud
        $data_file = ROOT . DIRECTORY_SEPARATOR . 'webroot' . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . 'initials_prices'. DIRECTORY_SEPARATOR . $store_data->company->company_keyword . DIRECTORY_SEPARATOR . $file_name;

        if(!file_exists($data_file)) {
            $this->out(__('File not exist'));
            return false;
        }

        /* Lectura del excel */
        if(file_exists($data_file)){
            $filename = explode('.', $data_file);
            debug($filename);

            if($filename[1]=='csv'){
                
                $store_id = $store_data->id;
                $arr = [];
                $arr['new_products_count'] = 0;
                $arr['update_count'] = 0;
                $arr['keep_count'] = 0;
                $arr['initial_update_count'] = 0;


                $handle = fopen($data_file, "r");

                $x = 0;
                //Iteración productos de la api de precio de cencosud
                while (($original_row = fgetcsv($handle, 1000, ";")) !== FALSE){

                    if($x == 0){
                        $x++;
                        continue;
                    }

                    /*echo '<pre>';
                    print_r($original_row);
                    echo '</pre>';*/


                    if(count($original_row) == 1){
                        $row = explode(',', $original_row[0]);

                    }
                    else{
                        $row = $original_row;
                    }

                    /*echo '<pre>';
                    print_r($row);
                    echo '</pre>';*/

                    $product_store = $this->ProductsStores->find('all')
                        ->contain('PriceUpdates', function ($q) use ($store_id){
                            return $q
                                ->select(['PriceUpdates.id', 'PriceUpdates.product_store_id', 'PriceUpdates.store_id', 'PriceUpdates.price', 'PriceUpdates.ppums_price', 'PriceUpdates.company_updated'])
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
                            'ProductsStores.company_id',
                            'ProductsStores.section_id',
                            'ProductsStores.category_id',
                            'ProductsStores.sub_category_id',
                            'ProductsStores.internal_code',
                            'ProductsStores.description',
                            'ProductsStores.ean13'
                        ])
                        ->where([
                            'ProductsStores.company_id' => $store_data->company->id, 
                            'ProductsStores.internal_code' => $row[1], 
                            'ProductsStores.ean13' => $row[3].$row[4],
                        ])
                        ->first();

                    if(count($row) == 19){
                        $datetime = explode('T', $row[18]);
                    }
                    else{
                        $datetime = explode('T', $row[17]);
                    }
                    //$datetime = explode('T', $row[17]);
                    $new_company_update = new Time($datetime[0].' '.$datetime[1]);

                    $api_product_section_code = intval(substr($row[5], 0, 2));
                    $api_product_category_code = intval(substr($row[5], 2, 2));
                    $api_product_sub_category_code = intval(substr($row[5], 4, 2));

                    //Agregar product
                    $section = $this->getSectionData($store_data->company_id, $api_product_section_code, $api_product_section_code);

                    //Se obtiene categoria
                    $category = $this->getCategoryData($store_data->company_id, $api_product_category_code, $api_product_category_code, $section->id);

                    //Se obtiene sub categoria
                    $sub_category = $this->getSubCategoryData($store_data->company_id, $api_product_sub_category_code, $api_product_sub_category_code, $category->id);

                    if(count($product_store) == 0){

                        $product_store = $this->ProductsStores->newEntity();
                        $product_store->company_id = $store_data->company_id;
                        $product_store->section_id = ($section != null) ? $section->id : null;
                        $product_store->category_id = ($category != null) ? $category->id : null;
                        $product_store->sub_category_id = ($sub_category != null) ? $sub_category->id : null;
                        $product_store->description = utf8_encode(ucwords(strtolower($row[10])));
                        $product_store->internal_code = $row[1];
                        $product_store->ean13 = $row[3].$row[4];

                        if(!$this->ProductsStores->save($product_store)){

                            $this->out(__('<error>Error while saving the product {0} [SAP: {1}] and relationship</error>', [$product_name, $internal_code]));
                        }
                        else{
                            $this->out(__('<success>Se agrego producto nuevo [INT.CODE: {0} EAN: {1} DESCRIPTION: {2}]</success>', [$row[1], $row[3].$row[4], utf8_encode(ucwords(strtolower($row[10])))]));        

                            $arr['new_products_count'] = $arr['new_products_count'] + 1;
                        }

                    }
                    else{
                        $this->out(__('<info>Producto existente [INT.CODE: {0} EAN: {1} DESCRIPTION: {2}]</info>', [$row[1], $row[3].$row[4], utf8_encode(ucwords(strtolower($row[10])))]));

                        if($product_store->section_id == null && $section != null){
                            $product_store->section_id = $section->id;
                            $this->ProductsStores->save($product_store);

                            $this->out(__('<info>Se actualizo categoria 1 [INT.CODE: {0} EAN: {1} DESCRIPTION: {2}]</info>', [$row[1], $row[3].$row[4], utf8_encode(ucwords(strtolower($row[10])))]));
                        }

                        if($product_store->category_id == null && $category != null){
                            $product_store->category_id = $category->id;
                            $this->ProductsStores->save($product_store);

                            $this->out(__('<info>Se actualizo categoria 2 [INT.CODE: {0} EAN: {1} DESCRIPTION: {2}]</info>', [$row[1], $row[3].$row[4], utf8_encode(ucwords(strtolower($row[10])))]));
                        }

                        if($product_store->sub_category_id == null && $sub_category != null){
                            $product_store->sub_category_id = $sub_category->id;
                            $this->ProductsStores->save($product_store);

                            $this->out(__('<info>Se actualizo categoria 3 [INT.CODE: {0} EAN: {1} DESCRIPTION: {2}]</info>', [$row[1], $row[3].$row[4], utf8_encode(ucwords(strtolower($row[10])))]));
                        }
                    }

                    if(isset($product_store->price_updates) && count($product_store->price_updates) > 0){
                        foreach ($product_store->price_updates as $exist_price_update) {
                            
                            if(intval($exist_price_update->price) != $row[11]){

                                //Si no tiene precio, se agrega el precio de la API
                                $price_update = $this->PriceUpdates->newEntity();
                                $price_update->product_store_id = $product_store->id;
                                $price_update->store_id = $store_data->id;
                                $price_update->price = $row[11];
                                $price_update->previous_price = $exist_price_update->price;


                                if(count($row) == 19){
                                    $first = explode('"', $row[12]);
                                    $second = explode('"', $row[13]);

                                    $price_update->ppums_price = $first[1].'.'.$second[0];
                                }
                                else{
                                    $price_update->ppums_price = $row[12];
                                }

                                //$price_update->ppums_price = $row[12];
                                $price_update->previous_ppums_price = $exist_price_update->previous_ppums_price;
                                $price_update->company_updated = $new_company_update;


                                if(!$this->PriceUpdates->save($price_update)){
                        
                                    $this->out(__('<error>Error while saving the new update [INT.CODE: {0} EAN: {1} PRICE: {2}]</error>', [$row[1], $row[3].$row[4], $row[11]]));
                                }
                                else{
                                    $this->out(__('<success>Cambio de precio [DESC: {0} INT.CODE: {1} EAN: {2} NEW PRICE: {3} LAST PRICE: {4}]</success>', [utf8_encode(ucwords(strtolower($row[1]))),$row[1], $row[3].$row[4], $row[11], $exist_price_update->price]));

                                    $arr['update_count'] = $arr['update_count'] + 1;
                                }
                            }
                            else{
                                $this->out(__('<info>Se mantuvo el precio [INT.CODE: {0} EAN: {1} PRICE: {2}]</info>', [$row[1], $row[3].$row[4], $row[11]]));

                                $arr['keep_count'] = $arr['keep_count'] + 1;
                            }
                        }
                    }
                    else{
                        //Si no tiene precio, se agrega el precio de la API
                        $price_update = $this->PriceUpdates->newEntity();
                        $price_update->product_store_id = $product_store->id;
                        $price_update->store_id = $store_data->id;
                        $price_update->price = $row[11];
                        //$price_update->ppums_price = $row[12];
                        $price_update->previous_price = $row[11];
                        //$price_update->previous_ppums_price = $row[12];

                        if(count($row) == 19){
                            $first = explode('"', $row[12]);
                            $second = explode('"', $row[13]);

                            $price_update->ppums_price = $first[1].'.'.$second[0];
                        }
                        else{
                            $price_update->ppums_price = $row[12];
                        }

                        if(count($row) == 19){
                            $first = explode('"', $row[12]);
                            $second = explode('"', $row[13]);

                            $price_update->previous_ppums_price = $first[1].'.'.$second[0];
                        }
                        else{
                            $price_update->previous_ppums_price = $row[12];
                        }

                        $price_update->company_updated = $new_company_update;

                        if(!$this->PriceUpdates->save($price_update)){

                            $this->out(__('<error>Error while saving the initial update [EAN: {0} INT.CODE: {1} PRICE: {2}]</error>', [$product_store->description, $row[1], $row[3].$row[4], $row[11]]));
                        }
                        else{
                            $this->out(__('<success>Se agrego el precio inicial [DESC: {0} EAN: {1} INT.CODE: {2} PRICE: {3}]</success>', [$product_store->description, $row[1], $row[3].$row[4], $row[11]]));

                            $arr['initial_update_count'] = $arr['initial_update_count'] + 1;
                        }
                    }
                }

                print_r($arr);
            }
        } 
    }

    
    function updateStockProducts($store_data){
        $opts = array();

        $http = new Client();
        $api_stock_response = $http->get('https://api.smdigital.cl:8443/cl/v1.0/nrt-sap-stock',['id-tienda' => $store_data->store_code, $opts],['headers' => ['apiKey' => 'WoLlS3hnIPeFeOxEdykbGB2mIGnvS4a5'], 'type' => 'json']);

        echo '<pre>';
        print_r($api_stock_response->json);
        echo '</pre>';
    }

    function updateDealProducts($company_keyword){

        $send_email = true;
        
        //Si no existe tienda omite la fila
        if($company_keyword == null){
            
            $this->out(__('Company keyword not found'));
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
                $stores_list[$store->store_code]['store_name'] = $store->store_name;
                $stores_list[$store->store_code]['company_name'] = $company->company_name;
                $stores_list[$store->store_code]['id'] = $store->id;
                $stores_list[$store->store_code]['new_products_count'] = 0;
                $stores_list[$store->store_code]['deals_count'] = 0;
                $stores_list[$store->store_code]['ignore_count'] = 0;
            }
        }

        $dir = new Folder(ROOT . DIRECTORY_SEPARATOR . 'webroot' . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . 'deals'. DIRECTORY_SEPARATOR . $company->company_keyword);

        $files = $dir->find('.*\.xlsx', true);

        if(count($files) > 0){

            print_r($files);
            for($z=0; $z < count($files); $z++){
                //Excel con maestra de cencosud
                $data_file = ROOT . DIRECTORY_SEPARATOR . 'webroot' . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . 'deals'. DIRECTORY_SEPARATOR . $company->company_keyword . DIRECTORY_SEPARATOR . $files[$z];


                //Si el archivo existe
                if (file_exists($data_file)) {

                    $this->out(__('Processing file: {0}', $files[$z]));

                    /* Lectura del excel */
                    $file = new File($data_file);
                    $registry = Registry::instance();
                    $spreadsheet = $registry->getManager()->getSpreadsheet($file); // \PhpOffice\PhpSpreadsheet\Spreadsheet instance
                    $worksheet = $spreadsheet->getActiveSheet();

                    $excel_data = $worksheet->toArray();
                    /* Fin Lectura del excel */

                    if(count($excel_data) > 0){
                        
                        /*
                        if($send_email == true){
                            $data = [
                                'store' => [
                                    'store_name' => __('All'),
                                    'store_code' => __('All'),
                                ],
                                'company' => [
                                    'company_name' => $company->company_name,
                                    'company_logo' => $company->company_logo
                                ],
                                'master_date' => $catalog_date,
                                'products_quantity' => count($excel_data)
                            ];

                            $email = new EmailsController;
                            $email->sendInitMasterProcessEmail($data);
                        }*/

                        for($x=0; $x < count($excel_data); $x++){

                            if($x == 0){
                                continue;
                            }

                            /*echo '<pre>';
                            print_r($x);
                            echo '</pre>';

                            die();*/

                            $store_exist = false;
                            $store_id = null;

                            //print_r($excel_data[$x]);

                            $start_date_array = explode('/', $excel_data[$x][17]);

                            if(count($start_date_array) == 1){
                                $start_date_array = explode('.', $excel_data[$x][17]);

                                $start_date = New Time($start_date_array[2].'-'.$start_date_array[1].'-'.$start_date_array[0]);
                                $start_date_query = $start_date->format('Y-m-d');
                            }
                            else{
                                $start_date = New Time($start_date_array[2].'-'.$start_date_array[0].'-'.$start_date_array[1]);
                                $start_date_query = $start_date->format('Y-m-d');
                            }

                            $end_date_array = explode('/', $excel_data[$x][18]);

                            if(count($end_date_array) == 1){
                                $end_date_array = explode('.', $excel_data[$x][18]);

                                $end_date = New Time($end_date_array[2].'-'.$end_date_array[1].'-'.$end_date_array[0]);
                                $end_date_query = $end_date->format('Y-m-d');
                            }
                            else{
                                $end_date = New Time($end_date_array[2].'-'.$end_date_array[0].'-'.$end_date_array[1]);
                                $end_date_query = $end_date->format('Y-m-d');
                            }

                            $store_array = explode(' ', $excel_data[$x][0]);
                            //print_r($store_array);
                            foreach ($store_array as $key => $value) {
                                if(isset($stores_list[$value])){
                                    $store_id = $stores_list[$value]['id'];
                                    $store_code = $key;
                                    $store_exist = true;
                                    break;
                                }
                            }

                            if($excel_data[$x][9] == ''){

                                $this->out(__('<comment>Producto sin EAN [INT.CODE: {1} DESCRIPTION: {2}] - Ignorando</comment>', [$excel_data[$x][8], utf8_encode(ucwords(strtolower($excel_data[$x][11])))]));

                                $stores_list[$store_code]['ignore_count'] = $stores_list[$store_code]['ignore_count'] + 1;

                                continue;
                            }
                            else{
                                $ean_array = explode('-', $excel_data[$x][9]);

                                $ean13 = '';
                                if(count($ean_array) > 1){
                                    $ean13 = $ean_array[0].$ean_array[1];
                                }
                                else{
                                    $ean13 = $excel_data[$x][9];
                                }
                            }

                            /*$section_code = $excel_data[$x][6];
                            if(strlen($section_code) == 1){
                                $section_code = '0'.$section_code;
                            }*/
                            //Se obtiene seccion
                            //$section = $this->getSectionData($company->id, $section_code, $section_code);

                            $section = null;

                            //Se obtiene categoria
                            $category = null;

                            //Se obtiene sub categoria
                            $sub_category = null;

                            if($store_exist == true){
                                $this->out(__('<success>STORE: '.$store_code.'</success>'));

                                $product_store = $this->ProductsStores->find('all')
                                    ->contain([
                                        'DealUpdates' => function (\Cake\ORM\Query $query) use ($store_id, $start_date_query, $end_date_query){
                                            return $query
                                                //->select(['DealUpdates.id', 'DealUpdates.product_store_id', 'DealUpdates.store_id'])
                                                ->where([
                                                    'DealUpdates.store_id' => $store_id, 
                                                    'DATE(DealUpdates.start_date)' => $start_date_query,
                                                    'DATE(DealUpdates.end_date)' => $end_date_query
                                                ])
                                                ->limit(1);
                                        }
                                    ])
                                    ->select([
                                        'ProductsStores.id',
                                        'ProductsStores.company_id',
                                        'ProductsStores.section_id',
                                        'ProductsStores.category_id',
                                        'ProductsStores.sub_category_id',
                                        'ProductsStores.internal_code',
                                        'ProductsStores.ean13',
                                        'ProductsStores.description'
                                    ])
                                    ->where([
                                        'ProductsStores.company_id' => $company->id, 
                                        'ProductsStores.internal_code' => $excel_data[$x][8], 
                                        'ProductsStores.ean13' => $ean13
                                    ])
                                    ->first();

                                // Si no existe producto, se agrega a la base de datos
                                if(count($product_store) == 0){

                                    if(strlen($ean13) <= 13 && utf8_encode(ucwords(strtolower($excel_data[$x][11]))) != '' && $excel_data[$x][8] != ''){

                                        $product_store = $this->ProductsStores->newEntity();
                                        $product_store->company_id = $company->id;
                                        $product_store->section_id = ($section != null) ? $section->id : $section;
                                        $product_store->category_id = ($category != null) ? $category->id : $category;
                                        $product_store->sub_category_id = ($sub_category != null) ? $sub_category->id : $sub_category;
                                        $product_store->description = utf8_encode(ucwords(strtolower($excel_data[$x][11])));
                                        $product_store->internal_code = $excel_data[$x][8];
                                        //Ignorar 14 digitos
                                        $product_store->ean13 = $ean13;

                                        if(!$this->ProductsStores->save($product_store)){

                                            $this->out(__('<error>Error while saving the product [EAN: {0} INT.CODE: {1}]</error>', [$product_store->ean13, $product_store->internal_code]));
                                        }
                                        else{
                                            $this->out(__('<success>Se agrego producto inicial [EAN: {0} INT.CODE: {1} DESCRIPTION: {2}]</success>', [$product_store->ean13, $product_store->internal_code, $product_store->description]));
                                            $stores_list[$store_code]['new_products_count'] = $stores_list[$store_code]['new_products_count'] + 1;
                                        }
                                    }
                                    else{
                                        $this->out(__('<comment>Producto con EAN de 14 dígitos [EAN: {0} INT.CODE: {1} DESCRIPTION: {2}] - Ignorando</comment>', [$ean13, $excel_data[$x][8], utf8_encode(ucwords(strtolower($excel_data[$x][11])))]));

                                        $stores_list[$store_code]['ignore_count'] = $stores_list[$store_code]['ignore_count'] + 1;

                                        continue;
                                    }
                                }
                                else{
                                    $this->out(__('<info>Ya existe el producto [EAN: {0} INT.CODE: {1} DESCRIPTION: {2}]</info>', [$product_store->ean13, $product_store->internal_code, $product_store->description]));

                                    if($product_store->section_id == null && $section != null){
                                        $product_store->section_id = $section->id;
                                        $this->ProductsStores->save($product_store);

                                        $this->out(__('<info>Se actualizo categoria 1 [INT.CODE: {0} EAN: {1} DESCRIPTION: {2}]</info>', [$api_product['codigoMaterial'], $api_product['ean13'].$api_product['digitoVerificador'], $api_product['descripcionLarga']]));
                                    }
                                }


                                if(isset($product_store->deal_updates) && count($product_store->deal_updates) > 0){


                                    foreach ($product_store->deal_updates as $exist_deal_update) {
                                        
                                        $this->out(__('<info>Ya existe la oferta {0} [EAN: {1} INT.CODE: {2} START: {3} END: {4}]</info>', [$exist_deal_update->deal_description, $product_store->internal_code, $product_store->ean13, $exist_deal_update->start_date->format('d-m-Y'), $exist_deal_update->end_date->format('d-m-Y')]));
                                        $inject_new_deal = false;
                                        
                                        if($exist_deal_update->start_date != $start_date){
                                            $inject_new_deal = true;
                                        }

                                        if($exist_deal_update->end_date != $end_date){
                                            $inject_new_deal = true;
                                        }

                                        if($inject_new_deal == true){

                                            $deal_update = $this->DealUpdates->newEntity();
                                            $deal_update->product_store_id = $product_store->id;
                                            $deal_update->store_id = $store_id;
                                            $deal_update->deal_description = $excel_data[$x][7];
                                            $deal_update->value = $excel_data[$x][15];

                                            
                                            $deal_update->start_date = $start_date;
                                            $deal_update->end_date = $end_date;
                                            $deal_update->deal_code = null;
                                            $deal_update->deal_type = $excel_data[$x][13];

                                            if(!$this->DealUpdates->save($deal_update)){

                                                $this->out(__('<error>Error while saving the deal [EAN: {0} INT.CODE: {1}]</error>', [$product_store->description, $product_store->internal_code, $product_store->ean13, $api_product['precioFlejes']]));
                                            }
                                            else{
                                                $this->out(__('<success>[{0}] Se agrego la oferta {1} [EAN: {2} INT.CODE: {3} START: {4} END: {5}]</success>', [$store_code, $deal_update->deal_description, $product_store->internal_code, $product_store->ean13, $start_date->format('d-m-Y'), $end_date->format('d-m-Y')]));

                                                $stores_list[$store_code]['deals_count'] = $stores_list[$store_code]['deals_count'] + 1;
                                            }
                                        }
                                        /*if(intval($exist_price_update->price) != $api_product['precioFlejes']){

                                            //Si no tiene precio, se agrega el precio de la API
                                            $price_update = $this->PriceUpdates->newEntity();
                                            $price_update->product_store_id = $product_store->id;
                                            $price_update->store_id = $store_data->id;
                                            $price_update->price = $api_product['precioFlejes'];
                                            $price_update->previous_price = $exist_price_update->price;
                                            $price_update->ppums_price = $api_product['importePPUMS'];
                                            $price_update->previous_ppums_price = $exist_price_update->previous_ppums_price;
                                            $price_update->company_updated = $new_company_update;


                                            if(!$this->PriceUpdates->save($price_update)){
                                    
                                                $this->out(__('<error>Error while saving the new update [INT.CODE: {0} EAN: {1} PRICE: {2}]</error>', [$api_product['codigoMaterial'], $api_product['ean13'].$api_product['digitoVerificador'], $api_product['precioFlejes']]));
                                            }
                                            else{
                                                $this->out(__('<success>Cambio de precio [INT.CODE: {0} EAN: {1} NEW PRICE: {2} LAST PRICE: {3}]</success>', [$api_product['codigoMaterial'], $api_product['ean13'].$api_product['digitoVerificador'], $api_product['precioFlejes'], $exist_price_update->price]));

                                                $arr['update_count'] = $arr['update_count'] + 1;
                                            }
                                        }
                                        else{
                                            $this->out(__('<info>Se mantuvo el precio [INT.CODE: {0} EAN: {1} PRICE: {2}]</info>', [$api_product['codigoMaterial'], $api_product['ean13'].$api_product['digitoVerificador'], $api_product['precioFlejes']]));

                                            $arr['keep_count'] = $arr['keep_count'] + 1;
                                        }*/
                                    }
                                }
                                else{
                                    //Si no tiene precio, se agrega el precio de la API
                                    $deal_update = $this->DealUpdates->newEntity();
                                    $deal_update->product_store_id = $product_store->id;
                                    $deal_update->store_id = $store_id;
                                    $deal_update->deal_description = $excel_data[$x][7];
                                    $deal_update->value = $excel_data[$x][15];

                                    
                                    $deal_update->start_date = $start_date;
                                    $deal_update->end_date = $end_date;
                                    $deal_update->deal_code = null;
                                    $deal_update->deal_type = $excel_data[$x][13];

                                    if(!$this->DealUpdates->save($deal_update)){

                                        $this->out(__('<error>Error while saving the deal [EAN: {0} INT.CODE: {1}]</error>', [$product_store->description, $product_store->internal_code, $product_store->ean13, $api_product['precioFlejes']]));
                                    }
                                    else{
                                        $this->out(__('<success>[{0}] Se agrego la oferta {1} [EAN: {2} INT.CODE: {3} START: {4} END: {5}]</success>', [$store_code, $deal_update->deal_description, $product_store->internal_code, $product_store->ean13, $start_date->format('d-m-Y'), $end_date->format('d-m-Y')]));

                                        $stores_list[$store_code]['deals_count'] = $stores_list[$store_code]['deals_count'] + 1;
                                    }
                                }
                            }
                            else{
                            
                                foreach($stores_list as $store_code => $data){

                                    $store_id = $stores_list[$store_code]['id'];
                                    

                                    $product_store = $this->ProductsStores->find('all')
                                        ->contain([
                                            'DealUpdates' => function (\Cake\ORM\Query $query) use ($store_id, $start_date_query, $end_date_query){
                                                return $query
                                                    //->select(['DealUpdates.id', 'DealUpdates.product_store_id', 'DealUpdates.store_id'])
                                                    ->where([
                                                        'DealUpdates.store_id' => $store_id, 
                                                        'DATE(DealUpdates.start_date)' => $start_date_query,
                                                        'DATE(DealUpdates.end_date)' => $end_date_query
                                                    ])
                                                    ->limit(1);
                                            }
                                        ])
                                        ->select([
                                            'ProductsStores.id',
                                            'ProductsStores.company_id',
                                            'ProductsStores.section_id',
                                            'ProductsStores.category_id',
                                            'ProductsStores.sub_category_id',
                                            'ProductsStores.internal_code',
                                            'ProductsStores.ean13',
                                            'ProductsStores.description'
                                        ])
                                        ->where([
                                            'ProductsStores.company_id' => $company->id, 
                                            'ProductsStores.internal_code' => $excel_data[$x][8], 
                                            'ProductsStores.ean13' => $ean13
                                        ])
                                        ->first();

                                    // Si no existe producto, se agrega a la base de datos
                                    if($product_store == null){

                                        if(strlen($ean13) <= 13 && utf8_encode(ucwords(strtolower($excel_data[$x][11]))) != '' && $excel_data[$x][8] != ''){

                                            $product_store = $this->ProductsStores->newEntity();
                                            $product_store->company_id = $company->id;
                                            $product_store->section_id = ($section != null) ? $section->id : $section;
                                            $product_store->category_id = ($category != null) ? $category->id : $category;
                                            $product_store->sub_category_id = ($sub_category != null) ? $sub_category->id : $sub_category;
                                            $product_store->description = utf8_encode(ucwords(strtolower($excel_data[$x][11])));
                                            $product_store->internal_code = $excel_data[$x][8];
                                            //Ignorar 14 digitos
                                            $product_store->ean13 = $ean13;

                                            if(!$this->ProductsStores->save($product_store)){

                                                $this->out(__('<error>Error while saving the product [EAN: {0} INT.CODE: {1}]</error>', [$product_store->ean13, $product_store->internal_code]));
                                            }
                                            else{
                                                $this->out(__('<success>Se agrego producto inicial [EAN: {0} INT.CODE: {1} DESCRIPTION: {2}]</success>', [$product_store->ean13, $product_store->internal_code, $product_store->description]));
                                                $stores_list[$store_code]['new_products_count'] = $stores_list[$store_code]['new_products_count'] + 1;
                                            }
                                        }
                                        else{
                                            $this->out(__('<comment>Producto con EAN de 14 dígitos [EAN: {0} INT.CODE: {1} DESCRIPTION: {2}] - Ignorando</comment>', [$ean13, $excel_data[$x][8], utf8_encode(ucwords(strtolower($excel_data[$x][11])))]));

                                            $stores_list[$store_code]['ignore_count'] = $stores_list[$store_code]['ignore_count'] + 1;

                                            continue;
                                        }
                                    }
                                    else{
                                        $this->out(__('<info>Ya existe el producto [EAN: {0} INT.CODE: {1} DESCRIPTION: {2}]</info>', [$product_store->ean13, $product_store->internal_code, $product_store->description]));

                                        if($product_store->section_id == null && $section != null){
                                            $product_store->section_id = $section->id;
                                            $this->ProductsStores->save($product_store);

                                            $this->out(__('<info>Se actualizo categoria 1 [INT.CODE: {0} EAN: {1} DESCRIPTION: {2}]</info>', [$api_product['codigoMaterial'], $api_product['ean13'].$api_product['digitoVerificador'], $api_product['descripcionLarga']]));
                                        }
                                    }


                                    if(isset($product_store->deal_updates) && count($product_store->deal_updates) > 0){
                                        foreach ($product_store->deal_updates as $exist_deal_update) {
                                            
                                            $this->out(__('<info>Ya existe la oferta {0} [EAN: {1} INT.CODE: {2} START: {3} END: {4}]</info>', [$exist_deal_update->deal_description, $product_store->internal_code, $product_store->ean13, $exist_deal_update->start_date->format('d-m-Y'), $exist_deal_update->end_date->format('d-m-Y')]));


                                            //Comparar columna medio con deal code para agregar nueva oferta si no existiese OOJOOO


                                            /*if(intval($exist_price_update->price) != $api_product['precioFlejes']){

                                                //Si no tiene precio, se agrega el precio de la API
                                                $price_update = $this->PriceUpdates->newEntity();
                                                $price_update->product_store_id = $product_store->id;
                                                $price_update->store_id = $store_data->id;
                                                $price_update->price = $api_product['precioFlejes'];
                                                $price_update->previous_price = $exist_price_update->price;
                                                $price_update->ppums_price = $api_product['importePPUMS'];
                                                $price_update->previous_ppums_price = $exist_price_update->previous_ppums_price;
                                                $price_update->company_updated = $new_company_update;


                                                if(!$this->PriceUpdates->save($price_update)){
                                        
                                                    $this->out(__('<error>Error while saving the new update [INT.CODE: {0} EAN: {1} PRICE: {2}]</error>', [$api_product['codigoMaterial'], $api_product['ean13'].$api_product['digitoVerificador'], $api_product['precioFlejes']]));
                                                }
                                                else{
                                                    $this->out(__('<success>Cambio de precio [INT.CODE: {0} EAN: {1} NEW PRICE: {2} LAST PRICE: {3}]</success>', [$api_product['codigoMaterial'], $api_product['ean13'].$api_product['digitoVerificador'], $api_product['precioFlejes'], $exist_price_update->price]));

                                                    $arr['update_count'] = $arr['update_count'] + 1;
                                                }
                                            }
                                            else{
                                                $this->out(__('<info>Se mantuvo el precio [INT.CODE: {0} EAN: {1} PRICE: {2}]</info>', [$api_product['codigoMaterial'], $api_product['ean13'].$api_product['digitoVerificador'], $api_product['precioFlejes']]));

                                                $arr['keep_count'] = $arr['keep_count'] + 1;
                                            }*/
                                        }
                                    }
                                    else{
                                        //Si no tiene precio, se agrega el precio de la API
                                        if(strlen($excel_data[$x][7]) <= 80){
                                            $description = $excel_data[$x][7];
                                        }
                                        else{
                                            $description = substr($excel_data[$x][7], 0, 79);
                                        }
                                        
                                        $deal_update = $this->DealUpdates->newEntity();
                                        $deal_update->product_store_id = $product_store->id;
                                        $deal_update->store_id = $store_id;
                                        $deal_update->deal_description = $description;
                                        $deal_update->value = $excel_data[$x][15];

                                        
                                        $deal_update->start_date = $start_date;
                                        $deal_update->end_date = $end_date;
                                        $deal_update->deal_code = $excel_data[$x][2];
                                        $deal_update->deal_type = $excel_data[$x][13];

                                        if(!$this->DealUpdates->save($deal_update)){

                                            $this->out(__('<error>Error while saving the deal [EAN: {0} INT.CODE: {1}]</error>', [$product_store->description, $product_store->internal_code, $product_store->ean13, $api_product['precioFlejes']]));
                                        }
                                        else{
                                            $this->out(__('<success>[{0}] Se agrego la oferta {1} [EAN: {2} INT.CODE: {3} START: {4} END: {5}]</success>', [$store_code, $deal_update->deal_description, $product_store->internal_code, $product_store->ean13, $start_date->format('d-m-Y'), $end_date->format('d-m-Y')]));

                                            $stores_list[$store_code]['deals_count'] = $stores_list[$store_code]['deals_count'] + 1;
                                        }
                                    }
                                }
                            }

                            continue;
                        }

                        print_r($stores_list);
                    }

                    unlink($data_file);
                }
            }
        }
    }

    /**
    **
    Busca la seccion por su codigo, de no encontrarla crea una nueva y devuelve el objeto
    **
    **/
    function getSectionData($company_id = null, $section_code = null, $section_name = null){

        $section = $this->ProductsStores->Sections->find('all', ['conditions' => ['Sections.section_code' => $section_code, 'Sections.company_id' => $company_id]])->select(['Sections.id', 'Sections.section_code'])->first();

        if($section == null && $section_name != null){

            $section = $this->Sections->newEntity();
            $section->company_id = $company_id;
            $section->section_name = ucwords(strtolower($section_name));
            $section->section_code = ucwords(strtolower($section_code));

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

        $category = $this->ProductsStores->Categories->find('all', ['conditions' => ['Categories.category_code' => $category_code, 'Categories.company_id' => $company_id, 'Categories.section_id' => $section_id]])->select(['Categories.id', 'Categories.category_code'])->first();

        if($category == null && $category_name != null && $section_id != null){

            $category = $this->Categories->newEntity();
            $category->company_id = $company_id;
            $category->section_id = $section_id;
            $category->category_name = ucwords(strtolower($category_name));
            $category->category_code = ucwords(strtolower($category_code));

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

        $sub_category = $this->ProductsStores->SubCategories->find('all', ['conditions' => ['SubCategories.sub_category_code' => $sub_category_code, 'SubCategories.company_id' => $company_id, 'SubCategories.category_id' => $category_id]])->select(['SubCategories.id', 'SubCategories.sub_category_code'])->first();

        if($sub_category == null && $sub_category_name != null && $category_id != null){

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

    function generateNewProduct($product_name = null, $internal_code = null, $ean_code = null, $company_id = null, $store_id = null, $section_id = null, $category_id = null, $sub_category_id = null, $catalog_date = null, $cataloged = null, $enabled = null, $stock_up_to_date = null, $upload_cloud = false){

        //Object producto
        $product = $this->Products->newEntity();

        $product->product_name = utf8_encode(ucwords(strtolower($product_name)));
        $product->product_description = utf8_encode(ucwords(strtolower($product_name)));

        if($ean_code != null){
            $product->product_name = utf8_encode(ucwords(strtolower($product_name)));

            $ean_original_length = strlen($ean_code);
            $ean_new_length = $ean_original_length -1;
            $product->ean13 = substr($ean_code, 0, $ean_new_length);
            $product->ean13_digit = substr($ean_code, -1);
        }
        

        if ($this->Products->save($product)) {

            $this->out(__('<question>Product {0}: Saved successful on {1}</question>', [$product->product_name, date('d-m-Y H:i:s')]));

            //return $product;
            $master_date = new Time($catalog_date);

            $new_product_store = $this->ProductsStores->newEntity();
            $new_product_store->product_id = $product->id;
            $new_product_store->company_id = $company_id;
            $new_product_store->store_id = $store_id;
            $new_product_store->section_id = $section_id;
            $new_product_store->category_id = $category_id;
            $new_product_store->sub_category_id = $sub_category_id;
            $new_product_store->master_catalog_date = $master_date;
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

                $new_product_store->product = $product; 

                return $new_product_store;
            }
        }
        else{
            $this->out(__('<error>Error while saving the product {0}</error>', [$product_name]));
            return false;
        }
    }

    function uploadCloud($object = null, $table_name = null, $id = null){
        
        if($object != null && $table_name != null){
            $projectId = 'cencosud-chile';
            $dataSet = 'public';

            $object_data = json_decode(json_encode($object), true);

            //print_r($object_data);

            $insertId = '';

            if($id != null){
                $insertId = $id;
            }   
            else{
                $insertId = $object_data['id'];
            }

            if($this->stream_row($projectId, $dataSet, $table_name, $object_data, $insertId) == true){
                return true;
            }
        }

        return false;
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


    function testGmail(){
        // Get the API client and construct the service object.
        $client = $this->getClient();
        $service = new \Google_Service_Gmail($client);
        $userId = 'reports@zippedi.com';

        // Print the labels in the user's account.
        $user = 'me';
        $results = $service->users_labels->listUsersLabels($user);

        if (count($results->getLabels()) == 0) {
          print "No labels found.\n";
        } else {
          print "Labels:\n";
          foreach ($results->getLabels() as $label) {
            printf("- %s\n", $label->getName());
          }
        }

        $messages_list = $this->listMessages($service, $userId);
        $file_finded = false;
        if(count($messages_list) > 0){
            foreach ($messages_list as $message_list) {
                $message_data = $this->getMessage($service, $userId, $message_list->id);

                //echo $message_data->getId().'<br>';

                $messageDetails = $message_data->getPayload();
                foreach ($messageDetails['parts'] as $key => $value) {
                    if ($value['body']['attachmentId'] != '' && $value['mimeType'] =='application/x-msexcel') {
                        //array_push($files, $value['partId']);
                        $value['body']['attachmentId'];

                        //$attachment_array = $this->getMessageAttachment($service, $userId, $message_list->id, $value['body']['attachmentId']);
                        //print_r($attachment_array);
                        echo 'attachId: '.$value['body']['attachmentId'].' - message_id: '.$message_list->id.'<br>';
                        $file_finded = true;
                        break;
                    }
                }

                if($file_finded == true){
                    break;
                }

                //$attachment = $this->getMessageAttachment($service, $userId, $message_data->getId(), $attachmentId);
                /*if(count($message_data['parts']) > 0){
                    foreach($message_data['parts'] as $part){
                        if($part->body->attachmentId != ''){
                            
                        }

                        echo $part->body->attachmentId.'<br>';
                    }
                }*/

                /*$time = Time::createFromTimestamp($message_data->internalDate);
                echo '<pre>';
                print_r($message_data);
                echo '</pre>';

                $time = Time::createFromTimestamp($message_data->internalDate);

                $attachmentId = $message_data['body']->attachmentId;
                echo '<pre>';
                print_r($time);
                echo '</pre>';

                echo $attachmentId.'<br>';*/
            }
        }
    }

    function getClient()
    {
        $json_file = ROOT . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'client_secret.json';

        $client = new \Google_Client();
        $client->setApplicationName('Gmail API PHP Quickstart');
        $client->setScopes(\Google_Service_Gmail::GMAIL_READONLY);
        $client->setAuthConfig($json_file);
        $client->setAccessType('offline');

        // Load previously authorized credentials from a file.
        $credentialsPath = $this->expandHomeDirectory('credentials.json');

        if (file_exists($credentialsPath)) {
            $accessToken = json_decode(file_get_contents($credentialsPath), true);
        } else {
            // Request authorization from the user.
            $authUrl = $client->createAuthUrl();
            printf("Open the following link in your browser:\n%s\n", $authUrl);
            print 'Enter verification code: ';
            $authCode = trim(fgets(STDIN));

            // Exchange authorization code for an access token.
            $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);

            // Store the credentials to disk.
            echo $credentialsPath;
            if (!file_exists(dirname($credentialsPath))) {
                mkdir(dirname($credentialsPath), 0700, true);
            }
            file_put_contents($credentialsPath, json_encode($accessToken));
            printf("Credentials saved to %s\n", $credentialsPath);


        }
        $client->setAccessToken($accessToken);

        // Refresh the token if it's expired.
        if ($client->isAccessTokenExpired()) {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            file_put_contents($credentialsPath, json_encode($client->getAccessToken()));
        }
        return $client;
    }

    function expandHomeDirectory($path) {
        $homeDirectory = getenv('HOME');
        if (empty($homeDirectory)) {
            $homeDirectory = getenv('HOMEDRIVE') . getenv('HOMEPATH');
        }

        return ROOT . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . $path;
        //return str_replace('~', realpath($homeDirectory), $path);
    }

    function listMessages($service, $userId) {
        $pageToken = NULL;
        $messages = array();
        $opt_param = array();
        do {
            try {
                if ($pageToken) {
                    $opt_param['pageToken'] = $pageToken;
                }

                $opt_param['pageToken'] = $pageToken;
                $opt_param['q'] = 'rv: maestra catalogados';
                $opt_param['maxResults'] = '5';

                $messagesResponse = $service->users_messages->listUsersMessages($userId, $opt_param);
                if ($messagesResponse->getMessages()) {
                    $messages = array_merge($messages, $messagesResponse->getMessages());
                    $pageToken = $messagesResponse->getNextPageToken();
                }
            } 
            catch (Exception $e) {
                print 'An error occurred: ' . $e->getMessage();
            }
        } while ($pageToken);

        foreach ($messages as $message) {
            //print 'Message with ID: ' . $message->getId() . '<br/>';
        }

        return $messages;
    }

    function getMessage($service, $userId, $messageId) {
      try {
        $message = $service->users_messages->get($userId, $messageId);
        //print 'Message with ID: ' . $message->getId() . ' retrieved.';
        return $message;
      } catch (Exception $e) {
        print 'An error occurred: ' . $e->getMessage();
      }
    }

    function getMessageAttachment($service, $userId, $messageId, $attachmentId) {
      try {
        $attachmentObject = $service->users_messages_attachments->get($userId, $messageId, $attachmentId);
        //print 'Message with ID: ' . $message->getId() . ' retrieved.';
        return $attachmentObject;
      } catch (Exception $e) {
        print 'An error occurred: ' . $e->getMessage();
      }
    }

    public function getAttachment($messageId, $partId)
    {
        try {
            $files = [];
            $gmail = new \Google_Service_Gmail($this->authenticate->getClient());
            $attachmentDetails = $this->getAttachmentDetailsFromMessage($messageId, $partId);
            $attachment = $gmail->users_messages_attachments->get($this->authenticate->getUserId(), $messageId, $attachmentDetails['attachmentId']);
            if (!$attachmentDetails['status']) {
                return $attachmentDetails;
            }
            $attachmentDetails['data'] = $this->base64UrlDecode($attachment->data);
            return ['status' => true, 'data' => $attachmentDetails];
        } catch (\Google_Service_Exception $e) {
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }
}