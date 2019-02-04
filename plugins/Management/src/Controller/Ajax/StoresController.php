<?php
namespace Management\Controller\Ajax;

use stdClass;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\Network\Exception\NotFoundException;
use Cake\I18n\I18n;
use Cake\Routing\Router;

use Management\Controller\AppController;

class StoresController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Stores');
        $this->Auth->allow(['getStoresList']);
    }

    function getStoresList(){
        if($this->request->is('post')){
            $response = new \stdClass();
            $response->status = false;
            $response->error = '';
            $response->message = '';
            $response->data = [];

            if($this->request->data('id') == null){
                $response->error = __('Invalid Params.');

                $this->response->type('json');
                $this->response->body(json_encode($response));

                return $this->response;
            } 

            $stores = $this->Stores->find('list', ['conditions' => ['Stores.company_id' => $this->request->data('id'),
                    'Stores.active' => 1], 'order' => ['Stores.store_name' => 'ASC']])->toArray();

            if(count($stores) > 0){
                

                $response->error = __("Get {0} stores", count($stores));

                $response->status = true;
                $response->data = [
                    'stores' => $stores
                ];

                $this->response->type('json');
                $this->response->body(json_encode($response));

                return $this->response;
            }
            else{
                $response->error = __("No exist stores");

                $this->response->type('json');
                $this->response->body(json_encode($response));

                return $this->response;
            }

        }
    }
}