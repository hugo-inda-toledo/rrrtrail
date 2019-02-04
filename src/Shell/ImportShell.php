<?php

namespace App\Shell;

use Cake\Console\Shell;
use Cake\Event\Event;
use Cake\Network\Http\Client;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;
use Cake\Utility\Text;
use Cake\Datasource\ConnectionManager;
use Google\Cloud\BigQuery\BigQueryClient;

class ImportShell extends Shell
{

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Companies');
        $this->loadModel('MeasurementUnits');
        $this->loadModel('Products');
        $this->loadModel('Sections');
        $this->loadModel('Categories');
        $this->loadModel('SubCategories');
    }

    public function master($to_google_cloud = false)
    {
        $http = new Client();

        if($to_google_cloud == true){
            
            //Start BigQuery API
            $projectId = 'cencosud-chile';
            $dataSet = 'public';
        }
        


        $companies = $this->Companies->find('all')->contain(['Stores'])->where(['Companies.active' => 1])->toArray();

        if(count($companies) > 0){
            foreach($companies as $company){
                if(count($company->stores) > 0){
                    foreach($company->stores as $store){
                        switch ($company->company_keyword) {
                            case 'jumbo':
                                $url = 'https://api.cencosud.cl/v1.0/sm/cl/articulos/precios';
                                $response = $http->get($url,['idLocalSap' => $store->store_code],['headers' => ['apiKey' => 'CdRpbGmRfLcq2XbVNwLgHtU9zuLp4w6W'], 'type' => 'json']);

                                if($response->code != 200){
                                    //$this->out(__('Error {0}', [$response->code]));

                                    $this->abort('Error '.$response->code, 128);
                                    continue;
                                }

                                $this->out('------------------------------------------');
                                $this->out('------------------------------------------');
                                $this->out('------------------------------------------');
                                $this->out('------------------------------------------');
                                $this->out(__('{0} {1} [{2}] :', [$company->company_keyword, $store->store_name, $store->store_code]));
                                $this->out(__('Obteniendo productos'));
                                $this->out('------------------------------------------');
                                $this->out('------------------------------------------');
                                $this->out('------------------------------------------');
                                $this->out('------------------------------------------');

                                if(count($response->json) > 0){

                                    $this->loadModel('MeasurementUnits');
                                    $this->loadModel('Products');
                                    $this->loadModel('ProductsStores');
                                    $this->loadModel('Sections');
                                    $this->loadModel('Categories');
                                    $this->loadModel('SubCategories');

                                    $new_products = 0;
                                    $update_products = 0;
                                    $uncategorized_products = 0;
                                    $diference_price_products = 0;

                                    foreach($response->json as $api_product){

                                        // Buscamos el producto por su EAN13
                                        $product = $this->Products->find('all', ['conditions' => ['Products.ean13' => $api_product['ean13'], 'Products.ean13_digit' => $api_product['digitoVerificador']]])->first();

                                        $this->out($api_product['ean13'].'-'.$api_product['digitoVerificador']);
                                        //$product = true;
                                        

                                        $section_code = substr($api_product['jerarquia'], 0, 2);
                                        $category_code = substr($api_product['jerarquia'], 2, 2);
                                        $sub_category_code = substr($api_product['jerarquia'], 4, 2);



                                        $section = $this->Sections->find('all', ['conditions' => ['Sections.section_code' => $section_code]])->first();
                                        $category = $this->Categories->find('all', ['conditions' => ['Categories.category_code' => $section_code.$category_code]])->first();
                                        $sub_category = $this->SubCategories->find('all', ['conditions' => ['SubCategories.sub_category_code' => $section_code.$category_code.$sub_category_code]])->first();

                                        if($section == null || $category == null || $sub_category == null){

                                            $producto = Text::transliterate(ucwords(strtolower($api_product['descripcionCorta'])));
                                            $this->out(__('El producto {0} con EAN13: {1} no tiene categorización', [$producto, $api_product['ean13'].$api_product['digitoVerificador']]));

                                            $uncategorized_products++;
                                            continue;
                                        }

                                        // Si no existe lo debe insertar en la base de datos
                                        if(count($product) == 0){

                                            $unit = $this->MeasurementUnits->find('all', ['conditions' => ['MeasurementUnits.unit_code' => $api_product['unidadMedida']]])->first();

                                            if(count($unit) == 1){
                                                $productsTable = TableRegistry::get('Products');
                                                $product = $productsTable->newEntity();

                                                $product->measurement_unit_id = $unit->id;
                                                $product->product_name = utf8_encode(ucwords(strtolower($api_product['descripcionCorta'])));
                                                $product->product_description = utf8_encode(ucwords(strtolower($api_product['descripcionLarga'])));
                                                $product->stripped = $api_product['imprimeFleje'];
                                                $product->ean13 = $api_product['ean13'];
                                                $product->ean13_digit = $api_product['digitoVerificador'];
                                                $product->bar_type = $api_product['tipoBarra'];
                                                $product->hierarchy = $api_product['jerarquia'];
                                                $product->last_update = $api_product['timeStampActualizacion'];
                                                $product->tax = $api_product['impuesto'];
                                                $product->ppums_amount = $api_product['importePPUMS'];
                                                $product->weighable = $api_product['pesable'];

                                                if ($productsTable->save($product)) {

                                                    $new_products++;
                                                    // The $article entity contains the id now
                                                    $id = $product->id;

                                                    $productsStoresTable = TableRegistry::get('ProductsStores');
                                                    $product_store = $productsStoresTable->newEntity();
                                                    $product_store->product_id = $id;
                                                    $product_store->company_id = $company->id;
                                                    $product_store->store_id = $store->id;
                                                    $product_store->section_id = $section->id;
                                                    $product_store->category_id = $category->id;
                                                    $product_store->sub_category_id = $sub_category->id;
                                                    $product_store->strip_price = $api_product['precioFlejes'];
                                                    $product_store->company_update = $api_product['timeStampActualizacion'];
                                                    $product_store->company_internal_code = $api_product['codigoMaterial'];

                                                    if ($productsStoresTable->save($product_store)) {
                                                        $this->out(__('NEW PRODUCT[{0}  EAN13: {1}]: SAVED', [utf8_encode(ucwords(strtolower($api_product['descripcionCorta']))), $api_product['ean13'].$api_product['digitoVerificador']]));
                                                        $update_products++;

                                                        if($to_google_cloud == true){
                                                            
                                                            $data_product = json_decode(json_encode($product), true);
                                                            $data_product_store = json_decode(json_encode($product_store), true);

                                                            
                                                            if($this->stream_row($projectId, $dataSet, 'products', $data_product, $data_product['id']) == true && $this->stream_row($projectId, $dataSet, 'products_stores', $data_product_store, $data_product_store['id']) == true){
                                                                $this->out(__('Product # {0} and relationship # {1} uploaded to google cloud', [$product->id, $product_store->id]));
                                                            }
                                                        }
                                                    }
                                                    else{
                                                        $this->out('Error al guardar relacion del producto');
                                                    }
                                                }
                                                else{
                                                    $this->out('Error al guardar producto');
                                                }
                                            }
                                            else{
                                                $this->out(__('No existe la unidad de medida {0} en la base de datos, agregala', $api_product['unidadMedida']));
                                            }
                                        }
                                        else{
                                            
                                            $this->out(__('Producto existente: {0}', $product->product_name));

                                            if($to_google_cloud == true){
                                                            
                                                $data_product = json_decode(json_encode($product), true);
                                                
                                                if($this->stream_row($projectId, $dataSet, 'products', $data_product, $data_product['id']) == true){
                                                    $this->out(__('Product # {0} uploaded to google cloud', [$product->id]));
                                                }
                                            }

                                            $last_product_store = $this->ProductsStores->find('all')
                                                ->where([
                                                    'ProductsStores.product_id' => $product->id,
                                                    'ProductsStores.company_id' => $company->id,
                                                    'ProductsStores.store_id' => $store->id,
                                                    'ProductsStores.section_id' => $section->id,
                                                    'ProductsStores.category_id' => $category->id,
                                                    'ProductsStores.sub_category_id' => $sub_category->id,
                                                    'ProductsStores.company_update' => $api_product['timeStampActualizacion']
                                                ])
                                                ->order([
                                                    'ProductsStores.created' => 'DESC'
                                                ])
                                                ->first();

                                            if(count($last_product_store) == 0){
                                                $productsStoresTable = TableRegistry::get('ProductsStores');
                                                $product_store = $productsStoresTable->newEntity();
                                                $product_store->product_id = $product->id;
                                                $product_store->company_id = $company->id;
                                                $product_store->store_id = $store->id;
                                                $product_store->section_id = $section->id;
                                                $product_store->category_id = $category->id;
                                                $product_store->sub_category_id = $sub_category->id;
                                                $product_store->strip_price = $api_product['precioFlejes'];
                                                $product_store->company_update = $api_product['timeStampActualizacion'];
                                                $product_store->company_internal_code = $api_product['codigoMaterial'];

                                                if ($productsStoresTable->save($product_store)) {
                                                    $this->out(__('Producto {0} actualizado por {1} el {2}', [$product->product_name, $company->company_keyword, $api_product['timeStampActualizacion']]));

                                                    if($to_google_cloud == true){
                                                            
                                                        $data_product_store = json_decode(json_encode($product_store), true);

                                                        
                                                        if($this->stream_row($projectId, $dataSet, 'products_stores', $data_product_store, $data_product_store['id']) == true){
                                                            $this->out(__('Relationship # {1} uploaded to google cloud', [$product_store->id]));
                                                        }
                                                    }

                                                    $update_products++;

                                                    $last_product_store = $this->ProductsStores->find('all')
                                                        ->where([
                                                            'ProductsStores.product_id' => $product->id,
                                                            'ProductsStores.company_id' => $company->id,
                                                            'ProductsStores.store_id' => $store->id,
                                                            'ProductsStores.section_id' => $section->id,
                                                            'ProductsStores.category_id' => $category->id,
                                                            'ProductsStores.sub_category_id' => $sub_category->id,
                                                            'ProductsStores.id <>' => $product_store->id
                                                        ])
                                                        ->order([
                                                            'ProductsStores.created' => 'DESC'
                                                        ])
                                                        ->first();

                                                    if(count($last_product_store) == 1){

                                                        //Se compara precio y fecha
                                                        if($product_store->strip_price != $last_product_store->strip_price){
                                                            $this->out(__('Hay diferencia de precio'));

                                                            $diference_price_products++;
                                                        }
                                                    }
                                                    else{
                                                        $this->out(__('No hay registros del día anteriores del producto con ID: {0}', $product->id));
                                                    }
                                                }
                                                else{
                                                    $this->out(__('Error al guardar el registro del dia del producto {0}', [$product->product_name]));
                                                }
                                            }
                                            else{
                                                $this->out(__('El producto {0} ya tiene un registro del dia', [$product->product_name]));

                                                $second_last_product_store = $this->ProductsStores->find('all')
                                                    ->where([
                                                        'ProductsStores.product_id' => $product->id,
                                                        'ProductsStores.company_id' => $company->id,
                                                        'ProductsStores.store_id' => $store->id,
                                                        'ProductsStores.section_id' => $section->id,
                                                        'ProductsStores.category_id' => $category->id,
                                                        'ProductsStores.sub_category_id' => $sub_category->id,
                                                        'ProductsStores.id <>' => $last_product_store->id
                                                    ])
                                                    ->order([
                                                        'ProductsStores.created' => 'DESC'
                                                    ])
                                                    ->first();

                                                if(count($second_last_product_store) == 1){

                                                    //Se compara precio
                                                    if($second_last_product_store->strip_price != $last_product_store->strip_price){
                                                        $this->out(__('Hay diferencia de precio'));
                                                        $diference_price_products++;
                                                    }
                                                }
                                                else{
                                                    $this->out(__('No hay registros del día anteriores del producto con ID: {0}', $product->id));
                                                }
                                            }
                                        }
                                    }

                                    $this->out(__('{0} productos nuevos agregados', $new_products));
                                    $this->out(__('{0} productos existentes actualizados', $update_products));
                                    $this->out(__('{0} productos no categorizados', $uncategorized_products));
                                    $this->out(__('{0} productos con diferencia de precio', $diference_price_products));
                                }
                                else{
                                    $this->out(__('No hay productos en el maestro de {0} {1} [{2}]', [$company->company_keyword, $store->store_name, $store->store_code]));
                                }

                                $this->out('------------------------------------------');
                                $this->out('------------------------------------------');
                                $this->out('------------------------------------------');
                                $this->out('------------------------------------------');
                                $this->out(__('Fin del proceso para {0} {1} [{2}] :', [$company->company_keyword, $store->store_name, $store->store_code]));
                                $this->out('------------------------------------------');
                                $this->out('------------------------------------------');
                                $this->out('------------------------------------------');
                                $this->out('------------------------------------------');

                                break;
                            
                            default:
                                # code...
                                break;
                        }
                    }
                }
            }
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
}