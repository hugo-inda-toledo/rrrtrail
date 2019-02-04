<?php

namespace App\Controller\Ajax;

use stdClass;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\Network\Exception\NotFoundException;
use Cake\I18n\I18n;
use Cake\Routing\Router;

use App\Controller\AppController;

class CategoriesController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Categories');
        $this->Auth->allow(['getCategoriesList']);
    }

    function getCategoriesList(){
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

            $categories = $this->Categories->find('list', ['conditions' => ['Categories.section_id' => $this->request->data('id'), 'Categories.category_name <>' => ''], 'order' => ['Categories.category_name' => 'ASC']])->toArray();
            

            if(count($categories) > 0){
                $categories_list = [];

                foreach ($categories as $id => $name) {
                    $categories_list[$id] = __($name);
                }

                

                $response->error = __("Get {0} sections", count($categories_list));

                $response->status = true;
                $response->data = [
                    'categories' => $categories_list
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