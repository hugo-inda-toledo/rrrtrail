<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Network\Http\Client;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;

class ImportsController extends AppController
{
  public function beforeFilter(Event $event)
  {
      parent::beforeFilter($event);

      $this->loadModel('Companies');
  }

  function seeApi($store_code = null){
    $http = new Client();
    
    $response = $http->get('https://api.cencosud.cl/v1.0/sm/cl/articulos/precios',['idLocalSap' => $store_code],['headers' => ['apiKey' => 'CdRpbGmRfLcq2XbVNwLgHtU9zuLp4w6W'], 'type' => 'json']);

    echo '<pre>';
    print_r($response);
    echo '</pre>';
  }


	public function processMasterProductsData(){

    $this->set('companies_list', $this->Companies->find('list'));

    if($this->request->is('post')){

      if(!$this->request->data['Companies']['id'] || !$this->request->data['Stores']['id']){
          $this->Flash->success(__('Invalid Params.'));
          return $this->redirect(['controller' => 'Companies', 'action' => 'processMasterProductsData']);
      }

      $company = $this->Companies->get($this->request->data['Companies']['id']);
      $store = $this->Companies->Stores->get($this->request->data['Stores']['id']);

      if($company == null){
          $this->Flash->success(__('No exist company'));
          return $this->redirect(['controller' => 'Companies', 'action' => 'processMasterProductsData']);
      }

      if($store == null){
          $this->Flash->success(__('No exist store'));
          return $this->redirect(['controller' => 'Companies', 'action' => 'processMasterProductsData']);
      }

      $http = new Client();

      switch ($company->company_keyword) {
        case 'jumbo':
          $response = $http->get('https://api.cencosud.cl/v1.0/sm/cl/articulos/precios',['idLocalSap' => $store->store_code],['headers' => ['apiKey' => 'CdRpbGmRfLcq2XbVNwLgHtU9zuLp4w6W'], 'type' => 'json']);

          if(count($response->json) > 0){
            

            $this->loadModel('MeasurementUnits');
            $this->loadModel('Products');
            $this->loadModel('Sections');
            $this->loadModel('Categories');
            $this->loadModel('SubCategories');
            
            

            foreach($response->json as $api_product){

              // Buscamos el producto por su EAN13
              $product = $this->Products->find('all', ['conditions' => ['Products.ean13' => $api_product['ean13'], 'Products.ean13_digit' => $api_product['digitoVerificador']]])->first();
              
              echo $api_product['ean13'].'-'.$api_product['digitoVerificador'].'<br>';
              //$product = true;
              // Si no existe lo debe insertar en la base de datos
              if(count($product) == 0){
                
                $unit = $this->MeasurementUnits->find('all', ['conditions' => ['MeasurementUnits.unit_code' => $api_product['unidadMedida']]])->first();

                $section_code = substr($api_product['jerarquia'], 0, 2);
                $category_code = substr($api_product['jerarquia'], 2, 2);
                $sub_category_code = substr($api_product['jerarquia'], 4, 2);

                /*echo 'jerarquia: '.$api_product['jerarquia'].'<br>';
                echo 'section_code: '.$section_code.'<br>';
                echo 'category_code: '.$category_code.'<br>';
                echo 'sub_category_code: '.$sub_category_code.'<br>';*/

                $section = $this->Sections->find('all', ['conditions' => ['Sections.section_code' => $section_code]])->first();
                $category = $this->Categories->find('all', ['conditions' => ['Categories.category_code' => $section_code.$category_code]])->first();
                $sub_category = $this->SubCategories->find('all', ['conditions' => ['SubCategories.sub_category_code' => $section_code.$category_code.$sub_category_code]])->first();

                if($section != null && $category != null && $sub_category != null){
                  $productsTable = TableRegistry::get('Products');
                  $product = $productsTable->newEntity();

                  $product->measurement_unit_id = $unit->id;
                  $product->product_name = ucwords(strtolower($api_product['descripcionCorta']));
                  $product->product_description = ucwords(strtolower($api_product['descripcionLarga']));
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
                      echo 'Increible! producto con relacion guardado<br>';
                    }
                    else{
                      echo 'Error al guardar relacion del producto<br>';
                    }
                  }
                  else{
                    echo 'Error al guardar producto<br>';
                  }
                }
                else{
                  echo 'no hay secciones, categorias o sub<br>';
                }


                
                /*echo 'jerarquia: '.$api_product['jerarquia'].'<br>';
                echo 'section_code: '.$section_code.'<br>';
                echo 'jerarquia: '.$category_code.'<br>';
                echo 'jerarquia: '.$sub_category_code.'<br>';

                echo $api_product['timeStampActualizacion'].'<br>';
                $time = Time::createFromTimestamp($api_product['timeStampActualizacion']);
                print_r($time);



                die();*/
                /*echo '<pre>';
                print_r($section);
                echo '</pre>';

                echo '<pre>';
                print_r($category);
                echo '</pre>';

                echo '<pre>';
                print_r($sub_category);
                echo '</pre>';

                echo '<pre>';
                print_r($unit);
                echo '</pre>';*/
                

                //die();
              }
              else{
                echo 'Producto existente<br>';
              }

            }

          }

          break;
        
        default:
          # code...
          break;
      }

      die();
    }
  }
}