<?php

namespace Management\Controller\Ajax;

use stdClass;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\Network\Exception\NotFoundException;
use Cake\I18n\I18n;
use Cake\Routing\Router;

use Management\Controller\AppController;

class UsersSuppliersController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadModel('UsersSuppliers');
        $this->Auth->allow(['deleteRecord']);
    }

    function deleteRecord(){
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

            $user_supplier = $this->UsersSuppliers->get($this->request->data('id'));

            if(count($user_supplier) == 1){
                
                if($this->UsersSuppliers->delete($user_supplier)){
                    $response->error = __("Record deleted");

                    $response->status = true;
                    $response->data = [
                        'id' => $this->request->data('id')
                    ];
                }
                else{
                    $response->error = __("Record couldn't be deleted");
                }   
            }
            else{
                $response->error = __("No exist record");
            }
            $this->response->type('json');
            $this->response->body(json_encode($response));

            return $this->response;
        }
    }
}