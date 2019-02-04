<?php
namespace Management\Controller\Ajax;

use stdClass;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\Network\Exception\NotFoundException;
use Cake\I18n\I18n;
use Cake\Routing\Router;

use Management\Controller\AppController;

class CommunesController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Communes');
        $this->Auth->allow(['getCommunesList']);
    }

    function getCommunesList(){
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

            $communes = $this->Communes->find('list', ['conditions' => ['Communes.region_id' => $this->request->data('id')]])->order(['Communes.commune_name' => 'ASC'])->toArray();

            if(count($communes) > 0){
                

                $response->error = __("Get {0} communes", count($communes));

                $response->status = true;
                $response->data = [
                    'communes' => $communes
                ];

                $this->response->type('json');
                $this->response->body(json_encode($response));

                return $this->response;
            }
            else{
                $response->error = __("No exist communes");

                $this->response->type('json');
                $this->response->body(json_encode($response));

                return $this->response;
            }

        }
    }
}