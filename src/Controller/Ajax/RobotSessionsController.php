<?php

namespace App\Controller\Ajax;

use stdClass;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\Network\Exception\NotFoundException;
use Cake\I18n\I18n;
use Cake\Routing\Router;
use Cake\I18n\Time;
use Cake\Network\Http\Client;
use App\Controller\RobotReportsController as RobotReportsMain;
use Cake\Cache\Cache;
use Picqer\Barcode\BarcodeGeneratorPNG;
use App\View\Helper\EanHelper;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use App\Controller\AppController;

class RobotSessionsController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadModel('RobotSessions');

        $this->loadComponent('RequestHandler');
    }

    var $endpoint = 'https://reports2.zippedi.cl';
    //var $endpoint = 'https://reports.zippedi.cl';

    /**
    // Get the sessions list that Zippedi has made
    **/
    public function getSessionLoadData($robot_session_id = null){

        if($robot_session_id != null){

            return $this->RobotSessions->Detections->find('all')->where(['Detections.robot_session_id' => $robot_session_id])->count();   
        }
        else{
            return false;
        }
    }

    function getSessionsList($store_id = null, $type = null, $list = false, $ajax = false){
        if($store_id != null){

            switch ($type) {
                case 'price_differences':
                    $robot_sessions = $this->RobotSessions->find('all')
                        ->where([
                            'RobotSessions.store_id' => $store_id, 
                            'RobotSessions.price_differences_labels_finished' => 1, 
                            'RobotSessions.price_differences_labels_processing' => 0
                        ])
                        ->toArray();

                    break;

                case 'facing':
                    $robot_sessions = $this->RobotSessions->find('all')
                        ->where([
                            'RobotSessions.store_id' => $store_id, 
                            'RobotSessions.facing_labels_finished' => 1, 
                            'RobotSessions.facing_labels_processing' => 0
                        ])
                        ->toArray();

                    break;

                case 'all_labels':
                    $robot_sessions = $this->RobotSessions->find('all')
                        ->where([
                            'RobotSessions.store_id' => $store_id, 
                            'RobotSessions.labels_finished' => 1, 
                            'RobotSessions.labels_processing' => 0
                        ])
                        ->toArray();

                    break;

                case 'assortment':
                    $robot_sessions = $this->RobotSessions->find('all')
                        ->where([
                            'RobotSessions.store_id' => $store_id, 
                            'RobotSessions.assortment_finished' => 1, 
                            'RobotSessions.assortment_processing' => 0
                        ])
                        ->toArray();

                    break;
                
                default:
                    $robot_sessions = $this->RobotSessions->find('all')
                        ->where([
                            'RobotSessions.store_id' => $store_id, 
                            'RobotSessions.labels_finished' => 1, 
                            'RobotSessions.labels_processing' => 0
                        ])
                        ->toArray();

                    break;
            }
            
            $store_data = $this->RobotSessions->Stores->get($store_id);

            if(count($robot_sessions) > 0){

                $sessions = [];

                if($list == true){
                    $sessions_list = [];

                    if($store_data->store_code == 'HC67' || $store_data->store_code == 'L739'){
                        foreach ($robot_sessions as $session) {
                            if($session->is_test == '' && $session->includes_qa == 1){

                                if($type == 'assortment'){
                                    $sessions_list[] = $session->calendar_date->format('Y-m-d');
                                }
                                else{
                                    $sessions_list[] = $session->session_date->format('Y-m-d');
                                }
                                
                            }
                            
                        }
                    }
                    else{
                        foreach ($robot_sessions as $session) {
                            if($session->is_test == '' && $session->includes_qa == 1){

                                if($type == 'assortment'){
                                    $sessions_list[] = $session->calendar_date->format('Y-m-d');
                                }
                                else{
                                    $sessions_list[] = $session->session_date->format('Y-m-d');
                                }
                                
                            }
                            
                        } 
                    }
                    

                    $sessions_list = array_unique($sessions_list);
                    ksort($sessions_list); 
                    $sessions_list = array_reverse($sessions_list);

                    $sessions = $sessions_list;
                }
                

                if($ajax == true){
                    $response = new \stdClass();
                    $response->status = true;
                    $response->error = '';
                    $response->message = __('Get {0} records', count($sessions));
                    $response->data = [
                        'sessions' => $sessions
                    ];

                    $this->response->type('json');
                    $this->response->body(json_encode($response));

                    return $this->response;
                }
                else{
                    $sessions = $robot_sessions;
                    return $sessions;
                }
            }
            else{
                $response = new \stdClass();
                $response->status = false;
                $response->error = __('Not exist sessions');
                $response->message = '';
                $response->data = [];

                $this->response->type('json');
                $this->response->body(json_encode($response));

                return $this->response;
            }

        }
    }

    function getSessionsByDate($store_id  = null, $type = null, $date_string = null){

        $response = new \stdClass();
        $response->status = false;
        
        $response->message = '';
        $response->data = [];
        

        if($store_id != null && $date_string != null){


            switch ($type) {
                case 'price_differences':

                    $robot_sessions = $this->RobotSessions->find('all')
                        ->select([
                            'RobotSessions.id', 
                            'RobotSessions.session_date'
                        ])
                        ->where([
                            'RobotSessions.store_id' => $store_id, 
                            'DATE(RobotSessions.session_date)' => $date_string, 
                            'RobotSessions.is_test' => 0, 
                            'RobotSessions.price_differences_labels_finished' => 1, 
                            'RobotSessions.price_differences_labels_processing' => 0
                        ])
                        ->order([
                            'RobotSessions.session_date' => 'DESC'
                        ])
                        ->toArray();

                    break;

                case 'facing':
                    $robot_sessions = $this->RobotSessions->find('all')
                        ->select([
                            'RobotSessions.id', 
                            'RobotSessions.session_date'
                        ])
                        ->where([
                            'RobotSessions.store_id' => $store_id, 
                            'DATE(RobotSessions.session_date)' => $date_string, 
                            'RobotSessions.is_test' => 0, 
                            'RobotSessions.facing_labels_processing' => 0, 
                            'RobotSessions.facing_labels_finished' => 1, 
                        ])
                        ->order([
                            'RobotSessions.session_date' => 'DESC'
                        ])
                        ->toArray();

                    break;

                case 'all_labels':
                    $robot_sessions = $this->RobotSessions->find('all')
                        ->select([
                            'RobotSessions.id', 
                            'RobotSessions.session_date'
                        ])
                        ->where([
                            'RobotSessions.store_id' => $store_id, 
                            'DATE(RobotSessions.session_date)' => $date_string, 
                            'RobotSessions.is_test' => 0, 
                            'RobotSessions.labels_processing' => 0, 
                            'RobotSessions.labels_finished' => 1, 
                            'RobotSessions.total_detections >' => 0
                        ])
                        ->order([
                            'RobotSessions.session_date' => 'DESC'
                        ])
                        ->toArray();

                    break;
                
                default:
                    $robot_sessions = $this->RobotSessions->find('all')
                        ->select([
                            'RobotSessions.id', 
                            'RobotSessions.session_date'
                        ])
                        ->where([
                            'RobotSessions.store_id' => $store_id, 
                            'DATE(RobotSessions.session_date)' => $date_string, 
                            'RobotSessions.is_test' => 0, 
                            'RobotSessions.labels_processing' => 0, 
                            'RobotSessions.labels_finished' => 1, 
                            'RobotSessions.total_detections >' => 0
                        ])
                        ->order([
                            'RobotSessions.session_date' => 'DESC'
                        ])
                        ->toArray();

                    break;
            }

            if(count($robot_sessions) > 0){

                $sessions = [];
                foreach($robot_sessions as $robot_session){
                    $sessions[$robot_session->id] = __('{0}', [$robot_session->session_date->format('d-m-Y H:i:s')]);
                }

                $response->data = [
                    'sessions' => $sessions
                ];

                $response->status = true;

                $response->error = __('{0} valid sessions', [count($robot_sessions)]); 
            }
            else{
                $response->error = __('No exist sessions'); 
            }


        }
        else{
            $response->error = __('Invalid params'); 
        }

        $this->response->type('json');
        $this->response->body(json_encode($response));

        return $this->response;

    }

    function getCatalogDates($store_id = null, $list = false, $ajax = false){

        $response = new \stdClass();

        if($store_id != null){


            $catalog_updates = $this->RobotSessions->Stores->CatalogUpdates->find()
                ->select([
                    'CatalogUpdates.id', 
                    'CatalogUpdates.catalog_date'
                ])
                ->where([
                    'CatalogUpdates.store_id' => $store_id, 
                ])
                ->group('CatalogUpdates.catalog_date')
                ->toArray();


            if(count($catalog_updates) > 0){

                $dates = [];
                foreach ($catalog_updates as $catalog) {
                    $dates[] = $catalog->catalog_date->format('Y-m-d');
                } 

                if($ajax == true){
                
                    $response->status = true;
                    $response->error = '';
                    $response->message = __('Get {0} records', count($dates));
                    $response->data = [
                        'sessions' => $dates
                    ];

                    
                }
                else{
                    $dates = $catalog_updates;
                    return $dates;
                }

            }
        }

        $this->response->type('json');
        $this->response->body(json_encode($response));

        return $this->response;
    }
}