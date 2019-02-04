<?php

namespace App\Controller\Ajax;

use stdClass;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\Network\Exception\NotFoundException;
use Cake\I18n\I18n;
use Cake\Routing\Router;

use App\Controller\AppController;

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

            //$user_sections_ids  = $this->Sections->getAuthIds($this->request->session()->read('Auth.User.id'));

            $sections = $this->Sections->find('list', ['conditions' => ['Sections.company_id' => $this->request->data('id'), 'Sections.section_name <>' => '', 'Sections.enabled' => 1], 'order' => ['Sections.section_name' => 'ASC']])->toArray();
            

            if(count($sections) > 0){
                $sections_list['all'] = __('All');

                foreach ($sections as $id => $name) {
                    $sections_list[$id] = __($name);
                }

                

                $response->error = __("Get {0} sections", count($sections_list));

                $response->status = true;
                $response->data = [
                    'sections' => $sections_list
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