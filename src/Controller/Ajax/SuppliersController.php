<?php

namespace App\Controller\Ajax;

use stdClass;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\Network\Exception\NotFoundException;
use Cake\I18n\I18n;
use Cake\Routing\Router;

use App\Controller\AppController;

class SuppliersController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadModel('SuppliersCompanies');
        $this->loadModel('Suppliers');
        $this->Auth->allow(['getSuppliersList']);
    }

    function getSuppliersList(){
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

            $suppliers_companies = $this->SuppliersCompanies->find('all', ['contain' => 'Suppliers', 'conditions' => ['SuppliersCompanies.company_id' => $this->request->data('id')]])->toArray();

            if(count($suppliers_companies) > 0){
                
                $suppliers = array();

                foreach($suppliers_companies as $supplier_company){
                    $suppliers[$supplier_company->supplier->id] = $supplier_company->supplier->supplier_name;
                }

                $response->error = __("Get {0} suppliers", count($suppliers));

                $response->status = true;
                $response->data = [
                    'suppliers' => $suppliers
                ];

                $this->response->type('json');
                $this->response->body(json_encode($response));

                return $this->response;
            }
            else{
                $response->error = __("No exist suppliers");

                $this->response->type('json');
                $this->response->body(json_encode($response));

                return $this->response;
            }

        }
    }
}