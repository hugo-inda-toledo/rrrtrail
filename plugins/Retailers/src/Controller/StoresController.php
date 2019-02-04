<?php
namespace Retailers\Controller;

use Retailers\Controller\AppController;
use Cake\I18n\Time;
use Cake\Controller\Component\PaginatorComponent;
use Picqer\Barcode\BarcodeGeneratorPNG;
use App\View\Helper\EanHelper;

/**
 * Stores Controller
 *
 *
 * @method \Retailers\Model\Entity\Store[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class StoresController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Stores');
    }

    public function details($store_code = null){
        if($store_code != null){

            $stores_list = $this->getAllUserStores('array_list_code');

            $retailer = true;
            //$retailer = $this->isRetailer(); Funcion para saber si un usuario es retailer o proveedor

            if($retailer == false){
                $this->Flash->danger(__("You don't access"));
                //$this->reportWarning(); Funcion para captar los intentos a la plataforma sin acceso
                $this->redirect($this->referer());
            }

            if(count($stores_list) == 0){
                $this->Flash->warning(__("You don't access to the {0} store", $store->store_code));
                $this->redirect($this->referer());
            }

            $store_data = $this->Stores->find('all')
                ->contain([
                    'Locations' => [
                        'Countries',
                        'Regions',
                        'Communes'
                    ],
                    'Companies',
                ])
                //->select(['Stores.id', 'Stores.company_id','Stores.store_name', 'store_code'])
                ->where([
                    'Stores.store_code' => $store_code
                ])
                ->first();

            if(!isset($stores_list[$store_data->store_code])){
                $this->Flash->warning(__("You don't access to the {0} store", $store_data->store_code));
                $this->redirect($this->referer());
            }

            if(count($store_data) == 0){

                $this->Flash->error(__("Store {0} not exist", $store_code));
                $this->redirect($this->referer());
            }

            $all_robot_sessions = $this->Stores->RobotSessions->find('all')
                ->where([
                    'RobotSessions.store_id' => $store_data->id,
                    'RobotSessions.is_test' =>0,
                    'RobotSessions.total_detections IS NOT NULL'
                ])
                ->order(['RobotSessions.session_date' => 'DESC'])
                //->limit(7)
                ->toArray();

            $robot_session_calendar = [];
            $data = [
                'summary' => [
                ],
                'stats' => [
                ],
                'reports' => [
                ]
            ];

            for($x = 0; $x < count($all_robot_sessions); $x++){

                $robot_session_calendar[] = $all_robot_sessions[$x]->session_date->format('Y-m-d');

                if($x < 15){
                    $data['summary']['global_chart'][$x]['datetime'] = $all_robot_sessions[$x]->session_date->format('Y-m-d');
                    $data['summary']['global_chart'][$x]['all_seen_labels'] = $all_robot_sessions[$x]->total_detections;
                    $data['summary']['global_chart'][$x]['labels_with_price_difference'] = $all_robot_sessions[$x]->total_price_difference_detections;
                    $data['summary']['global_chart'][$x]['detections_with_stock_alert'] = $all_robot_sessions[$x]->total_stock_alert_detections;
                }
                
            }

            $data['summary']['global_chart'] = array_reverse($data['summary']['global_chart']);

            $this->set('data', $data);
            $this->set('robot_sessions', $all_robot_sessions);
            $this->set('robot_session_calendar', $robot_session_calendar);
            
            $this->set('store_data', $store_data);
        }
    }

    public function detailsBckp($store_code = null){
        if($store_code != null){

            $stores_list = $this->getAllUserStores('array_list_code');

            $retailer = true;
            //$retailer = $this->isRetailer(); Funcion para saber si un usuario es retailer o proveedor

            if($retailer == false){
                $this->Flash->danger(__("You don't access"));
                //$this->reportWarning(); Funcion para captar los intentos a la plataforma sin acceso
                $this->redirect($this->referer());
            }


            if(count($stores_list) == 0){
                $this->Flash->warning(__("You don't access to the {0} store", $store->store_code));
                $this->redirect($this->referer());
            }

            if(!isset($stores_list[$store_code])){
                $this->Flash->warning(__("You don't access to the {0} store", $store->store_code));
                $this->redirect($this->referer());
            }

            $store_data = $this->Stores->find('all')
                ->contain([
                    'Locations' => [
                        'Countries',
                        'Regions',
                        'Communes'
                    ],
                    'Companies',
                ])
                //->select(['Stores.id', 'Stores.company_id','Stores.store_name', 'store_code'])
                ->where([
                    'Stores.store_code' => $store_code
                ])
                ->first();

            if(count($store_data) == 0){

                $this->Flash->error(__("Store {0} not exist", $store->store_code));
                $this->redirect($this->referer());
            }

            $robot_sessions = $this->Stores->RobotSessions->find('all')
                ->where(['RobotSessions.store_id' => $store_data->id])
                ->order(['RobotSessions.session_date' => 'DESC'])
                ->limit(7)
                ->toArray();

            ksort($robot_sessions);

            /*echo '<pre>';
            print_r($robot_sessions);
            echo '<pre>';*/

            $this->set('robot_sessions', $robot_sessions);

            /*if(count($robot_sessions) == 0){

                $this->Flash->error(__("Not exist processed sessions", $store_data->store_code));
                return $this->redirect($this->referer());
            }*/

            $store_id = $store_data->id;
            $robot_session_data = [];
            $catalogs = [];
            $data = [];


            foreach($robot_sessions as $robot_session){

                $robot_session_cond['Detections.robot_session_id'] = $robot_session->id;
                $session_date_query = $robot_session->session_date->format('Y-m-d');
                $catalog_date = New Time($robot_session->session_date);

                $catalog_date->modify('-1 day');
                $catalog_date_query = $catalog_date->format('Y-m-d');


                if($robot_session->total_catalogs != null){
                    //Hay datos globales, mostrar y break

                    $data['reports'] = [
                        'assortment' => [  
                            'numbers_stats' => [
                                'total_master' => $robot_session->total_catalogs,
                                'total_deals' => 0,
                                'readed_products' => $robot_session->total_catalog_readed_products,
                                'unreaded_products' => $robot_session->total_catalog_unreaded_products,
                                'readed_and_blocked_products' => $robot_session->total_catalog_readed_and_blocked_products,
                                'unreaded_and_blocked_products' => $robot_session->total_catalog_unreaded_and_blocked_products,
                                'readed_and_discontinued_products' => $robot_session->total_catalog_readed_and_discontinued_products,
                                'unreaded_and_discontinued_products' => $robot_session->total_catalog_unreaded_and_discontinued_products
                            ],
                            'percent_stats' => [
                                'readed_products' => 0,
                                'unreaded_products' => 0,
                                'readed_and_blocked_products' => 0,
                                'unreaded_and_blocked_products' => 0,
                                'readed_and_discontinued_products' => 0,
                                'unreaded_and_discontinued_products' => 0
                            ]
                        ],
                        'price_difference' => [
                            'total_detections' => $robot_session->total_price_difference_products,
                            /*'total_products' => [
                            ]*/
                        ],
                        'stock_alert' => [
                            'total_alerts' => $robot_session->total_stock_alert_detections,
                        ]
                    ];

                    $robot_session_data = $robot_session;
                    break;
                }   

                /****** Consultar por cache*****/

                //Surtido
                $catalogs = $this->Stores->RobotSessions->Detections->ProductsStores->CatalogUpdates->find('all')
                    ->contain([
                        'ProductsStores' => [
                            'queryBuilder' => function (\Cake\ORM\Query $query) {
                                return $query
                                    ->select(['ProductsStores.id', /*'ProductsStores.section_id', 'ProductsStores.category_id', 'ProductsStores.product_state_id', 'ProductsStores.description', 'ProductsStores.ean13', 'ProductsStores.internal_code'*/]);
                            },
                            'Detections' => [
                                'queryBuilder' => function (\Cake\ORM\Query $query) use($robot_session_cond) {
                                    return $query                                        
                                        ->where([$robot_session_cond]);
                                        //->limit(1);
                                },
                                'ProductsStores' => [
                                    'queryBuilder' => function (\Cake\ORM\Query $query){
                                        return $query
                                            ->select(['ProductsStores.id', 'ProductsStores.section_id', 'ProductsStores.category_id', 'ProductsStores.description', 'ProductsStores.ean13', 'ProductsStores.internal_code']);
                                    },
                                    'Sections' => [
                                        'queryBuilder' => function (\Cake\ORM\Query $query) use($store_id){
                                            return $query
                                                ->select([
                                                    'Sections.id',
                                                    'Sections.section_name',
                                                    'Sections.section_code',
                                                ]);
                                        }
                                    ],
                                    'Categories' => [
                                        'queryBuilder' => function (\Cake\ORM\Query $query) use($store_id){
                                            return $query
                                                ->select([
                                                    'Categories.id',
                                                    'Categories.category_name',
                                                    'Categories.category_code',
                                                ]);
                                        }
                                    ],
                                    'StockUpdates' => [
                                        'queryBuilder' => function (\Cake\ORM\Query $query) use($store_id, $session_date_query){
                                            return $query
                                                ->select([
                                                    'StockUpdates.id',
                                                    'StockUpdates.product_store_id',
                                                    'StockUpdates.current_stock',
                                                    //'StockUpdates.stock_updated'
                                                ])
                                                ->where([
                                                    'StockUpdates.store_id' => $store_id,
                                                    'DATE(StockUpdates.stock_updated) <=' => $session_date_query
                                                ])                             
                                                ->order(['StockUpdates.stock_updated' => 'DESC']);
                                        }
                                    ],
                                    'PriceUpdates' => [
                                        'fields' => [
                                            'PriceUpdates.id',
                                            'PriceUpdates.product_store_id',
                                            'PriceUpdates.price'
                                        ],
                                        'conditions' => [
                                            'PriceUpdates.company_updated <=' => $session_date_query,
                                            'PriceUpdates.store_id' => $store_id,                            
                                        ],
                                        'sort' => ['PriceUpdates.company_updated' => 'DESC']
                                    ]
                                ]
                            ],
                            'DealUpdates' => [
                                'queryBuilder' => function (\Cake\ORM\Query $query) use($session_date_query, $store_id){
                                    return $query
                                        ->select(['DealUpdates.id', 'DealUpdates.product_store_id'])                                    
                                        ->where([
                                            'DealUpdates.store_id' => $store_id,
                                            'DATE(DealUpdates.start_date) <=' => $session_date_query,
                                            'DATE(DealUpdates.end_date) >=' => $session_date_query
                                        ]);
                                        //->limit(1);
                                }
                            ]
                        ]
                    ])
                    ->select([
                        'CatalogUpdates.id', 'CatalogUpdates.product_store_id','CatalogUpdates.enabled', 'CatalogUpdates.cataloged', 'CatalogUpdates.catalog_date'
                    ])
                    ->where([
                        'DATE(CatalogUpdates.catalog_date)' => $catalog_date_query,
                        'CatalogUpdates.store_id' => $store_data->id
                    ])
                    //->limit(20)
                    ->toArray();

                if(count($catalogs) > 0){
                    $robot_session_data = $robot_session;
                    break;
                }
            }


            if(isset($data['reports'])){
                $this->viewBuilder()->setLayout('new_default');

                $this->set('data', $data);
                $this->set('robot_session_data', $robot_session_data);
                $this->set('store_data', $store_data);
            }
            else{

                if(count($catalogs) == 0){
                    $this->Flash->error(__("Not exist processed sessions for {0}", $store_data->store_code));
                    return $this->redirect($this->referer());
                }
                else{
                    $data['reports'] = [
                        'assortment' => [  
                            'numbers_stats' => [
                                'total_master' => 0,
                                'total_deals' => 0,
                                'readed_products' => 0,
                                'unreaded_products' => 0,
                                'readed_and_blocked_products' => 0,
                                'unreaded_and_blocked_products' => 0,
                                'readed_and_discontinued_products' => 0,
                                'unreaded_and_discontinued_products' => 0
                            ],
                            'percent_stats' => [
                                'readed_products' => 0,
                                'unreaded_products' => 0,
                                'readed_and_blocked_products' => 0,
                                'unreaded_and_blocked_products' => 0,
                                'readed_and_discontinued_products' => 0,
                                'unreaded_and_discontinued_products' => 0
                            ]
                        ],
                        'price_difference' => [
                            'total_detections' => 0,
                            /*'total_products' => [
                            ]*/
                        ],
                        'stock_alert' => [
                            'total_alerts' => 0,
                        ]
                    ];

                    foreach($catalogs as $catalog){

                        if(count($catalog->products_store->detections) > 0){

                            //$data['reports']['price_difference']['total_products'][$catalog->products_store->internal_code] = 0;
                            /* Inicio Conteo Surtido */
                            $data['reports']['assortment']['numbers_stats']['readed_products'] = $data['reports']['assortment']['numbers_stats']['readed_products'] + 1;

                            if($catalog->cataloged == 0){
                                $data['reports']['assortment']['numbers_stats']['readed_and_discontinued_products'] = $data['reports']['assortment']['numbers_stats']['readed_and_discontinued_products'] + 1; 
                            }

                            if($catalog->enabled == 0){
                                $data['reports']['assortment']['numbers_stats']['readed_and_blocked_products'] = $data['reports']['assortment']['numbers_stats']['readed_and_blocked_products'] + 1; 
                            }
                            /* Fin Conteo Surtido */


                            
                            //foreach($catalog->products_store->detections as $detection){


                                /* Inicio Conteo Diferencia de Precio */
                                if(count($catalog->products_store->detections[0]->products_store->price_updates)> 0 && $catalog->products_store->detections[0]->label_price != $catalog->products_store->detections[0]->products_store->price_updates[0]->price){

                                    //ff$data['reports']['price_difference']['total_detections'] = $data['reports']['price_difference']['total_detections'] + 1; 


                                    //$data['reports']['price_difference']['total_products'][$detection->products_store->internal_code] = $data['reports']['price_difference']['total_products'][$detection->products_store->internal_code] + 1; 
                                }
                                /* Fin Conteo Diferencia de Precio */




                                /* Inicio Conteo Stock Out */
                                if($catalog->products_store->detections[0]->stock_alert == 1){

                                    //hjh$data['reports']['stock_alert']['total_alerts'] = $data['reports']['stock_alert']['total_alerts'] + 1;
                                }
                                /* Fin Conteo Stock Out */

                            //}


                            /* Inicio Conteo Diferencia de Precio */
                            //$data['reports']['price_difference']['total_products'] = $data['reports']['price_difference']['total_products'] + 1;
                            /* Fin Conteo Diferencia de Precio */

                        }
                        else{
                            
                            //print_r($catalog->products_stores->deal_updates);
                            //die();
                            //Si no tiene oferta
                            //if(count($catalog->products_stores->deal_updates) == 0){

                                /* Inicio Conteo Surtido */
                                $data['reports']['assortment']['numbers_stats']['unreaded_products'] = $data['reports']['assortment']['numbers_stats']['unreaded_products'] + 1;

                                if($catalog->enabled == 0){
                                    $data['reports']['assortment']['numbers_stats']['unreaded_and_blocked_products'] = $data['reports']['assortment']['numbers_stats']['unreaded_and_blocked_products'] + 1; 
                                }

                                if($catalog->cataloged == 0){
                                    $data['reports']['assortment']['numbers_stats']['unreaded_and_discontinued_products'] = $data['reports']['assortment']['numbers_stats']['unreaded_and_discontinued_products'] + 1; 
                                }
                                /* Fin Conteo Surtido */

                            //}
                        }

                        $data['reports']['assortment']['numbers_stats']['total_master'] = $data['reports']['assortment']['numbers_stats']['total_master'] + 1;
                    }


                    $robot_session_data->total_catalogs = $data['reports']['assortment']['numbers_stats']['total_master'];
                    $robot_session_data->total_catalog_readed_products = $data['reports']['assortment']['numbers_stats']['readed_products'];
                    $robot_session_data->total_catalog_unreaded_products = $data['reports']['assortment']['numbers_stats']['unreaded_products'];
                    $robot_session_data->total_catalog_readed_and_blocked_products = $data['reports']['assortment']['numbers_stats']['readed_and_blocked_products'];
                    $robot_session_data->total_catalog_unreaded_and_blocked_products = $data['reports']['assortment']['numbers_stats']['unreaded_and_blocked_products'];
                    $robot_session_data->total_catalog_readed_and_discontinued_products = $data['reports']['assortment']['numbers_stats']['readed_and_discontinued_products'];
                    $robot_session_data->total_catalog_unreaded_and_discontinued_products = $data['reports']['assortment']['numbers_stats']['unreaded_and_discontinued_products'];
                    $robot_session_data->total_price_difference_detections = $data['reports']['price_difference']['total_detections'];
                    $robot_session_data->total_stock_alert_detections = $data['reports']['stock_alert']['total_alerts'];

                    $this->RobotSessions->save($robot_session_data);

                    $this->viewBuilder()->setLayout('new_default');

                    $this->set('data', $data);
                    $this->set('robot_session_data', $robot_session_data);
                    $this->set('store_data', $store_data);
                }
            }
        }
    }

        
    public function mapNew()
    {
        $stores_data = $this->getAllUserStores('map');

        if(count($stores_data) == 0){
            $this->Flash->info(__('You not have associated stores, contact the zippedi team').'.');
            $this->redirect($this->referer());
        }

        $this->set('stores_data', $stores_data);
        //$this->set('users_suppliers_data', $users_suppliers_data);
    }

    public function map()
    {
        $stores_data = $this->getAllUserStores('map');

        if(count($stores_data) == 0){
            $this->Flash->info(__('You not have associated stores, contact the zippedi team').'.');
            $this->redirect($this->referer());
        }

        $this->set('stores_data', $stores_data);
        //$this->set('users_suppliers_data', $users_suppliers_data);
    }

    function getAllUserStores($type = null){
        $this->loadModel('UsersSuppliers');
        $this->loadModel('UsersCompanies');

        $stores_data = array();

        $users_suppliers_data = $this->UsersSuppliers->find('all')
            ->contain([
                'Stores' => [
                    'RobotSessions' => [
                        'conditions' => [
                            'RobotSessions.total_detections >' => 0,
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
                        ],
                        'sort' => ['RobotSessions.session_date' => 'DESC']
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
                        $stores_data[$user_supplier_data->store->id]['store'] = $user_supplier_data->store;
                        $stores_data[$user_supplier_data->store->id]['company'] = $user_supplier_data->company;
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
                        $stores_data[$user_company_data->store->id]['store'] = $user_company_data->store;
                        $stores_data[$user_company_data->store->id]['company'] = $user_company_data->company;
                        break;
                }
            }
        }
        
        return $stores_data;   
    }
}
