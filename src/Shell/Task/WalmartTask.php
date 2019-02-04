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

class WalmartTask extends Shell
{
    public function initialize()
    {
        parent::initialize();
        $this->loadModel('ProductsStores');
        $this->loadModel('Stores');
        $this->loadModel('Products');
        $this->loadModel('Sections');
        $this->loadModel('Categories');
        $this->loadModel('SubCategories');
        $this->loadModel('ThirdCategories');
        $this->loadModel('Companies');
        $this->loadModel('MeasurementUnits');
        $this->loadModel('Aisles');
        $this->loadModel('AnalyzedProducts');
    }

    

    public function main()
    {
        $this->out('llego a sodimac');
    }

    public function doMasterProcess($store_data = null, $upload_cloud = false){

        $send_email = true;
        //Validar objeto tienda
        if($store_data == null){
            $this->out(__('Store object not found'));
            return false;
        }
        
        $file_name = '20180528.csv';
        //Excel con maestra de cencosud
        $data_file = ROOT . DIRECTORY_SEPARATOR . 'webroot' . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . 'active_masters'. DIRECTORY_SEPARATOR . $store_data->company->company_keyword . DIRECTORY_SEPARATOR . $file_name;

        if(!file_exists($data_file)) {
            $this->out(__('File not exist'));
            return false;
        }

        /* Lectura del excel */
        if(file_exists($data_file)){
            $filename = explode('.', $data_file);
            debug($filename);

            if($filename[1]=='csv'){

                //Se obtiene fecha de la maestra del nombre del archivo
                $split_file_array = explode('.', $file_name);
                $file_info = explode('_', $split_file_array[0]);
                $catalog_date = new Time(substr($file_info[0], 0, 4).'-'.substr($file_info[0], 4, 2).'-'.substr($file_info[0], 6, 2));

                //Mensaje de inicio del proceso
                $this->out(__('[Status: Startup at {0}] {1} - [{2}] {3} Cataloged Master for {4}', [date('d-m-Y H:i:s'), $store_data->company->company_name, $store_data->store_code, $store_data->store_name, $catalog_date->format('d-m-Y')]));

                if($send_email == true){
                    $catalog_date = new Time(date('Y-m-d'));
                    $data = [
                        'store' => [
                            'store_name' => $store_data->store_name,
                            'store_code' => $store_data->store_code,
                        ],
                        'company' => [
                            'company_name' => $store_data->company->company_name,
                            'company_logo' => $store_data->company->company_logo
                        ],
                        'master_date' => $catalog_date,
                        //'products_quantity' => count($excel_data)
                    ];

                    $email = new EmailsController;
                    $email->sendInitMasterProcessEmail($data);
                }

                if($upload_cloud == true){

                    $products_stores_start = '';
                    $products_start = '';
                    $sections_start = '';
                    $categories_start = '';
                    $sub_categories_start = '';
                    $third_categories_start = '';
                }
                    

                $handle = fopen($data_file, "r");

                $x = 0;
                while (($row = fgetcsv($handle, 1000, ";")) !== FALSE){

                    if($x == 0){
                        $x++;
                        continue;
                    }

                    /*$ean_original_length = strlen($row[3]);
                    $ean_new_length = $ean_original_length -1;
                    $ean13 = substr($row[3], 0, $ean_new_length);
                    $ean13_digit = substr($row[3], -1);*/

                    //Se obtiene digito verificador ya que no lo trae incluido
                    $ean13 = $row[3];
                    $ean13_digit = $this->ean13_checksum($row[3]);

                    $company_id = $store_data->company_id;
                    $store_id = $store_data->id;

                    $current_product= $this->Products->find()
                        ->contain([
                            'ProductsStores' => function (\Cake\ORM\Query $query) use ($company_id, $store_id){
                                return $query
                                    ->where(['ProductsStores.company_id' => $company_id, 'ProductsStores.store_id' => $store_id])
                                    ->order(['ProductsStores.master_catalog_date' => 'DESC'])
                                    ->limit(1);
                            }
                        ])
                        ->where(['Products.ean13' => $ean13, 'Products.ean13_digit' => $ean13_digit])
                        ->first();

                    //Se busca producto con el SAP
                    /*$current_product_store = $this->ProductsStores->find()
                        ->contain('Products', function ($q) {
                            return $q
                                ->select(['Products.id', 'Products.product_name']);
                        })
                        ->select([
                            'ProductsStores.id', 'ProductsStores.company_internal_code', 'ProductsStores.company_id', 'ProductsStores.store_id', 'ProductsStores.product_id', 'ProductsStores.section_id', 'ProductsStores.category_id', 'ProductsStores.sub_category_id', 'ProductsStores.strip_price', 'ProductsStores.company_update'
                        ])
                        ->where([
                            //'ProductsStores.company_internal_code' => $row[0],
                            'ProductsStores.company_id' => $store_data->company_id,
                            'ProductsStores.store_id' => $store_data->id,
                        ])
                        ->matching('Products', function ($q) use ($ean13, $ean13_digit){
                            return $q
                                ->where(['Products.ean13' => $ean13, 'Products.ean13_digit' => $ean13_digit]);
                        })
                        ->group('ProductsStores.product_id')
                        ->order(['ProductsStores.master_catalog_date' => 'DESC', 'ProductsStores.company_update' => 'DESC'])
                        ->toarray();*/

                    // Si no existe producto, se agrega a la base de datos
                    if(count($current_product) == 0){

                        //Se obtiene section
                        $section = $this->getSectionData($store_data->company_id, $row[10], $row[9]);

                        //To BigQuery
                        if($upload_cloud == true && $sections_start == ''){
                            $sections_start = $section->id;
                        }
                        
                        //Se obtiene categoria
                        $category = $this->getCategoryData($store_data->company_id, $section->id, $this->cleanString($row[14]), $row[13]);

                        //To BigQuery
                        if($upload_cloud == true && $categories_start == ''){
                            $categories_start = $category->id;
                        }

                        //Se obtiene sub categoria
                        $sub_category = $this->getSubCategoryData($store_data->company_id, $category->id, $this->cleanString($row[16]), $row[15]);

                        //To BigQuery
                        if($upload_cloud == true && $sub_categories_start == ''){
                            $sub_categories_start = $sub_category->id;
                        }

                        $third_category = $this->getThirdCategoryData($store_data->company_id, $sub_category->id, $this->cleanString($row[18]), $row[17]);

                        //To BigQuery
                        if($upload_cloud == true && $third_categories_start == ''){
                            $third_categories_start = $third_category->id;
                        }

                        //Genera producto y relacion de precio de la maestra
                        $new_product_store = $this->generateNewProduct($row[4], $row[0], $ean13.$ean13_digit, $store_data->company_id, $store_data->id, null, $section->id, $category->id, $sub_category->id, $third_category->id, $catalog_date->format('Y-m-d H:i:s'), $row[2], null, null, null);

                        if($upload_cloud == true && $products_start == ''){
                            $products_start = $new_product_store->product->id;
                        }

                        if($upload_cloud == true && $products_stores_start == ''){
                            $products_stores_start = $new_product_store->id;
                        }

                        $x++;
                    }
                    else{

                        if(count($current_product->products_stores) > 0){
                            foreach($current_product->products_stores as $product_store){

                                $new_product_store = $this->ProductsStores->newEntity();
                                $new_product_store->product_id = $product_store->product_id;
                                $new_product_store->company_id = $store_data->company_id;
                                $new_product_store->aisle_id = $product_store->aisle_id;
                                $new_product_store->store_id = $store_data->id;
                                $new_product_store->section_id = $product_store->section_id;
                                $new_product_store->category_id = $product_store->category_id;
                                $new_product_store->sub_category_id = $product_store->sub_category_id;
                                $new_product_store->third_category_id = $product_store->third_category_id;
                                $new_product_store->strip_price = ($product_store->strip_price != null) ? $product_store->strip_price : $row[2];
                                $new_product_store->company_update = ($product_store->company_update != null) ? $product_store->company_update : null;
                                $new_product_store->company_internal_code = $row[0];
                                $new_product_store->master_catalog_date = $catalog_date->format('Y-m-d H:i:s');
                                $new_product_store->cataloged = null;
                                $new_product_store->enabled = null;
                                $new_product_store->stock_up_to_date = null;

                                if(!$this->ProductsStores->save($new_product_store)){
                                    $this->out(__('Error while saving the product {0} [SKU: {1}] and relationship', [$excel_data[$x][3], $excel_data[$x][1]]));
                                    return false;
                                }
                                else{

                                    $this->out(__('<question>[Int. Code: {0}] Product {1}: Saved on cataloged products on {2}</question>', [$new_product_store->company_internal_code, $product_store->product->product_name, $catalog_date->format('d-m-Y')]));

                                    if($upload_cloud == true && $products_stores_start == ''){
                                        $products_stores_start = $new_product_store->id;
                                    }

                                    $x++;
                                }
                            }
                        }
                        else{

                            //Se obtiene section
                            $section = $this->getSectionData($store_data->company_id, $row[10], $row[9]);

                            //To BigQuery
                            if($upload_cloud == true && $sections_start == ''){
                                $sections_start = $section->id;
                            }
                            
                            //Se obtiene categoria
                            $category = $this->getCategoryData($store_data->company_id, $section->id, $this->cleanString($row[14]), $row[13]);

                            //To BigQuery
                            if($upload_cloud == true && $categories_start == ''){
                                $categories_start = $category->id;
                            }

                            //Se obtiene sub categoria
                            $sub_category = $this->getSubCategoryData($store_data->company_id, $category->id, $this->cleanString($row[16]), $row[15]);

                            //To BigQuery
                            if($upload_cloud == true && $sub_categories_start == ''){
                                $sub_categories_start = $sub_category->id;
                            }

                            $third_category = $this->getThirdCategoryData($store_data->company_id, $sub_category->id, $this->cleanString($row[18]), $row[17]);

                            //To BigQuery
                            if($upload_cloud == true && $third_categories_start == ''){
                                $third_categories_start = $third_category->id;
                            }

                            $new_product_store = $this->ProductsStores->newEntity();
                            $new_product_store->product_id = $current_product->id;
                            $new_product_store->company_id = $store_data->company_id;
                            $new_product_store->store_id = $store_data->id;
                            $new_product_store->section_id = $section->id;
                            $new_product_store->category_id = $category->id;
                            $new_product_store->sub_category_id = $sub_category->id;
                            $new_product_store->third_category_id = $third_category->id;
                            $new_product_store->strip_price = $row[2];
                            $new_product_store->company_update = null;
                            $new_product_store->company_internal_code = $row[0];
                            $new_product_store->master_catalog_date = $catalog_date->format('Y-m-d H:i:s');
                            $new_product_store->cataloged = null;
                            $new_product_store->enabled = null;
                            $new_product_store->stock_up_to_date = null;

                            if(!$this->ProductsStores->save($new_product_store)){
                                $this->out(__('Error while saving the product {0} [SAP: {1}] and relationship', [$excel_data[$x][3], $excel_data[$x][2]]));
                                return false;
                            }
                            else{
                                $x++;
                                $this->out(__('<question>[Int. Code: {0}] Product {1}: Saved on cataloged products on {2}</question>', [$new_product_store->company_internal_code, $current_product->product_name, $catalog_date->format('d-m-Y')]));

                                if($upload_cloud == true && $products_stores_start == ''){
                                    $products_stores_start = $new_product_store->id;
                                }
                            }
                        }
                    }
                }

                fclose($handle);

                if($send_email == true){
                    $email->sendFinishMasterProcessEmail($data, ($x - 1));
                }

                $this->out(__('[Status: Startup at {0}] {1} - [{2}] {3} Cataloged Master for {4}', [date('d-m-Y H:i:s'), $store_data->company->company_name, $store_data->store_code, $store_data->store_name, date('d-m-Y')]));

                if($upload_cloud == true){
                    

                    $product_store = $this->ProductsStores->find('all')->select(['ProductsStores.id'])->order(['ProductsStores.id' => 'DESC'])->first();
                    $products_stores_end = $product_store->id;

                    $product = $this->ProductsStores->Products->find('all')->select(['Products.id'])->order(['Products.id' => 'DESC'])->first();
                    $products_end = $product->id;

                    $section = $this->ProductsStores->Sections->find('all')->select(['Sections.id'])->order(['Sections.id' => 'DESC'])->first();
                    $sections_end = $section->id;

                    $category = $this->ProductsStores->Categories->find('all')->select(['Categories.id'])->order(['Categories.id' => 'DESC'])->first();
                    $categories_end = $category->id;

                    $sub_category = $this->ProductsStores->SubCategories->find('all')->select(['SubCategories.id'])->order(['SubCategories.id' => 'DESC'])->first();
                    $sub_categories_end = $sub_category->id;

                    $third_category = $this->ProductsStores->ThirdCategories->find('all')->select(['ThirdCategories.id'])->order(['ThirdCategories.id' => 'DESC'])->first();
                    $third_categories_end = $third_category->id;

                    $files = [
                        [
                            'table' => 'products_stores',
                            'start' => $products_stores_start,
                            'end' => $products_stores_end
                        ],
                        [
                            'table' => 'products',
                            'start' => $products_start,
                            'end' => $products_end
                        ],
                        [
                            'table' => 'sections',
                            'start' => $sections_start,
                            'end' => $sections_end
                        ],
                        [
                            'table' => 'categories',
                            'start' => $categories_start,
                            'end' => $categories_end
                        ],
                        [
                            'table' => 'sub_categories',
                            'start' => $sub_categories_start,
                            'end' => $sub_categories_end
                        ],
                        [
                            'table' => 'third_categories',
                            'start' => $third_categories_start,
                            'end' => $third_categories_end
                        ]   
                    ];

                    return $files;
                }
                else{
                    return true;
                }
            }
        }
        else{
            $this->out(__('CSV without rows'));
            return false;
        }
    }

