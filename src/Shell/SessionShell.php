<?php
namespace App\Shell;

use Cake\Console\Shell;
use Cake\Event\Event;
use Cake\Network\Http\Client;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;
use Cake\Utility\Text;
use Cake\Datasource\ConnectionManager;
use Google\Cloud\BigQuery\BigQueryClient;
use Google\Cloud\Core\ExponentialBackoff;
use Google\Service\Storage;
use Cake\Core\App;
use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Robotusers\Excel\Registry;
use Robotusers\Excel\Excel\Manager;
use App\Controller\ProductsController;
use App\Controller\RobotReportsController;
use App\Controller\ReportsController;
use App\Controller\RobotSessionsController;
use App\Controller\EmailsController;
use Cake\Filesystem\Folder;
use Cake\Filesystem\File;
use App\View\Helper\EanHelper;
use Picqer\Barcode\BarcodeGeneratorPNG;
use Cake\Mailer\Email;

class SessionShell extends Shell
{

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('RobotSessions');
        $this->loadModel('ProductsStores');
        $this->loadModel('Detections');
        $this->loadModel('PriceUpdates');
        $this->loadModel('Stores');
        $this->loadModel('Companies');
        $this->loadModel('Products');
        $this->loadModel('Sections');
        $this->loadModel('Categories');
        $this->loadModel('SubCategories');
        $this->loadModel('Companies');
        $this->loadModel('MeasurementUnits');
        $this->loadModel('Aisles');
        $this->loadModel('AnalyzedProducts');
    }

    public $tasks = ['Cencosud', 'Sodimac', 'Walmart'];

    function summary($send_email = false){

        $companies = $this->Stores->Companies->find('all')
            ->contain([
                'Stores' => [
                    'queryBuilder' => function (\Cake\ORM\Query $query) {
                        return $query->where(['Stores.active' => 1]);
                    },
                ]
            ])
            ->where([
                'Companies.active' => 1
            ])
            ->toArray();

        if(count($companies) > 0){

            $sessions_info = [];

            foreach($companies as $company){
                if(count($company->stores) > 0){

                    $now = New Time();
                    $now->modify('-1 days');

                    foreach($company->stores as $store){

                        $robot_session = $this->RobotSessions->find()
                            ->where([
                                'RobotSessions.store_id' => $store->id,
                                'RobotSessions.is_test' => 0,
                                'RobotSessions.session_date >' => $now->format('Y-m-d').' 22:55:00',
                            ])
                            ->order([
                                'RobotSessions.session_date'=> 'DESC'
                            ])
                            ->first();

                        if($robot_session != null){
                            $this->out(__('Generating info string for [{0}] {1} - {2}', [$store->store_code, $company->company_name, $store->store_name]));

                            $sessions_info[$store->id]['has_session'] = true;
                            $sessions_info[$store->id]['company_name'] = $company->company_name;
                            $sessions_info[$store->id]['store_name'] = $store->store_name;
                            $sessions_info[$store->id]['store_code'] = $store->store_code;
                            $sessions_info[$store->id]['session_date'] = $robot_session->session_date->format('d-m-Y H:i:s');
                            $sessions_info[$store->id]['session_code'] = $robot_session->session_code;
                            $sessions_info[$store->id]['total_detections'] = $robot_session->total_detections;

                            if($robot_session->assortment_finished == 1){
                                
                                $sessions_info[$store->id]['assortment_processing_date'] = $robot_session->assortment_processing_date->format('d-m-Y H:i:s');
                                $sessions_info[$store->id]['assortment_finished_date'] = $robot_session->assortment_finished_date->format('d-m-Y H:i:s');

                                $sessions_info[$store->id]['total_catalogs'] = $robot_session->total_catalogs;
                                $sessions_info[$store->id]['total_catalog_readed_products'] = $robot_session->total_catalog_readed_products;
                                $sessions_info[$store->id]['total_catalog_unreaded_products'] = $robot_session->total_catalog_unreaded_products;
                                $sessions_info[$store->id]['total_catalog_readed_and_blocked_products'] = $robot_session->total_catalog_readed_and_blocked_products;
                                $sessions_info[$store->id]['total_catalog_unreaded_and_blocked_products'] = $robot_session->total_catalog_unreaded_and_blocked_products;
                            }
                            else{
                                $sessions_info[$store->id]['assortment_processing_date'] = 'No hay datos';
                                $sessions_info[$store->id]['assortment_finished_date'] = 'No hay datos';

                                $sessions_info[$store->id]['total_catalogs'] = 'No hay datos';
                                $sessions_info[$store->id]['total_catalog_readed_products'] = 'No hay datos';
                                $sessions_info[$store->id]['total_catalog_unreaded_products'] = 'No hay datos';
                                $sessions_info[$store->id]['total_catalog_readed_and_blocked_products'] = 'No hay datos';
                                $sessions_info[$store->id]['total_catalog_unreaded_and_blocked_products'] = 'No hay datos';
                            }

                            if($robot_session->price_differences_labels_finished == 1){
                                
                                $sessions_info[$store->id]['price_differences_labels_processing_date'] = $robot_session->price_differences_labels_processing_date->format('d-m-Y H:i:s');
                                $sessions_info[$store->id]['price_differences_labels_finished_date'] = $robot_session->price_differences_labels_finished_date->format('d-m-Y H:i:s');
                                $sessions_info[$store->id]['total_price_differences_labels'] = $robot_session->total_price_difference_detections;
                                $sessions_info[$store->id]['total_price_differences_products'] = $robot_session->total_price_difference_products;
                            }
                            else{
                                $sessions_info[$store->id]['price_differences_labels_processing_date'] = 'No hay datos';
                                $sessions_info[$store->id]['price_differences_labels_finished_date'] = 'No hay datos';
                                $sessions_info[$store->id]['total_price_differences_labels'] = 'No hay datos';
                                $sessions_info[$store->id]['total_price_differences_products'] = 'No hay datos';
                            }

                            if($robot_session->facing_labels_finished == 1){
                                
                                $sessions_info[$store->id]['facing_labels_processing_date'] = $robot_session->facing_labels_processing_date->format('d-m-Y H:i:s');
                                $sessions_info[$store->id]['facing_labels_finished_date'] = $robot_session->facing_labels_finished_date->format('d-m-Y H:i:s');
                                $sessions_info[$store->id]['total_stock_alert_detections'] = $robot_session->total_stock_alert_detections;
                                $sessions_info[$store->id]['total_stock_alert_products'] = $robot_session->total_stock_alert_products;

                            }
                            else{
                                $sessions_info[$store->id]['facing_labels_processing_date'] = 'No hay datos';
                                $sessions_info[$store->id]['facing_labels_finished_date'] = 'No hay datos';
                                $sessions_info[$store->id]['total_stock_alert_detections'] = 'No hay datos';
                                $sessions_info[$store->id]['total_stock_alert_products'] = 'No hay datos';
                            }


                        }
                        else{
                            $sessions_info[$store->id]['company_name'] = $company->company_name;
                            $sessions_info[$store->id]['store_name'] = $store->store_name;
                            $sessions_info[$store->id]['store_code'] = $store->store_code;
                            $sessions_info[$store->id]['has_session'] = false;
                        }   
                    }
                }
            }
     
            $users= [
                [
                    'name' => 'Hugo',
                    'last_name' => 'Inda',
                    'email' => 'hugo@zippedi.com'
                ],
                [
                    'name' => 'Ariel',
                    'last_name' => 'Schilkrut',
                    'email' => 'ariel@zippedi.com'
                ],
                [
                    'name' => 'Jose Manuel',
                    'last_name' => 'Díaz',
                    'email' => 'josemanuel@zippedi.com'
                ],
                [
                    'name' => 'Rodrigo',
                    'last_name' => 'Gonzalez',
                    'email' => 'rodrigo@zippedi.com'
                ],
                [
                    'name' => 'Álvaro',
                    'last_name' => 'Soto',
                    'email' => 'alvaro@zippedi.com'
                ],
                [
                    'name' => 'Ivan',
                    'last_name' => 'Soto',
                    'email' => 'ivan@zippedi.com'
                ],
                [
                    'name' => 'Juan Pablo',
                    'last_name' => 'Valencia',
                    'email' => 'juanpablo@zippedi.com'
                ],
            ];

            if(count($users) > 0){
                foreach($users as $user){
                    $email = new Email('default');
                    $email->template('summary_sessions', 'admin_layout');
                    $email->subject('Resumen reportes /carga de datos my.zippedi.com ['.date('d-m-Y').']');
                    $email->emailFormat('html');
                    $email->viewVars(['sessions_info' => $sessions_info]);
                    $email->attachments([
                        'new_zippedi_logo_horizontal.png' => [
                            'file' => ROOT.'/webroot/img/new_zippedi_logo_horizontal.png',
                            'mimetype' => 'image/png',
                            'contentId' => 'logo-id'
                        ]
                    ]);
                    $email->from (array('reports@zippedi.com' => 'Zippedi Reports'));
                    $email->to($user['email'], $user['name'].' '.$user['last_name']);

                    $email->send();

                    $this->out('Email enviado a '.$user['email']);
                }
            }
        }
    }

    function summaryHighLevel($company_keyword = null){

        if($company_keyword != null){

            $company = $this->Companies->find('all')
                ->contain([
                    'Stores' => [
                        //'foreignKey' => false,
                        'queryBuilder' => function (\Cake\ORM\Query $query) {
                            return $query->where(['Stores.active' => 1]);
                        },
                        //'RobotSessions'
                    ]
                ])
                ->where([
                    'Companies.active' => 1,
                    'Companies.company_keyword' => $company_keyword
                ])
                ->first();

            if($company != null){
                
                if(count($company->stores) > 0){

                    $data = [
                        'global' =>[
                            'total_analyzed_aisles' => 0,
                            'total_detections' => 0,
                            'audited_products' => 0,
                            'total_price_difference_detections' => 0,
                            'total_stock_alert_detections' => 0,
                            'total_percent_assortment' => 0,
                            'total_products' => 0, 
                            'total_shelfs' => 0
                        ],
                        'stores' => [
                        ]
                    ];

                    foreach($company->stores as $store){
                        $data['stores'][$store->id] = [];
                    }

                    foreach($company->stores as $store){
                        //$this->out($store->store_code);

                        $robot_session = $this->RobotSessions->find()
                            ->where([
                                'RobotSessions.store_id' => $store->id,
                                'RobotSessions.is_test' => 0,
                                'RobotSessions.total_detections >' => 0,
                                //'DATE(RobotSessions.session_date)' => date('Y-m-d'),
                                'RobotSessions.labels_processing' => 0,
                                'RobotSessions.labels_finished' => 1,
                            ])
                            ->order([
                                'RobotSessions.session_date'=> 'DESC'
                            ])
                            ->first();

                        //print_r($robot_session);

                        if($robot_session != null){

                            //$data['stores'][$store->id] = [];
                            $data['stores'][$store->id]['store']['id'] = $store->id;
                            $data['stores'][$store->id]['store']['store_name'] = $store->store_name;
                            $data['stores'][$store->id]['store']['store_code'] = $store->store_code;

                            $data['stores'][$store->id]['robot_session']['data']['id'] = $robot_session->id;
                            $data['stores'][$store->id]['robot_session']['data']['session_date'] = $robot_session->session_date;
                            $data['stores'][$store->id]['robot_session']['data']['session_code'] = $robot_session->session_code;

                            $data['stores'][$store->id]['robot_session']['stats']['total_detections'] = $robot_session->total_detections;
                            $data['stores'][$store->id]['robot_session']['stats']['total_price_difference_detections'] = $robot_session->total_price_difference_detections;
                            $data['stores'][$store->id]['robot_session']['stats']['total_stock_alert_detections'] = $robot_session->total_stock_alert_detections;

                            $data['stores'][$store->id]['robot_session']['stats']['total_analyzed_aisles'] = $robot_session->analyzed_aisles;


                        }
                        else{
                            //$email_text .= __('*{0} {1}: No hay datos.<br><br>', [$store->store_code, $store->store_name]);
                        }   
                    }

                    foreach($data['stores'] as $store_id => $d){
                        
                        //$data['global']['total_analyzed_shelfs']
                        $data['global']['total_detections'] = $data['global']['total_detections'] + $d['robot_session']['stats']['total_detections'];

                        $data['global']['total_price_difference_detections'] = $data['global']['total_price_difference_detections'] + $d['robot_session']['stats']['total_price_difference_detections'];

                        $data['global']['total_stock_alert_detections'] = $data['global']['total_stock_alert_detections'] + $d['robot_session']['stats']['total_stock_alert_detections'];

                        $data['global']['total_analyzed_aisles'] = $data['global']['total_analyzed_aisles'] + $d['robot_session']['stats']['total_analyzed_aisles'];

                    }
                    

                    print_r($data);
                    //die();
                }

                
         
                $users= [
                    [
                        'name' => 'Hugo',
                        'last_name' => 'Inda',
                        'email' => 'hugo@zippedi.com'
                    ],
                    /*[
                        'name' => 'Ariel',
                        'last_name' => 'Schilkrut',
                        'email' => 'ariel@zippedi.com'
                    ],
                    [
                        'name' => 'Jose Manuel',
                        'last_name' => 'Díaz',
                        'email' => 'josemanuel@zippedi.com'
                    ],
                    [
                        'name' => 'Rodrigo',
                        'last_name' => 'Gonzalez',
                        'email' => 'rodrigo@zippedi.com'
                    ],
                    [
                        'name' => 'Álvaro',
                        'last_name' => 'Soto',
                        'email' => 'alvaro@zippedi.com'
                    ],
                    [
                        'name' => 'Ivan',
                        'last_name' => 'Soto',
                        'email' => 'ivan@zippedi.com'
                    ],*/
                ];

                $pdf_path = ROOT . DIRECTORY_SEPARATOR . 'webroot'. DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . 'pdfs' .  DIRECTORY_SEPARATOR . 'summary-high-livel-'.$company_keyword.'-'.date('d-m-Y').'.pdf';

                if(file_exists($pdf_path)){
                    unlink($pdf_path);
                }

                if(!file_exists($pdf_path)){

                    $CakePdf = new \CakePdf\Pdf\CakePdf();
                    $CakePdf->template('summary_high_level', 'default');
                    $CakePdf->viewVars(['data' => $data, 'type' => 'list', 'company' => $company]);
                    // Get the PDF string returned
                    //$pdf = $CakePdf->output();
                    // Or write it to file directly
                    $pdf = $CakePdf->write($pdf_path); 
                }

                if(count($users) > 0){
                    foreach($users as $user){
                        $email = new Email('default');
                        $email->template('summary_high_level', 'admin_layout');
                        $email->subject('Resumen de alto nivel ['.date('d-m-Y').'] / '.$company->company_name);
                        $email->emailFormat('html');
                        $email->viewVars(['data' => $data]);
                        $email->attachments([
                            'new_zippedi_logo_horizontal.png' => [
                                'file' => ROOT.'/webroot/img/new_zippedi_logo_horizontal.png',
                                'mimetype' => 'image/png',
                                'contentId' => 'logo-id'
                            ],
                            'summary-high-livel-'.$company_keyword.'-'.date('d-m-Y').'.pdf' => [
                                'file' => $pdf_path
                            ]
                        ]);


                        $email->from (array('reports@zippedi.com' => 'Zippedi Reports'));
                        $email->to($user['email'], $user['name'].' '.$user['last_name']);

                        $email->send();

                        $this->out('Email enviado a '.$user['email']);
                    }
                }
            }
        }
    }
}