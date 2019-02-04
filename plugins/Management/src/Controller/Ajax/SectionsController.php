<?php
namespace Management\Controller\Ajax;

use stdClass;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\Network\Exception\NotFoundException;
use Cake\I18n\I18n;
use Cake\Routing\Router;

use Management\Controller\AppController;

class SectionsController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Sections');
        $this->Auth->allow(['getSectionsList']);
    }

    function getSectionsList(){
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

            $sections = $this->Sections->find('list', ['conditions' => ['Sections.company_id' => $this->request->data('id')], 'order' => ['Sections.section_name' => 'ASC']])->toArray();

            if(count($sections) > 0){
                

                $response->error = __("Get {0} sections", count($sections));

                $response->status = true;
                $response->data = [
                    'sections' => $sections
                ];

                $this->response->type('json');
                $this->response->body(json_encode($response));

                return $this->response;
            }
            else{
                $response->error = __("No exist sections");

                $this->response->type('json');
                $this->response->body(json_encode($response));

                return $this->response;
            }

        }
    }
}