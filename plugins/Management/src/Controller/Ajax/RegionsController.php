<?php
namespace Management\Controller\Ajax;

use stdClass;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\Network\Exception\NotFoundException;
use Cake\I18n\I18n;
use Cake\Routing\Router;

use Management\Controller\AppController;

class RegionsController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Regions');
        $this->Auth->allow(['getRegionsList']);
    }

    function getRegionsList(){
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

            $regions = $this->Regions->find('list', ['conditions' => ['Regions.country_id' => $this->request->data('id')]])->toArray();

            if(count($regions) > 0){
                

                $response->error = __("Get {0} regions", count($regions));

                $response->status = true;
                $response->data = [
                    'regions' => $regions
                ];

                $this->response->type('json');
                $this->response->body(json_encode($response));

                return $this->response;
            }
            else{
                $response->error = __("No exist regions");

                $this->response->type('json');
                $this->response->body(json_encode($response));

                return $this->response;
            }

        }
    }
}