    /**
    **
    Busca la seccion por su codigo, de no encontrarla crea una nueva y devuelve el objeto
    **
    **/
    function getSectionData($company_id = null, $section_name = null, $section_code = null, $upload_cloud = false, $file = null){

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
    function getCategoryData($company_id = null, $section_id = null, $category_name = null, $category_code = null, $upload_cloud = false, $file = null){

        $category = $this->ProductsStores->Categories->find('all', ['conditions' => ['Categories.category_code' => $category_code, 'Categories.company_id' => $company_id]])->select(['Categories.id', 'Categories.category_code'])->first();

        if(count($category) == 0){

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
    function getSubCategoryData($company_id = null, $category_id = null, $sub_category_name = null, $sub_category_code = null, $upload_cloud = false, $file = null){

        $sub_category = $this->ProductsStores->SubCategories->find('all', ['conditions' => ['SubCategories.sub_category_code' => $sub_category_code, 'SubCategories.company_id' => $company_id]])->select(['SubCategories.id', 'SubCategories.sub_category_code'])->first();

        if(count($sub_category) == 0){

            $sub_category = $this->SubCategories->newEntity();
            $sub_category->company_id = $company_id;
            $sub_category->category_id = $category_id;
            $sub_category_name = trim($sub_category_name, "\x00..\x1F");
            $sub_category->sub_category_name = ucwords(strtolower($sub_category_name));
            $sub_category->sub_category_code = $sub_category_code;

            if(!$this->SubCategories->save($sub_category)){

                $this->out(__('Error while trying saved the sub category'));
                return false;
            }
        }

        return $sub_category;
    }

    function getThirdCategoryData($company_id = null, $sub_category_id = null, $third_category_name = null, $third_category_code = null, $upload_cloud = false, $file = null){

        $third_category = $this->ProductsStores->ThirdCategories->find('all', ['conditions' => ['ThirdCategories.third_category_code' => $third_category_code, 'ThirdCategories.company_id' => $company_id]])->select(['ThirdCategories.id', 'ThirdCategories.third_category_code'])->first();

        if(count($third_category) == 0){

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

    /**
    **
    Genera un nuevo producto y la asociada a la maestra determinada
    **
    **/
    function generateNewProduct($product_name = null, $internal_code = null, $ean_code = null, $company_id = null, $store_id = null, $aisle_id = null, $section_id = null, $category_id = null, $sub_category_id = null, $third_category_id = null, $catalog_date = null, $price = null, $cataloged = null, $enabled = null, $stock_up_to_date = null){

        //Object producto
        $product = $this->Products->newEntity();

        $product->product_name = utf8_encode(ucwords(strtolower($product_name)));
        $product->product_description = utf8_encode(ucwords(strtolower($product_name)));

        if($ean_code != null){
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
            $new_product_store->aisle_id = $aisle_id;
            $new_product_store->section_id = $section_id;
            $new_product_store->category_id = $category_id;
            $new_product_store->sub_category_id = $sub_category_id;
            $new_product_store->third_category_id = $third_category_id;
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

                $new_product_store->product = $product; 

                return $new_product_store;
            }
        }
        else{
            $this->out(__('<error>Error while saving the product {0}</error>', [$product_name]));
            return false;
        }
    }

    function ean13_checksum ($message) {
        $checksum = 0;
        foreach (str_split(strrev($message)) as $pos => $val) {
            $checksum += $val * (3 - 2 * ($pos % 2));
        }
        return ((10 - ($checksum % 10)) % 10);
    }

    public function doUpdateProcess(){
        $this->out('llego a walmart update');
    }

    public function cleanString($string){
        $string = htmlentities($string);
        $string = preg_replace('/\&(.)[^;]*;/', '\\1', $string);
        return $string;
    }
}