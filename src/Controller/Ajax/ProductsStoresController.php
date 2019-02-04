<?php

namespace App\Controller\Ajax;

use stdClass;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\Network\Exception\NotFoundException;
use Cake\I18n\I18n;
use Cake\Routing\Router;
use Cake\Cache\Cache;

use App\Controller\AppController;

class ProductsStoresController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadModel('ProductsStores');
        $this->loadModel('ProductStates');
        $this->Auth->allow(['setStatus']);
    }

    function setStatus(){
        if($this->request->is('post')){
            $response = new \stdClass();
            $response->status = false;
            $response->error = '';
            $response->message = '';
            $response->data = [];

            if($this->request->data('product_store_id') == null || $this->request->data('state_keyword') == null ){
                $response->error = __('Invalid Params.');

                $this->response->type('json');
                $this->response->body(json_encode($response));

                return $this->response;
            } 

            $product_store = $this->ProductsStores->find('all', ['contain' => ['ProductStates'], 'conditions' => ['ProductsStores.id' => $this->request->data('product_store_id')]])->first();

            if(count($product_store) > 0){
                
                if($product_store->product_state_id == null){
                    $product_state = $this->ProductStates->find('all', ['conditions' => ['ProductStates.state_keyword' => $this->request->data('state_keyword')]])->first();

                    if($product_state != null){
                        
                        $product_store->product_state_id = $product_state->id;

                        if($this->ProductsStores->save($product_store)){


                            /*if (($report_view = Cache::read('element_stock_out_report_view_'.$product_store->company_id.$product_store->store_id.$this->request->data('session_id'), 'config_cache_report')) === true) {

                            }*/

                            $product_state->state_name = __($product_state->state_name);

                            $response->error = __("Get {0} product_state", count($product_state));

                            $response->status = true;
                            $response->data = [
                                'product_state' => $product_state
                            ];

                            $this->response->type('json');
                            $this->response->body(json_encode($response));
                            return $this->response;
                        }
                        else{
                            $response->error = __("Error save product_store");

                            $this->response->type('json');
                            $this->response->body(json_encode($response));

                            return $this->response;
                        }
                    }
                    else{
                        $response->error = __("No exist state");

                        $this->response->type('json');
                        $this->response->body(json_encode($response));

                        return $this->response;
                    }
                }
                else{
                    $response->status = true;
                    $response->error = __("Status really assigned");
                    $response->data = [
                        'product_state' => $product_store->product_state
                    ];

                    $this->response->type('json');
                    $this->response->body(json_encode($response));
                    return $this->response;
                }
                
            }
            else{
                $response->error = __("No exist product_store");

                $this->response->type('json');
                $this->response->body(json_encode($response));

                return $this->response;
            }

        }
    }
}