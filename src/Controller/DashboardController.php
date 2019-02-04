<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Network\Http\Client;
use Cake\I18n\Time;

class DashboardController extends AppController
{
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        $this->loadModel('RobotReports');
    }

    public function index(){

        $this->viewBuilder()->setLayout('new_default');
    	/*$auth_data = array(
            'username' => 'zippedi',
            'password' => 'YuL1fcfQQIwFL6Es2Zx'
        );

        $http = new Client();
        $response = $http->post('https://reports.zippedi.cl/auth', json_encode($auth_data), ['type' => 'json']);

        $options = array('http' => array(
            'method'  => 'GET',
            'header' => 'Authorization: jwt '.$response->json['access_token']
        ));

        $url = 'https://reports.zippedi.cl/jumbo/active_products?supermarket=J512&date=03-04-2018&department=7';

        $context  = stream_context_create($options);
        $webservice= json_decode(file_get_contents($url, false, $context));
        

        echo '<pre>';
        print_r($webservice); 
        echo '</pre>';*/

        //die();
        //$this->set('json_array', json_encode($webservice));

        $stores_list = $this->getAllUserStores();

        foreach($stores_list as $company_name => $data){

        }

        $this->set('stores_list', $stores_list);
        	//$robot_reports = $this->RobotReports->find('all')->where(['RobotReports.active' => 1])->toArray();
        	//$this->set('robot_reports', $robot_reports);
        //}
    }

    function getAllUserStores($type = null){
        $this->loadModel('UsersSuppliers');
        $this->loadModel('UsersCompanies');

        $current_date = New Time();
        $current_date->format('-7 days');

        $stores_data = array();

        $users_suppliers_data = $this->UsersSuppliers->find('all')
            ->contain([
                'Stores' => [
                    'RobotSessions' => [
                        /*'fields' => [
                            'RobotSessions.id', 
                            'RobotSessions.id', 
                            'RobotSessions.id', 
                            'RobotSessions.id', 
                            'RobotSessions.id', 
                            'RobotSessions.id', 
                        ],*/
                        'conditions' => [
                            'RobotSessions.total_detections >' => 0,
                            'RobotSessions.finished' => 1,                            
                            'RobotSessions.processing' => 0,
                            'RobotSessions.is_test' => 0,
                            'DATE(RobotSessions.session_date) >=' => $current_date->format('Y-m-d')
                        ],
                        'sort' => ['RobotSessions.session_date' => 'DESC']
                    ], 
                    'Locations' => [
                        'Countries', 'Regions', 'Communes'
                    ]
                ], 
                'Suppliers', 
                'Companies', 
                'Sections'
            ])
            ->where(['UsersSuppliers.user_id' => $this->request->session()->read('Auth.User.id')])
            ->toArray();

        $users_companies_data = $this->UsersCompanies->find('all')
            ->contain([
                'Stores' => [
                    'RobotSessions' => [
                        'conditions' => [
                            'RobotSessions.total_detections >' => 0,
                            'RobotSessions.finished' => 1,                            
                            'RobotSessions.processing' => 0, 
                            'RobotSessions.is_test' => 0,
                            'DATE(RobotSessions.session_date) >=' => $current_date->format('Y-m-d')
                        ],
                        'sort' => ['RobotSessions.session_date' => 'DESC'],
                        'Detections' => function($q) {
                            $q->select(['Detections.id', 'Detections.robot_session_id'])->group(['Detections.product_store_id']);

                            return $q;
                        }
                    ], 
                    'Locations' => [
                        'Countries', 
                        'Regions', 
                        'Communes'
                    ]
                ], 
                'Companies', 
                'Sections'
            ])
            ->where([
                'UsersCompanies.user_id' => $this->request->session()->read('Auth.User.id')
            ])
            ->toArray();

        if(count($users_suppliers_data) > 0){
            foreach($users_suppliers_data as $user_supplier_data){

                switch ($type) {
                    case 'list':
                        $stores_data[$user_supplier_data->store->id] = $user_supplier_data->company->company_name.' '.$user_supplier_data->store->store_name;
                        $stores_data[$user_supplier_data->store->id] = $user_supplier_data->company->company_name.' '.$user_supplier_data->store->store_name;
                        break;

                    case 'map':
                        $stores_data[$user_supplier_data->company->company_name.' '.$user_supplier_data->store->store_name] = $user_supplier_data->store;
                        $stores_data[$user_supplier_data->company->company_name.' '.$user_supplier_data->store->store_name]->company = $user_supplier_data->company;
                        break;

                    case 'array_list_id':
                        $stores_data[$user_supplier_data->store->id] = $user_supplier_data->company->company_name.' '.$user_supplier_data->store->store_name;
                        break;

                    case 'array_list_code':
                        $stores_data[$user_supplier_data->store->store_code] = $user_supplier_data->company->company_name.' '.$user_supplier_data->store->store_name;
                        break;

                    
                    default:
                        $stores_data[$user_supplier_data->company->company_name]['stores'][$user_supplier_data->store->id] = $user_supplier_data->store;

                        if(!isset($stores_data[$user_supplier_data->company->company_name]['company']))
                            $stores_data[$user_supplier_data->company->company_name][$user_supplier_data->store->id]['company'] = $user_supplier_data->company;
                        break;
                }
                
            }
        }
        
        if(count($users_companies_data) > 0){
            foreach($users_companies_data as $user_company_data){
                switch ($type) {
                    case 'list':
                        $stores_data[$user_company_data->store->id] = $user_company_data->company->company_name.' '.$user_company_data->store->store_name;
                        $stores_data[$user_company_data->store->id] = $user_company_data->company->company_name.' '.$user_company_data->store->store_name;
                        break;

                    case 'map':
                        $stores_data[$user_company_data->company->company_name.' '.$user_company_data->store->store_name] = $user_company_data->store;
                        $stores_data[$user_company_data->company->company_name.' '.$user_company_data->store->store_name]->company = $user_company_data->company;
                        break;

                    case 'array_list_id':
                        $stores_data[$user_company_data->store->id] = $user_company_data->company->company_name.' '.$user_company_data->store->store_name;
                        break;

                    case 'array_list_code':
                        $stores_data[$user_company_data->store->store_code] = $user_company_data->company->company_name.' '.$user_company_data->store->store_name;
                        break;

                    default:
                        $stores_data[$user_company_data->company->company_name]['stores'][$user_company_data->store->id] = $user_company_data->store;

                        if(!isset($stores_data[$user_company_data->company->company_name]['company']))
                            $stores_data[$user_company_data->company->company_name]['company'] = $user_company_data->company;
                        break;
                }
            }
        }
        
        return $stores_data;   
    }
}