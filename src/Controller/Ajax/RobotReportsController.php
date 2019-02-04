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

class RobotReportsController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadModel('ProductsStores');
        $this->loadModel('Stores');
        $this->loadModel('Sections');
        $this->loadModel('ProductStates');
        
        $this->Auth->allow(['doAssortmentReport']);
        $this->Auth->allow(['downloadPdf']);
        $this->Auth->allow(['assortmentReportPdfReport']);
        $this->Auth->allow(['getSessionsList']);

        
        $this->loadComponent('RequestHandler');
    }

    var $endpoint = 'https://reports2.zippedi.cl';
    //var $endpoint = 'https://reports.zippedi.cl';

    /**
    // Get the sessions list that Zippedi has made
    **/
    public function getSessionsList($store_id = null, $show = false){

        $response = new \stdClass();
        $response->status = false;
        $response->error = '';
        $response->message = '';
        $response->data = [];

        if($store_id == null){
            $response->error = __('Invalid Params.');

            $this->response->type('json');
            $this->response->body(json_encode($response));

            return $this->response;
        }

        $store_data = $this->Stores->get($store_id);

        if($store_data == null){
            $response->error = __('Store not exist.');

            $this->response->type('json');
            $this->response->body(json_encode($response));

            return $this->response;
        }

        $http = new Client();
        $request_token = $http->post($this->endpoint.'/auth', json_encode($this->auth_data), ['type' => 'json']);
        $url = $this->endpoint.'/status/session_list';

        $http = new Client([
            'headers' => ['Authorization' => 'jwt ' . $request_token->json['access_token']]
        ]);

        $robot_response = $http->get($url, ['store' => $store_data->store_code]);

        if($robot_response->getStatusCode() != 200){

            $response->error = __('Error '.$robot_response->code);

            $this->response->type('json');
            $this->response->body(json_encode($response));

            return $this->response;
        }

        if(count($robot_response->json) > 0){
            $response->error = __("Get {0} records", count($robot_response->json));

            $response_array = array_unique($robot_response->json);

            //krsort($response_array);

            $response->status = true;
            $response->data = [
                'sessions' => $response_array
            ];

            $this->response->type('json');
            $this->response->body(json_encode($response));

            return $this->response;
        }
        else{
            $response->error = __("No session data");

            $this->response->type('json');
            $this->response->body(json_encode($response));

            return $this->response;
        }
    }
    
    function doAssortmentReport(){
        if($this->request->is('post')){
            $response = new \stdClass();
            $response->status = false;
            $response->error = '';
            $response->message = '';
            $response->data = [];

            if($this->request->data('company_id') == null || $this->request->data('store_id') == null || $this->request->data('end_date') == null || $this->request->data('section_id') == null){
                $response->error = __('Invalid Params.');

                $this->response->type('json');
                $this->response->body(json_encode($response));

                return $this->response;
            }

            
            $this->loadModel('RobotSessions');
            //$store = $this->Stores->get($this->request->data('store_id'), ['contain' => ['Companies']]);
            $robot_session = $this->RobotSessions->find()
                ->contain([
                    'Stores' => [
                        'Companies'
                    ]
                ])
                ->where([
                    'DATE(RobotSessions.calendar_date)' => $this->request->data('end_date'),
                    'RobotSessions.store_id' => $this->request->data('store_id')
                ])
                ->first();

            $store_id = $robot_session->store->id;
            $catalog_date = New Time($robot_session->calendar_date->format('Y-m-d'));
            $catalog_date_query = $robot_session->calendar_date->format('Y-m-d');


            $sections_cond_arr = [];
            $categories_cond_arr = [];
            $products_cond_arr = [];

            //$section_id = null;
            $section = null;
            if($this->request->data('section_id') != 'all'){
                $products_cond_arr['ProductsStores.section_id'] = $this->request->data('section_id');
                $sections_cond_arr['Sections.id'] = $this->request->data('section_id');

                //$section_id = $this->request->data('section_id');
                $section = $this->ProductsStores->Sections->get($this->request->data('section_id'));
            }
            else{
                $active_sections = $this->ProductsStores->Sections->find('all')
                    ->select('Sections.id')
                    ->where(['Sections.enabled' => 1])
                    ->toArray();

                $all_sections = [];
                foreach($active_sections as $active_section){
                    $all_sections[] = $active_section->id;
                }

                $products_cond_arr['ProductsStores.section_id IN'] = $all_sections;
            }

            //$category_id = null;
            $category = null;
            if($this->request->data('category_id') != ''){
                $products_cond_arr['ProductsStores.category_id'] = $this->request->data('category_id');
                $categories_cond_arr['Categories.id'] = $this->request->data('category_id');

                //$category_id = $this->request->data('category_id');
                $category = $this->ProductsStores->Sections->Categories->get($this->request->data('category_id'));
            }

            $brands_cond_arr = [];
            $suppliers_cond_arr = [];


            if($robot_session->store->company->company_keyword == 'jumbo'){
                $catalogs = $this->ProductsStores->CatalogUpdates->find('all')
                    ->contain([
                        'ProductsStores' => [
                            'queryBuilder' => function (\Cake\ORM\Query $query) use($products_cond_arr, $store_id, $catalog_date_query) {
                                return $query
                                    ->select(['ProductsStores.id', 'ProductsStores.company_id', 'ProductsStores.section_id', 'ProductsStores.category_id', 'ProductsStores.description', 'ProductsStores.ean13', 'ProductsStores.internal_code'])
                                    ->where([$products_cond_arr])
                                    /*->matching('StockUpdates', function ($q) use($store_id, $catalog_date_query){
                                        //return $q->where(['Articles.created >=' => new DateTime('-10 days')]);

                                        return $q
                                            ->select([
                                                'StockUpdates.id',
                                                'StockUpdates.product_store_id',
                                                'StockUpdates.current_stock',
                                                //'StockUpdates.stock_updated'
                                            ])
                                            ->where([
                                                'StockUpdates.store_id' => $store_id,
                                                'DATE(StockUpdates.stock_updated) <=' => $catalog_date_query,
                                                'StockUpdates.current_stock >' => 0
                                            ])                             
                                            ->order(['StockUpdates.stock_updated' => 'DESC']);
                                            //->limit(1);
                                    })*/
                                    ->matching('Sections', function ($q){
                                        //return $q->where(['Articles.created >=' => new DateTime('-10 days')]);

                                        return $q
                                            ->where([
                                                'Sections.enabled' => 1
                                            ]);                       
                                            //->limit(1);
                                    });
                            },
                            /*'Brands' => [
                                'queryBuilder' => function (\Cake\ORM\Query $query) use($brands_cond_arr){
                                    return $query
                                        ->where([$brands_cond_arr]);
                                },
                                'Suppliers' => [
                                    'queryBuilder' => function (\Cake\ORM\Query $query) use($suppliers_cond_arr){
                                        return $query
                                            ->where([$suppliers_cond_arr]);
                                    },
                                ]
                            ],*/
                            'Sections' => [
                                'queryBuilder' => function (\Cake\ORM\Query $query) {
                                    return $query
                                        ->select([
                                            'Sections.id',
                                            'Sections.section_name',
                                            'Sections.section_code',
                                        ]);
                                }
                            ],
                            'Categories' => [
                                'queryBuilder' => function (\Cake\ORM\Query $query) {
                                    return $query
                                        ->select([
                                            'Categories.id',
                                            'Categories.category_name',
                                            'Categories.category_code',
                                        ]);
                                }
                            ],
                            /*'StockUpdates' => [
                                /*'queryBuilder' => function (\Cake\ORM\Query $query) use($store_id, $session_date_query){
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
                            ]*/
                        ]
                    ])
                    ->select([
                        'CatalogUpdates.id', 'CatalogUpdates.product_store_id', 'CatalogUpdates.store_id', 'CatalogUpdates.enabled', 'CatalogUpdates.cataloged', 'CatalogUpdates.catalog_date', 'CatalogUpdates.seen', 'CatalogUpdates.stock'
                    ])
                    ->where([
                        'DATE(CatalogUpdates.catalog_date)' => $catalog_date_query,
                        'CatalogUpdates.store_id' => $store_id,
                        'CatalogUpdates.seen' => 0,
                        'CatalogUpdates.enabled' => 1,
                        'CatalogUpdates.cataloged' => 1,
                        'CatalogUpdates.stock >' => 0
                    ])
                    ->group('CatalogUpdates.product_store_id')
                    ->order([
                        'CatalogUpdates.catalog_date' => 'DESC'
                    ])
                    //->limit(1000)
                    ->toArray();
                    //->cache('assortment_query_'.$dates['global']['end_date']['master_date']->format('Y-m-d').'_'.$store->store_code.'_'.$this->request->data('section_id').($this->request->data('category_id') != '') ? '_'.$this->request->data('category_id') : '', 'config_cache_query');
            }
            else{
                $catalogs = $this->ProductsStores->CatalogUpdates->find('all')
                    ->contain([
                        'ProductsStores' => [
                            'queryBuilder' => function (\Cake\ORM\Query $query) use($products_cond_arr, $store_id, $catalog_date_query) {
                                return $query
                                    ->select(['ProductsStores.id', 'ProductsStores.company_id', 'ProductsStores.section_id', 'ProductsStores.category_id', 'ProductsStores.description', 'ProductsStores.ean13', 'ProductsStores.internal_code'])
                                    ->where([$products_cond_arr])
                                    ->matching('Sections', function ($q){
                                        //return $q->where(['Articles.created >=' => new DateTime('-10 days')]);

                                        return $q
                                            ->where([
                                                'Sections.enabled' => 1
                                            ]);                       
                                            //->limit(1);
                                    });
                            },
                            'Sections' => [
                                'queryBuilder' => function (\Cake\ORM\Query $query) {
                                    return $query
                                        ->select([
                                            'Sections.id',
                                            'Sections.section_name',
                                            'Sections.section_code',
                                        ]);
                                }
                            ],
                            'Categories' => [
                                'queryBuilder' => function (\Cake\ORM\Query $query) {
                                    return $query
                                        ->select([
                                            'Categories.id',
                                            'Categories.category_name',
                                            'Categories.category_code',
                                        ]);
                                }
                            ],
                            /*'StockUpdates' => [
                                
                            ]*/
                        ]
                    ])
                    ->select([
                        'CatalogUpdates.id', 'CatalogUpdates.product_store_id', 'CatalogUpdates.store_id', 'CatalogUpdates.enabled', 'CatalogUpdates.cataloged', 'CatalogUpdates.catalog_date', 'CatalogUpdates.seen', 'CatalogUpdates.stock'
                    ])
                    ->where([
                        'DATE(CatalogUpdates.catalog_date)' => $catalog_date_query,
                        'CatalogUpdates.store_id' => $store_id,
                        'CatalogUpdates.seen' => 0,
                        'CatalogUpdates.enabled' => 1,
                        'CatalogUpdates.cataloged' => 1
                    ])
                    ->group('CatalogUpdates.product_store_id')
                    ->order([
                        'CatalogUpdates.catalog_date' => 'DESC'
                    ])
                    //->limit(1000)
                    ->toArray();
                    //->cache('assortment_query_'.$dates['global']['end_date']['master_date']->format('Y-m-d').'_'.$store->store_code.'_'.$this->request->data('section_id').($this->request->data('category_id') != '') ? '_'.$this->request->data('category_id') : '', 'config_cache_query');
            }

            $count_not_readed_products = 0;

            $not_readed_products = [
                'products' => []
            ];

            if(count($catalogs) > 0){


                if($robot_session->store->company->company_keyword == 'jumbo'){

                    $readed_products = $this->ProductsStores->CatalogUpdates->find('all')
                        ->contain([
                            'ProductsStores' => [
                                'queryBuilder' => function (\Cake\ORM\Query $query) use($products_cond_arr, $store_id, $catalog_date_query) {
                                    return $query
                                        ->select(['ProductsStores.id', 'ProductsStores.company_id', 'ProductsStores.section_id', 'ProductsStores.category_id', 'ProductsStores.description', 'ProductsStores.ean13', 'ProductsStores.internal_code'])
                                        ->where([$products_cond_arr])
                                        /*->matching('StockUpdates', function ($q) use($store_id, $catalog_date_query){
                                            //return $q->where(['Articles.created >=' => new DateTime('-10 days')]);

                                            return $q
                                                ->select([
                                                    'StockUpdates.id',
                                                    'StockUpdates.product_store_id',
                                                    'StockUpdates.current_stock',
                                                    //'StockUpdates.stock_updated'
                                                ])
                                                ->where([
                                                    'StockUpdates.store_id' => $store_id,
                                                    'DATE(StockUpdates.stock_updated) <=' => $catalog_date_query,
                                                    'StockUpdates.current_stock >' => 0
                                                ])                             
                                                ->order(['StockUpdates.stock_updated' => 'DESC']);
                                                //->limit(1);
                                        })*/
                                        ->matching('Sections', function ($q){
                                            //return $q->where(['Articles.created >=' => new DateTime('-10 days')]);

                                            return $q
                                                ->where([
                                                    'Sections.enabled' => 1
                                                ]);                       
                                                //->limit(1);
                                        });
                                },
                                'Sections' => [
                                    'queryBuilder' => function (\Cake\ORM\Query $query) {
                                        return $query
                                            ->select([
                                                'Sections.id',
                                                'Sections.section_name',
                                                'Sections.section_code',
                                            ]);
                                    }
                                ],
                                'Categories' => [
                                    'queryBuilder' => function (\Cake\ORM\Query $query) {
                                        return $query
                                            ->select([
                                                'Categories.id',
                                                'Categories.category_name',
                                                'Categories.category_code',
                                            ]);
                                    }
                                ],
                                /*'StockUpdates' => [
                                    
                                ]*/
                            ]
                        ])
                        ->group('CatalogUpdates.product_store_id')
                        ->where([
                            'DATE(CatalogUpdates.catalog_date)' => $catalog_date_query,
                            'CatalogUpdates.store_id' => $store_id,
                            'CatalogUpdates.seen' => 1,
                            'CatalogUpdates.enabled' => 1,
                            'CatalogUpdates.cataloged' => 1,
                            'CatalogUpdates.stock >' => 0
                        ])
                        ->count();
                }
                else{
                    $readed_products = $this->ProductsStores->CatalogUpdates->find('all')
                        ->contain([
                            'ProductsStores' => [
                                'queryBuilder' => function (\Cake\ORM\Query $query) use($products_cond_arr, $store_id, $catalog_date_query) {
                                    return $query
                                        ->select(['ProductsStores.id', 'ProductsStores.company_id', 'ProductsStores.section_id', 'ProductsStores.category_id', 'ProductsStores.description', 'ProductsStores.ean13', 'ProductsStores.internal_code'])
                                        ->where([$products_cond_arr])
                                        ->matching('Sections', function ($q){
                                            //return $q->where(['Articles.created >=' => new DateTime('-10 days')]);

                                            return $q
                                                ->where([
                                                    'Sections.enabled' => 1
                                                ]);                       
                                                //->limit(1);
                                        });
                                },
                                'Sections' => [
                                    'queryBuilder' => function (\Cake\ORM\Query $query) {
                                        return $query
                                            ->select([
                                                'Sections.id',
                                                'Sections.section_name',
                                                'Sections.section_code',
                                            ]);
                                    }
                                ],
                                'Categories' => [
                                    'queryBuilder' => function (\Cake\ORM\Query $query) {
                                        return $query
                                            ->select([
                                                'Categories.id',
                                                'Categories.category_name',
                                                'Categories.category_code',
                                            ]);
                                    }
                                ],
                                /*'StockUpdates' => [
                                    
                                ]*/
                            ]
                        ])
                        ->group('CatalogUpdates.product_store_id')
                        ->where([
                            'DATE(CatalogUpdates.catalog_date)' => $catalog_date_query,
                            'CatalogUpdates.store_id' => $store_id,
                            'CatalogUpdates.seen' => 1,
                            'CatalogUpdates.enabled' => 1,
                            'CatalogUpdates.cataloged' => 1
                        ])
                        ->count();
                }

                $x=0;

                $inv_path = ROOT . DIRECTORY_SEPARATOR . 'webroot'. DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . 'labels' .  DIRECTORY_SEPARATOR . $robot_session->store->store_code.'-'.$catalog_date_query.'-'.(($section != null) ? $section->id : '-all').(($category != null) ? '-'.$category->id : '').'-assortment.inv';

                if(file_exists($inv_path)){
                    unlink($inv_path);
                }

                $eanHelper = new EanHelper(new \Cake\View\View());
                $fp = fopen($inv_path, 'w');

                foreach($catalogs as $catalog){

                    if($robot_session->store->company->company_keyword == 'jumbo'){

                        if(count($catalog->products_store->stock_updates) > 0){

                            if($catalog->products_store->stock_updates[0]->current_stock <= 0){
                                continue;
                            }
                        }
                    }

                    if($catalog->enabled == 0){
                        continue;
                    }

                    $count_not_readed_products = $count_not_readed_products + 1;


                    //Archivo INV
                    //$code_length = strlen($x);
                    $code_id = '';

                    for($z=0; $z < (6 - strlen($x)); $z++){
                        $code_id .= '0';
                    }
                    $code_id .= $x;

                    $ean_full = $eanHelper->format($catalog->products_store->ean13);
                    $cant_flejes = "00001";
                    $tipo_fleje = "00911";
                    $ubicacion = "000000";

                    $full_code = $code_id."0".$ean_full."0".$cant_flejes."0".$tipo_fleje."0".$ubicacion."001S\r\n";

                    fwrite($fp, $full_code);



                    //To report list
                    $not_readed_products['products'][($catalog->products_store->section != null) ? $catalog->products_store->section->section_name : __('Unknown section')]['data'][($catalog->products_store->category != null) ? $catalog->products_store->category->category_name : __('Unknown category')]['data'][$catalog->products_store->internal_code]['id'] = $catalog->products_store->id;

                    
                    $not_readed_products['products'][($catalog->products_store->section != null) ? $catalog->products_store->section->section_name : __('Unknown section')]['data'][($catalog->products_store->category != null) ? $catalog->products_store->category->category_name : __('Unknown category')]['data'][$catalog->products_store->internal_code]['ean13'] = $catalog->products_store->ean13;

                    $not_readed_products['products'][($catalog->products_store->section != null) ? $catalog->products_store->section->section_name : __('Unknown section')]['data'][($catalog->products_store->category != null) ? $catalog->products_store->category->category_name : __('Unknown category')]['data'][$catalog->products_store->internal_code]['description'] = $catalog->products_store->description;

                    $not_readed_products['products'][($catalog->products_store->section != null) ? $catalog->products_store->section->section_name : __('Unknown section')]['data'][($catalog->products_store->category != null) ? $catalog->products_store->category->category_name : __('Unknown category')]['data'][$catalog->products_store->internal_code]['internal_code'] = $catalog->products_store->internal_code;

                    $not_readed_products['products'][($catalog->products_store->section != null) ? $catalog->products_store->section->section_name : __('Unknown section')]['data'][($catalog->products_store->category != null) ? $catalog->products_store->category->category_name : __('Unknown category')]['data'][$catalog->products_store->internal_code]['stock'] = ($catalog->stock > 0) ? $catalog->stock : null;

                    if($catalog->products_store->section != null && !isset($not_readed_products['products'][($catalog->products_store->section != null) ? $catalog->products_store->section->section_name : __('Unknown section')]['section_data'])){

                        $not_readed_products['products'][($catalog->products_store->section != null) ? $catalog->products_store->section->section_name : __('Unknown section')]['section_data']['section_name'] = $catalog->products_store->section->section_name;

                        $not_readed_products['products'][($catalog->products_store->section != null) ? $catalog->products_store->section->section_name : __('Unknown section')]['section_data']['section_code'] = $catalog->products_store->section->section_code;
                    }

                    if($catalog->products_store->category != null && !isset($not_readed_products['products'][($catalog->products_store->section != null) ? $catalog->products_store->section->section_name : __('Unknown section')]['data'][($catalog->products_store->category != null) ? $catalog->products_store->category->category_name : __('Unknown category')]['category_data'])){

                        $not_readed_products['products'][($catalog->products_store->section != null) ? $catalog->products_store->section->section_name : __('Unknown section')]['data'][($catalog->products_store->category != null) ? $catalog->products_store->category->category_name : __('Unknown category')]['category_data']['category_name'] = $catalog->products_store->category->category_name;

                        $not_readed_products['products'][($catalog->products_store->section != null) ? $catalog->products_store->section->section_name : __('Unknown section')]['data'][($catalog->products_store->category != null) ? $catalog->products_store->category->category_name : __('Unknown category')]['category_data']['category_code'] = $catalog->products_store->category->category_code;
                    }

                    $x++;
                }

                //Cerrar .inv
                fclose($fp);

                $barcode = new BarcodeGeneratorPNG();
                $this->set('barcode', $barcode);

                $this->set('section', $section);
                $this->set('category', $category);
                $this->set('catalog_date_query', $catalog_date_query);
                $this->set('readed_products', $readed_products);
                $this->set('not_readed_products_count', $count_not_readed_products);
            
                $this->set('section_id', $this->request->data('section_id'));
                $this->set('category_id', $this->request->data('category_id'));
                
            }


            $this->set('not_readed_products', $not_readed_products);
            $this->set('robot_session', $robot_session);
            $this->set('catalog_date', $catalog_date);
        }
    }

    function doPriceDifferenceReport(){
         if($this->request->is('post')){

            if($this->request->data('company_id') == null || $this->request->data('store_id') == null || $this->request->data('session_date') == null || $this->request->data('robot_session_id') == null){
                $response = new \stdClass();
                $response->status = false;
                $response->message = '';
                $response->data = [];
                $response->error = __('Invalid Params.');

                $this->response->type('json');
                $this->response->body(json_encode($response));

                return $this->response;
            }

            $store_data = $this->Stores->find('all')->contain(['Companies'])->where(['Stores.id' => $this->request->data('store_id')])->first();
            $store_id = $store_data->id;

            $robot_session_data = $this->Stores->RobotSessions->get($this->request->data('robot_session_id'));

            if(($report_view = Cache::read('element_price_difference_report_view_'.$this->request->data('company_id').$this->request->data('store_id').$robot_session_data->session_code, 'config_cache_report')) === false) {

                $session_date = $robot_session_data->session_date;
                $data = [];

                $robot_session = $this->Stores->RobotSessions->find('all')
                    ->contain([
                        'Detections' => [
                            'queryBuilder' => function (\Cake\ORM\Query $query) {
                                return $query
                                    ->where(['Detections.price_difference_alert' => 1])
                                    ->order(['Detections.location_x' => 'ASC', 'Detections.location_z' => 'ASC']);
                                    //->select(['ProductsStores.id', 'ProductsStores.section_id', 'ProductsStores.category_id', 'ProductsStores.product_state_id', 'ProductsStores.description', 'ProductsStores.ean13', 'ProductsStores.internal_code'])
                                    //->where([$products_cond_arr]);
                            },
                            'ProductsStores' => [
                                'queryBuilder' => function (\Cake\ORM\Query $query) {
                                    return $query
                                        ->select(['ProductsStores.id', 'ProductsStores.section_id', 'ProductsStores.category_id', 'ProductsStores.description', 'ProductsStores.ean13', 'ProductsStores.internal_code']);
                                },
                                /*'LastPrice' => [
                                    'fields' => [
                                        'PriceUpdates.id',
                                        'PriceUpdates.company_updated',
                                        'PriceUpdates.store_id'
                                    ],
                                    'conditions' => [
                                        'PriceUpdates.company_updated <=' => $session_date,
                                        'PriceUpdates.store_id' => $store_id,
                                    ],
                                ],*/
                                /*'PriceUpdates' => [
                                    'queryBuilder' => function (\Cake\ORM\Query $query) use($store_id, $session_date){
                                        return $query
                                            //->limit(2)
                                            ->where([
                                                'PriceUpdates.company_updated <=' => $session_date,
                                                'PriceUpdates.store_id' => $store_id
                                            ])
                                            ->order([
                                                'PriceUpdates.company_updated' => 'DESC',
                                                'PriceUpdates.created' => 'DESC'
                                            ]);
                                    }
                                ],*/
                                'DealUpdates' => [
                                    'queryBuilder' => function (\Cake\ORM\Query $query) use($session_date, $store_id){
                                        return $query
                                            ->select(['DealUpdates.id', 'DealUpdates.product_store_id'])                                    
                                            ->where([
                                                'DealUpdates.store_id' => $store_id,
                                                'DealUpdates.start_date <=' => $session_date,
                                                'DealUpdates.end_date >=' => $session_date
                                            ]);
                                            //->limit(1);
                                    },
                                ],
                                'Sections' => [
                                    'queryBuilder' => function (\Cake\ORM\Query $query) {
                                        return $query
                                            ->select([
                                                'Sections.id',
                                                'Sections.section_name',
                                                'Sections.section_code',
                                            ]);
                                    }
                                ],
                                //'DealUpdates'
                            ],
                            'Aisles'
                        ]
                    ])
                    ->where([
                        'RobotSessions.id' => $robot_session_data->id,
                        'RobotSessions.store_id' => $store_id,
                        'RobotSessions.price_differences_labels_finished' => 1,
                        'RobotSessions.price_differences_labels_processing' => 0
                    ])
                    ->first();
                    //->cache('price_difference_query_'.$this->request->data('session_date').'_'.$store_data->store_code, 'config_cache_query');

                /*echo '<pre>';
                print_r($robot_session);
                echo '</pre>';
                
                die();*/


                if(isset($robot_session->detections) && count($robot_session->detections) > 0){

                    $data = [
                        'stats' => [
                            'total_detections' => $robot_session->total_detections,
                            'total_detections_differences' => 0,
                            'total_products' => 0,
                            'total_products_differences' => 0,
                            'total_products_without_price' => 0,
                            'total_differences' => 0,
                            'total_labels_with_deal' => 0
                        ],
                        'products' => [

                        ]
                    ];
                    
                    ini_set('memory_limit','512M');
                    $last_30_days_date = New Time($session_date);
                    $last_30_days_date->modify('-30 days');

                    foreach($robot_session->detections as $detection){

                        /*echo '<pre>';
                        print_r($detection->products_store);
                        echo '</pre>';*/
                        //Si no tiene oferta
                        //if(count($detection->products_store->deal_updates) == 0){

                            //Si tiene precio
                            //if(count($detection->products_store->price_updates) > 0){
                            
                                //foreach($detection->products_store->price_updates as $price_update){

                                    //if($detection->products_store->price_updates[0]->price != $detection->label_price && !is_null($detection->label_price)){


                                        if(count($detection->products_store->deal_updates) == 0){

                                            $has_offer = 0;
                                                
                                            if($store_data->company->company_keyword == 'jumbo'){
                                                //Query para corroborar y buscar todos los productos x sap y verificar que no tengan oferta
                                                $sap_products = $this->ProductsStores->find()
                                                    ->contain([
                                                        'DealUpdates' => [
                                                            'queryBuilder' => function (\Cake\ORM\Query $query) use($session_date, $store_id){
                                                                return $query
                                                                    ->select(['DealUpdates.id', 'DealUpdates.product_store_id'])                                    
                                                                    ->where([
                                                                        'DealUpdates.store_id' => $store_id,
                                                                        'DealUpdates.start_date <=' => $session_date,
                                                                        'DealUpdates.end_date >=' => $session_date
                                                                    ]);
                                                                    //->limit(1);
                                                            },
                                                        ],
                                                    ])
                                                    ->select(['ProductsStores.id, ProductsStores.company_id', 'ProductsStores.internal_code'])
                                                    ->where([
                                                        'ProductsStores.internal_code' => $detection->products_store->internal_code,
                                                        'ProductsStores.company_id' => $store_data->company_id
                                                    ])
                                                    ->toArray();

                                                if(count($sap_products) > 0){
                                                    foreach ($sap_products as $product) {
                                                        if(count($product->deal_updates) > 0){
                                                            $has_offer = 1;
                                                            break;
                                                        }
                                                    }
                                                }
                                            }

                                            if($has_offer == 0){
                                                $data['stats']['total_detections_differences'] = $data['stats']['total_detections_differences'] + 1;

                                                $robot_sessions_list = $this->Stores->RobotSessions->find('list', [
                                                    'keyField' => 'id',
                                                    'valueField' => 'id',
                                                    'conditions' => [
                                                        'RobotSessions.store_id' => $store_data->id,
                                                        'RobotSessions.price_differences_labels_finished' => 1,
                                                        'RobotSessions.price_differences_labels_processing' => 0,
                                                        'RobotSessions.session_date <' => $robot_session->session_date,
                                                        'RobotSessions.session_date >=' => $last_30_days_date,
                                                    ]
                                                ])
                                                ->toArray();

                                                //print_r($robot_sessions_list);

                                                if(count($robot_sessions_list) > 0){
                                                    $quantity_alerts = $this->Stores->RobotSessions->Detections->find('all')
                                                        ->where([
                                                            'Detections.price_difference_alert' => 1,
                                                            'Detections.product_store_id' => $detection->products_store->id,
                                                            'Detections.robot_session_id IN' => $robot_sessions_list
                                                        ])
                                                        ->count();
                                                }
                                                else{
                                                    $quantity_alerts = 0;
                                                }

                                                if(!is_null($detection->products_store->section_id)){

                                                    //echo 'aqui hay con seccion<br>';

                                                    if(!isset($data['products'][$detection->products_store->section_id]['section'])){
                                                        $data['products'][$detection->products_store->section_id]['section'] = [
                                                            'id' => $detection->products_store->section->id,
                                                            'section_name' => $detection->products_store->section->section_name,
                                                            'section_code' => $detection->products_store->section->section_code,
                                                            'count_labels' => 0,
                                                            'count_products' => 0
                                                        ];
                                                    }
                                                    /*else{
                                                        $data['products'][$detection->products_store->section_id]['section']['count_labels'] = $data['products'][$detection->products_store->section_id]['section']['count_labels'] + 1;
                                                    }*/

                                                    if(!isset($data['products'][$detection->products_store->section_id]['data'][$detection->aisle->aisle_number][$detection->products_store->internal_code])){
                                                        $data['products'][$detection->products_store->section_id]['data'][$detection->aisle->aisle_number][$detection->products_store->internal_code] = [
                                                            'ean13' => $detection->products_store->ean13,
                                                            'internal_code' => $detection->products_store->internal_code,
                                                            'description' => $detection->products_store->description,
                                                        ];

                                                        $data['stats']['total_products'] = $data['stats']['total_products'] + 1;
                                                        $data['products'][$detection->products_store->section_id]['section']['count_labels'] = $data['products'][$detection->products_store->section_id]['section']['count_products'] + 1;
                                                    }

                                                    $data['products'][$detection->products_store->section_id]['data'][$detection->aisle->aisle_number][$detection->products_store->internal_code]['detections'][] = [
                                                        'detection_code' => $detection->detection_code,
                                                        'label_price' => $detection->label_price,
                                                        'location_x' => $detection->location_x,
                                                        'location_y' => $detection->location_y,
                                                        'location_z' => $detection->location_z,
                                                        'aisle' => $detection->aisle->aisle_number
                                                    ];

                                                    $data['products'][$detection->products_store->section_id]['data'][$detection->aisle->aisle_number][$detection->products_store->internal_code]['price_update'] = [
                                                        'price' => $detection->price_pos,
                                                        'previous_price' => null,
                                                        'company_updated' => $detection->price_update,
                                                        'days_with_difference' => $this->getWorkingDays($detection->price_update->format('Y-m-d'), $robot_session->session_date->format('Y-m-d')),
                                                        '30_days_alerts' => $quantity_alerts
                                                    ];
                                                }
                                                else{
                                                    //echo 'aqui hay sin seccion<br>';
                                                    if(!isset($data['products'][__('Unknown')]['section'])){
                                                        $data['products'][__('Unknown')]['section'] = [
                                                            'id' => __('Unknown'),
                                                            //'id' => $detection->products_store->section->id,
                                                            'section_name' => __('Unknown'),
                                                            'section_code' => __('Unknown')
                                                        ];
                                                    }

                                                    if(!isset($data['products'][__('Unknown')]['data'][$detection->aisle->aisle_number][$detection->products_store->internal_code])){
                                                        $data['products'][__('Unknown')]['data'][$detection->aisle->aisle_number][$detection->products_store->internal_code] = [
                                                            'ean13' => $detection->products_store->ean13,
                                                            'internal_code' => $detection->products_store->internal_code,
                                                            'description' => $detection->products_store->description,
                                                        ];
                                                    }

                                                    $data['products'][__('Unknown')]['data'][$detection->aisle->aisle_number][$detection->products_store->internal_code]['detections'][] = [
                                                        'detection_code' => $detection->detection_code,
                                                        'label_price' => $detection->label_price,
                                                        'location_x' => $detection->location_x,
                                                        'location_y' => $detection->location_y,
                                                        'location_z' => $detection->location_z,
                                                    ];

                                                    $data['products'][__('Unknown')]['data'][$detection->aisle->aisle_number][$detection->products_store->internal_code]['price_update'] = [
                                                        'price' => $detection->price_pos,
                                                        'previous_price' => null,
                                                        'company_updated' => $detection->price_update,
                                                        'days_with_difference' => $this->getWorkingDays($detection->price_update->format('Y-m-d'), $robot_session->session_date->format('Y-m-d')),
                                                        '30_days_alerts' => $quantity_alerts
                                                    ];
                                                }
                                            }
                                            else{
                                                $data['stats']['total_labels_with_deal'] = $data['stats']['total_labels_with_deal'] + 1;
                                            }
                                        }
                                        else{
                                            $data['stats']['total_labels_with_deal'] = $data['stats']['total_labels_with_deal'] + 1;
                                        }

                                        
                                    //}
                                    
                                //}
                            /*}
                            else{
                                //echo 'aqui no hay precio<br>';
                                $data['stats']['total_products_without_price'] = $data['stats']['total_products_without_price'] + 1;
                            }*/
                        //}
                        /*else{
                            $data['stats']['total_products_with_deal'] = $data['stats']['total_products_with_deal'] + 1;
                        }*/

                        //$data['stats']['total_detections'] = $data['stats']['total_detections'] + 1;
                    }

                    ksort($data['products']);

                    foreach($data['products'] as $section_id => $info){

                        ksort($data['products'][$section_id]['data']);

                        $total_products = 0;
                        $total_detections = 0;

                        foreach($info['data'] as $aisle_number => $products){
                            
                            $total_products += count($products);

                            foreach($products as $product){
                                $total_detections += count($product['detections']);
                            }
                        }

                        $data['products'][$section_id]['section']['count_products'] = $total_products;
                        $data['products'][$section_id]['section']['count_labels'] = $total_detections;
                    }

                    /*if($robot_session->total_price_difference_detections == null){
                        $robot_session->total_price_difference_detections = $data['stats']['total_detections_differences'];
                        $this->RobotSessions->save($robot_session);
                    }
                    else{
                        if($data['stats']['total_detections_differences'] != $robot_session->total_price_difference_detections){
                            $robot_session->total_price_difference_detections = $data['stats']['total_detections_differences'];
                            $this->RobotSessions->save($robot_session);
                        }
                    }

                    if($robot_session->total_price_difference_products == null){
                        $robot_session->total_price_difference_products = $data['stats']['total_products'];
                        $this->RobotSessions->save($robot_session);
                    }
                    else{
                        if($data['stats']['total_products'] != $robot_session->total_price_difference_products){
                            $robot_session->total_price_difference_products = $data['stats']['total_products'];
                            $this->RobotSessions->save($robot_session);
                        }
                    }*/

                    $barcode = new BarcodeGeneratorPNG();
                    
                    /*if(count($data) > 0){

                        $pdf_path = ROOT . DIRECTORY_SEPARATOR . 'webroot'. DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . 'pdfs' .  DIRECTORY_SEPARATOR . 'price_difference_'.$store_data->company->company_keyword.'_'.$store_data->store_code.'_'.$robot_session->session_code.'.pdf';

                        if(file_exists($pdf_path)){
                            unlink($pdf_path);
                        }


                        $CakePdf = new \CakePdf\Pdf\CakePdf();
                        $CakePdf->template('price_difference', 'default');
                        $CakePdf->viewVars(['barcode' => $barcode, 'store_data' => $store_data, 'data' => $data, 'session_date' => $robot_session->session_date, 'robot_session' => $robot_session]);
                        // Get the PDF string returned
                        //$pdf = $CakePdf->output();
                        // Or write it to file directly
                        $pdf = $CakePdf->write($pdf_path);
                    }*/

                    //789802439686

                    $this->set('barcode', $barcode);
                    $this->set('robot_session_id', $robot_session->id);
                    $this->set('robot_session', $robot_session);
                }
                
                $this->set('session_code', $robot_session->session_code);
                $this->set('data', $data);
                $this->set('store_data', $store_data);

                    
                //$barcode_code = $this->Ean->format($data['products'][224]['data'][1644932]['ean13']);
                //$barcode_html = $barcode->getBarcode($barcode_code, $barcode::TYPE_EAN_13, 1);

                /*echo '<pre>';
                print_r($robot_session);
                echo '</pre>';*/
                //die();
            }
            else{
                
                $this->autoRender = false;
                echo $report_view;
                error_reporting(E_ERROR | E_PARSE);
            }
        }
    }

    function getWorkingDays($startDate, $endDate){
        $startDate = strtotime($startDate);
        $endDate = strtotime($endDate);

        if($startDate <= $endDate){
            $datediff = $endDate - $startDate;
            return floor($datediff / (60 * 60 * 24));
        }

        return false;
    }


    function doPriceDifferenceReportByApi(){
         if($this->request->is('post')){

            if($this->request->data('company_id') == null || $this->request->data('store_id') == null || $this->request->data('session_date') == null){
                $response = new \stdClass();
                $response->status = false;
                $response->message = '';
                $response->data = [];
                $response->error = __('Invalid Params.');

                $this->response->type('json');
                $this->response->body(json_encode($response));

                return $this->response;
            }

            $store_data = $this->Stores->find('all')->contain(['Companies'])->where(['Stores.id' => $this->request->data('store_id')])->first();

            $report = New RobotReportsMain();
            $session_id = $report->getSessionCode($store_data->store_code, $this->request->data('session_date'));

            //Validar si es acutalizacion o lectura
            $update = false;

            $pdf_path = ROOT . DIRECTORY_SEPARATOR . 'webroot'. DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . 'pdfs' .  DIRECTORY_SEPARATOR . 'price_difference_'.$store_data->company_id.$store_data->id.$session_id.'.pdf';
            
            if($this->request->data('update') == 1){
                $update = true;
                Cache::delete('element_price_difference_report_view_'.$this->request->data('company_id').$this->request->data('store_id').$session_id, 'config_cache_report');

                if(file_exists($pdf_path)){
                    unlink($pdf_path);
                }
            }

            if (($report_view = Cache::read('element_price_difference_report_view_'.$this->request->data('company_id').$this->request->data('store_id').$session_id, 'config_cache_report')) === false) {

                $sections = $this->Sections->find('all')->select(['Sections.section_code', 'Sections.section_name'])->where(['Sections.company_id' => $store_data->company->id, 'Sections.section_name <>' => ''])->toArray();
                
                $session_date = New Time(substr($session_id, 0, 4).'-'.substr($session_id, 4, 2).'-'.substr($session_id, 6, 2).' '.substr($session_id, 8, 2).':'.substr($session_id, 10, 2).':00');
                $master_date = New Time(substr($session_id, 0, 4).'-'.substr($session_id, 4, 2).'-'.substr($session_id, 6, 2));
                $master_date->modify('-1 days');

                $products_differences = [];

                $x = 0;
                $exist = false;


                /*if($store_data->store_code != 'HC67'){
                    $section_code = intval($section->section_code);
                }
                else{
                    $section_code = $section->section_code;
                }*/

                $products = $this->getPriceDifferences($store_data->store_code,  $session_id, $update);

                //$products_differences[$section->section_name]['data'] = [];
                if(is_array($products) && count($products) > 0){
                    foreach($products as $product){
                        $products_differences[$product['category0']]['data'][$product['item']] = $product;
                    }

                    $exist = true;
                }

                foreach ($sections as $section) {

                    if($store_data->store_code != 'HC67'){
                        $section_code = intval($section->section_code);
                    }
                    else{
                        $section_code = $section->section_code;
                    }

                    if(isset($products_differences[$section_code])){
                        $products_differences[$section_code]['section'] = $section;
                    }
                }

                ksort($products);
                


                /*foreach ($sections as $section) {

                    if($store_data->store_code != 'HC67'){
                        $section_code = intval($section->section_code);
                    }
                    else{
                        $section_code = $section->section_code;
                    }

                    $products = $this->getPriceDifferences($store_data->store_code,  $session_id, $section_code, $update);
                    $products_differences[$section->section_name]['section_code'] = $section->section_code;

                    $products_differences[$section->section_name]['data'] = [];
                    if(is_array($products) && count($products) > 0){
                        foreach($products as $product){
                            $products_differences[$section->section_name]['data'][$product['item']] = $product;
                        }

                        $exist = true;
                    }

                    

                    //When
                    /*if(count($products) > 0){
                        $products_differences[$section->section_name]['data'] = $products;

                        for($x=0; $x < count($products_differences[$section->section_name]['data']); $x++){

                            $master_product = $this->ProductsStores->find('all')
                                ->select([
                                    'ProductsStores.id',
                                    'ProductsStores.company_internal_code', 
                                    'ProductsStores.company_update',
                                    'ProductsStores.master_catalog_date'
                                ])
                                ->where([
                                    'ProductsStores.master_catalog_date <=' => $master_date,
                                    'ProductsStores.company_update IS NOT NULL',
                                    'ProductsStores.company_internal_code' => intval($products_differences[$section->section_name]['data'][$x]['item']),
                                    'ProductsStores.store_id' => $store_data->id,
                                    'ProductsStores.company_id' => $store_data->company->id
                                ])
                                ->order([
                                    'ProductsStores.company_update' => 'DESC'
                                ])
                                ->first();

                            if($master_product != null){
                                $products_differences[$section->section_name]['data'][$x]['last_update'] = new Time($master_product->company_update);

                                $interval = $master_product->company_update->diff($session_date);
                                $products_differences[$section->section_name]['data'][$x]['days_with_difference'] = $interval->format('%R%a días');
                                $products_differences[$section->section_name]['data'][$x]['last_update_format'] = $products_differences[$section->section_name]['data'][$x]['last_update']->format('Y-m-d H:i:s');
                            }
                        }
                    }

                    
                }*/

                $barcode = new BarcodeGeneratorPNG();

        
                $zippedi_images = [];
                foreach($products_differences as $section_name => $info){
                    if(count($info['data']) > 0 && isset($info['data'])){
                        foreach($info['data'] as $product){
                            
                            $zippedi_images[$product['detection_id']]['sap_image_base64'] = $report->getLabelCrop($store_data->store_code, $session_id, $product['detection_id'], 'sap', 'base64');
                            $zippedi_images[$product['detection_id']]['price_image_base64'] = $report->getLabelCrop($store_data->store_code, $session_id, $product['detection_id'], 'price', 'base64');
                        }
                    }
                }
                

                $CakePdf = new \CakePdf\Pdf\CakePdf();
                $CakePdf->template('price_difference', 'default');
                $CakePdf->viewVars(['barcode' => $barcode, 'store_data' => $store_data, 'products_differences' => $products_differences, 'session_code' => $session_id, 'session_date' => $session_date, 'zippedi_images' => $zippedi_images]);
                // Get the PDF string returned
                //$pdf = $CakePdf->output();
                // Or write it to file directly
                $pdf = $CakePdf->write($pdf_path); 
                
                $this->set('barcode', $barcode);
                $this->set('store_data', $store_data);
                $this->set('products_differences', $products_differences);
                $this->set('zippedi_images', $zippedi_images);
                $this->set('session_code', $session_id);
                $this->set('session_date',  $session_date);
                $this->set('exist',  $exist);
            }
            else{
                
                printf($report_view);

                $this->autoRender = false;
                
            }
        }
    }

    public function getPriceDifferences($supermarket_code = null, $session_code = null, $update = false){

        if($session_code == null){
            return false;
        }

        if($supermarket_code == null){
            return false;
        }

        $store_data = $this->Stores->find('all')
            ->select(['Stores.id', 'Stores.company_id','Stores.store_code'])
            ->where(['Stores.store_code' => $supermarket_code])
            ->first();

        if($store_data == null){
            $response = new \stdClass();
            $response->status = false;
            $response->error = __('Store not found');

            $this->response->type('json');
            $this->response->body(json_encode($response));

            return $this->response;
        }

        
        $json_route = ROOT . DIRECTORY_SEPARATOR . 'logs'. DIRECTORY_SEPARATOR .'price_differences_'.$store_data->company_id.$store_data->id.$session_code.'.json';

        if(file_exists($json_route) && $update == false){
            $string = file_get_contents($json_route);
            $arr = json_decode($string, true);
            return $arr;
        }

        $auth_data = array(
            'username' => 'zippedi',
            'password' => 'YuL1fcfQQIwFL6Es2Zx'
        );

        $http = new Client();
        $request_token = $http->post($this->endpoint.'/auth', json_encode($auth_data), ['type' => 'json']);

        $http = new Client([
            'headers' => ['Authorization' => 'jwt ' . $request_token->json['access_token']]
        ]);

        $url = $this->endpoint.'/results/price_differences';


        $robot_response = $http->get($url, ['store' => $supermarket_code, 'session' => $session_code, 'source' => 'q']);

        if($robot_response->getStatusCode() != 200){

            $response = new \stdClass();
            $response->status = false;
            $response->error = __('Error '.$robot_response->code);

            $this->response->type('json');
            $this->response->body(json_encode($response));

            return $this->response;
        }

        $arr = [];
        
        if(isset($robot_response->json[0]['message'])){
            return $arr;
        }
        else{

            $arr = json_decode($robot_response->json, true);

            if(file_exists($json_route) && $update == true){
                unlink($json_route);
            }
                    
            $fp = fopen($json_route, 'w');
            fwrite($fp, json_encode($arr, JSON_PRETTY_PRINT));
            fclose($fp);

        }

        return $arr;
    }

    function doStockOutReport(){

        if($this->request->is('post')){

            if($this->request->data('company_id') == null || $this->request->data('store_id') == null || $this->request->data('session_date') == null || $this->request->data('robot_session_id') == null){
                $response = new \stdClass();
                $response->status = false;
                $response->message = '';
                $response->data = [];
                $response->error = __('Invalid Params.');

                $this->response->type('json');
                $this->response->body(json_encode($response));

                return $this->response;
            }

            $store_data = $this->Stores->find('all')->contain(['Companies'])->where(['Stores.id' => $this->request->data('store_id')])->first();
            $store_id = $store_data->id;
            //$report = New RobotReportsMain();
            //$session_id = $report->getSessionCode($store_data->store_code, $this->request->data('session_date'));

            //Validar si es acutalizacion o lectura
            /*$update = false;

            $pdf_path = ROOT . DIRECTORY_SEPARATOR . 'webroot'. DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . 'pdfs' .  DIRECTORY_SEPARATOR . 'price_difference_'.$store_data->company_id.$store_data->id.$session_id.'.pdf';
            
            if($this->request->data('update') == 1){
                $update = true;
                Cache::delete('element_price_difference_report_view_'.$this->request->data('company_id').$this->request->data('store_id').$session_id, 'config_cache_report');

                if(file_exists($pdf_path)){
                    unlink($pdf_path);
                }
            }*/

            $robot_session_data = $this->Stores->RobotSessions->get($this->request->data('robot_session_id'));
            $session_date = $robot_session_data->session_date;

            $last_30_days_date = $robot_session_data->session_date;
            $catalog_date = $robot_session_data->session_date;
            $catalog_date->modify('-1 days');
            $last_30_days_date->modify('-30 days');

            $catalog_date_query = $catalog_date->format('Y-m-d');

            $data = [];

            $robot_session = $this->Stores->RobotSessions->find('all')
                ->contain([
                    'Detections' => [
                        'queryBuilder' => function (\Cake\ORM\Query $query) {
                            return $query
                                ->where(['Detections.stock_alert' => 1])
                                ->order(['Detections.location_x' => 'ASC']);
                                //->limit(100);
                                //->select(['ProductsStores.id', 'ProductsStores.section_id', 'ProductsStores.category_id', 'ProductsStores.product_state_id', 'ProductsStores.description', 'ProductsStores.ean13', 'ProductsStores.internal_code'])
                                //->where([$products_cond_arr]);
                        },
                        'ProductsStores' => [
                            'queryBuilder' => function (\Cake\ORM\Query $query) {
                                return $query
                                    ->select(['ProductsStores.id', 'ProductsStores.section_id', 'ProductsStores.category_id', 'ProductsStores.description', 'ProductsStores.ean13', 'ProductsStores.internal_code']);
                            },
                            'LastStock',
                            'LastCatalog',
                            'Sections' => [
                                'queryBuilder' => function (\Cake\ORM\Query $query) {
                                    return $query
                                        ->select([
                                            'Sections.id',
                                            'Sections.section_name',
                                            'Sections.section_code',
                                        ]);
                                }
                            ],
                        ],
                        'Aisles'
                    ]
                ])
                ->where([
                    'RobotSessions.id' => $this->request->data('robot_session_id'),
                    'RobotSessions.store_id' => $store_id,
                    //'RobotSessions.includes_qa' => 1,
                    //'RobotSessions.includes_facing' => 1,
                    'RobotSessions.facing_labels_finished' => 1,
                    'RobotSessions.facing_labels_processing' => 0
                ])
                ->first();
                //->cache('price_difference_query_'.$this->request->data('session_date').'_'.$store_data->store_code, 'config_cache_query');

            $data = [
                'stats' => [
                    'total_products_in_alerts' => 0,
                    'total_detections' => 0,
                    '30_days_alert_detections' => 0
                ],
                'products' => [

                ]
            ];

            if(isset($robot_session->detections) && count($robot_session->detections) > 0){

                foreach($robot_session->detections as $detection){

                    if(!isset($data['products'][$detection->products_store->section_id]['section'])){
                        $data['products'][$detection->products_store->section_id]['section'] = [
                            'id' => $detection->products_store->section_id,
                            'section_name' => (isset($detection->products_store->section->section_name)) ? $detection->products_store->section->section_name : __('No data'),
                            'section_code' => (isset($detection->products_store->section->section_code)) ? $detection->products_store->section->section_code : 'no-data',
                            'count_labels' => 0,
                            'count_products' => 0
                        ];
                    }

                    if(!isset($data['products'][$detection->products_store->section_id]['data'][$detection->aisle->aisle_number][$detection->products_store->internal_code])){
                        $data['products'][$detection->products_store->section_id]['data'][$detection->aisle->aisle_number][$detection->products_store->internal_code] = [
                            'ean13' => $detection->products_store->ean13,
                            'internal_code' => $detection->products_store->internal_code,
                            'description' => $detection->products_store->description,
                        ];

                        $data['stats']['total_products_in_alerts'] = $data['stats']['total_products_in_alerts'] + 1;
                    }

                    $data['products'][$detection->products_store->section_id]['data'][$detection->aisle->aisle_number][$detection->products_store->internal_code]['detections'][] = [
                        'detection_code' => $detection->detection_code,
                        'label_price' => $detection->label_price,
                        'location_x' => $detection->location_x,
                        'location_y' => $detection->location_y,
                        'location_z' => $detection->location_z,
                        'aisle' => $detection->aisle->aisle_number
                    ];

                    //if($detection->products_store->last_stock != null){

                        $robot_sessions_list = $this->Stores->RobotSessions->find('list', [
                            'keyField' => 'id',
                            'valueField' => 'id',
                            'conditions' => [
                                'RobotSessions.store_id' => $store_id,
                                'RobotSessions.facing_labels_finished' => 1,
                                'RobotSessions.facing_labels_processing' => 0,
                                'RobotSessions.session_date <' => $session_date,
                                'RobotSessions.session_date >=' => $last_30_days_date,
                            ]
                        ])
                        ->toArray();

                        //print_r($robot_sessions_list);

                        if(count($robot_sessions_list) > 0){
                            $quantity_alerts = $this->Stores->RobotSessions->Detections->find('all')
                                ->where([
                                    'Detections.stock_alert' => 1,
                                    'Detections.product_store_id' => $detection->products_store->id,
                                    'Detections.robot_session_id IN' => $robot_sessions_list
                                ])
                                ->count();
                        }
                        else{
                            $quantity_alerts = __('No available');
                        }
                        
                        if($quantity_alerts > 0){
                            $data['stats']['30_days_alert_detections'] = $data['stats']['30_days_alert_detections'] + 1;
                            $quantity_alerts = $quantity_alerts + 1;
                        }

                        $data['products'][$detection->products_store->section_id]['data'][$detection->aisle->aisle_number][$detection->products_store->internal_code]['stock_update'] = [
                            'stock' => $detection->stock_on_hand,//$detection->products_store->last_stock->current_stock,
                            'last_stock' => null,//$detection->products_store->last_stock->last_stock,
                            'stock_warehouse' => $detection->stock_in_warehouse,
                            'stock_in_transit' => $detection->stock_in_transit,
                            'company_updated' => null,//$detection->products_store->last_stock->stock_updated,
                            '30_days_alerts' => $quantity_alerts
                        ];
                    //}

                    if($detection->products_store->last_catalog != null){
                        $data['products'][$detection->products_store->section_id]['data'][$detection->aisle->aisle_number][$detection->products_store->internal_code]['catalog_update'] = [
                            'enabled' => ($detection->products_store->last_catalog->enabled == 1) ? 1 : 0
                        ]; 
                    }
                    

                    $data['stats']['total_detections'] = $data['stats']['total_detections'] + 1;
                }

                ksort($data['products']);

                foreach($data['products'] as $section_id => $info){

                    ksort($data['products'][$section_id]['data']);

                    $total_products = 0;
                    $total_detections = 0;

                    foreach($info['data'] as $aisle_number => $products){
                        
                        $total_products += count($products);

                        foreach($products as $product){
                            $total_detections += count($product['detections']);
                        }
                    }

                    $data['products'][$section_id]['section']['count_products'] = $total_products;
                    $data['products'][$section_id]['section']['count_labels'] = $total_detections;
                }

                if($robot_session->total_stock_alert_products == null){
                    $robot_session->total_stock_alert_products = $data['stats']['total_products_in_alerts'];
                    $this->RobotSessions->save($robot_session);
                }

                if($robot_session->total_stock_alert_detections == null){
                    $robot_session->total_stock_alert_detections = $data['stats']['total_detections'];
                    $this->RobotSessions->save($robot_session);
                }

                $barcode = new BarcodeGeneratorPNG();
                
                /*if(count($data) > 0){
                    $report = New RobotReportsMain();

                    $pdf_path = ROOT . DIRECTORY_SEPARATOR . 'webroot'. DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . 'pdfs' .  DIRECTORY_SEPARATOR . 'stock_alert_'.$store_data->company->company_keyword.'_'.$store_data->store_code.'_'.$robot_session->session_code.'.pdf';

                    if(file_exists($pdf_path)){
                        unlink($pdf_path);
                    }

                    $zippedi_images = [];

                    $CakePdf = new \CakePdf\Pdf\CakePdf();
                    $CakePdf->template('stock_alert', 'default');
                    $CakePdf->viewVars(['barcode' => $barcode, 'store_data' => $store_data, 'data' => $data, 'session_date' => $robot_session->session_date, 'zippedi_images' => $zippedi_images, 'type' => 'list']);
                    $pdf = $CakePdf->write($pdf_path); 
                    
                }*/

                $this->set('barcode', $barcode);
                $this->set('robot_session_id', $robot_session->id);
                
            }

            $this->set('data', $data);
            $this->set('store_data', $store_data);
            $this->set('session_code', $robot_session->session_code);
            $this->set('robot_session_id', $robot_session->id);
            $this->set('robot_session', $robot_session);

        }
        
    }

    function doStockOutReportByApi(){
         if($this->request->is('post')){

            if($this->request->data('company_id') == null || $this->request->data('store_id') == null || $this->request->data('session_date') == null){
                $response = new \stdClass();
                $response->status = false;
                $response->message = '';
                $response->data = [];
                $response->error = __('Invalid Params.');

                $this->response->type('json');
                $this->response->body(json_encode($response));

                return $this->response;
            }




            $store_data = $this->Stores->find('all')->contain(['Companies'])->where(['Stores.id' => $this->request->data('store_id')])->first();

            $report = New RobotReportsMain();

            $session_id = $report->getSessionCode($store_data->store_code, $this->request->data('session_date'));

            $pdf_path = ROOT . DIRECTORY_SEPARATOR . 'webroot'. DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . 'pdfs' .  DIRECTORY_SEPARATOR . 'stock_out_'.$store_data->company_id.$store_data->id.$session_id.'.pdf';

            //Validar si es acutalizacion o lectura
            $update = false;
            if($this->request->data('update') == 1){
                $update = true;
                Cache::delete('element_stock_out_report_view_'.$this->request->data('company_id').$this->request->data('store_id').$session_id, 'config_cache_report');

                if(file_exists($pdf_path)){
                    unlink($pdf_path);
                }
            }

            if (($report_view = Cache::read('element_stock_out_report_view_'.$this->request->data('company_id').$this->request->data('store_id').$session_id, 'config_cache_report')) === false) {

                $sections = $this->Sections->find('all')->select(['Sections.section_code', 'Sections.section_name'])->where(['Sections.company_id' => $store_data->company->id, 'Sections.section_name <>' => ''])->toArray();

                $session_date = New Time(substr($session_id, 0, 4).'-'.substr($session_id, 4, 2).'-'.substr($session_id, 6, 2).' '.substr($session_id, 8, 2).':'.substr($session_id, 10, 2).':00');
                
                $stock_outs = [];
                $exist = false;
                


                $products = $this->getStockOut($store_data->store_code,  $session_id, $update);

                //$stock_outs[$section->section_name]['section'] = $section;
                //$stock_outs[$section->section_name]['data'] = [];



                if(is_array($products) && count($products) > 0){
                                  
                    $store_id = $store_data->id;
                    $query_date = $this->request->data('session_date');

                    $catalog_date =  New Time($this->request->data('session_date'));
                    $catalog_date->modify('-1 days');

                    $catalog_date_query = $catalog_date->format('Y-m-d');
                    
                    foreach($products as $product){

                        $stock_outs[$product['category0']]['data'][$product['item']] = $product;

                        $product_store = $this->ProductsStores->find('all')
                            ->contain('CatalogUpdates', function ($q) use ($store_id, $catalog_date_query){
                                return $q
                                    //->select(['CatalogUpdates.id', 'CatalogUpdates.product_store_id', 'CatalogUpdates.enabled', 'CatalogUpdates.cataloged'])
                                    ->where([
                                        'DATE(CatalogUpdates.catalog_date) <=' => $catalog_date_query,
                                    ])
                                    ->order([
                                        'CatalogUpdates.catalog_date' => 'DESC'
                                    ])
                                    ->limit(1);
                            })
                            ->contain('StockUpdates', function ($q) use ($store_id, $query_date){
                                return $q
                                    ->select([
                                        'StockUpdates.id',
                                        'StockUpdates.product_store_id',
                                        'StockUpdates.current_stock',
                                        'StockUpdates.stock_updated'
                                    ])
                                    ->where([
                                        'StockUpdates.store_id' => $store_id,
                                        'DATE(StockUpdates.stock_updated) <=' => $query_date
                                    ])                             
                                    ->order(['StockUpdates.stock_updated' => 'DESC'])
                                    ->limit(1);
                            })
                            ->contain('ProductStates')
                            ->select([
                                'ProductsStores.id',
                                'ProductsStores.company_id',
                                'ProductsStores.internal_code',
                                'ProductsStores.description',
                                'ProductsStores.ean13'
                            ])
                            ->where([
                                'ProductsStores.company_id' => $store_data->company->id, 
                                'ProductsStores.internal_code' => $product['item'], 
                                'ProductsStores.ean13' => $product['ean'],
                            ])
                            ->first();

                        //echo

                        if($product_store != null){

                            if(count($product_store->stock_updates) > 0){
                                foreach($product_store->stock_updates as $update){
                                    $stock_outs[$product['category0']]['data'][$product['item']]['stock_quantity'] = $update->current_stock;
                                }
                                
                            }
                            else{
                                $stock_outs[$product['category0']]['data'][$product['item']]['stock_quantity'] = null;
                            }

                            if(count($product_store->catalog_updates) > 0){
                                foreach($product_store->catalog_updates as $update){

                                    $stock_outs[$product['category0']]['data'][$product['item']]['enabled'] = $update->enabled;
                                    $stock_outs[$product['category0']]['data'][$product['item']]['cataloged'] = $update->cataloged;
                                }
                                
                            }
                            else{
                                $stock_outs[$product['category0']]['data'][$product['item']]['enabled'] = null;
                                $stock_outs[$product['category0']]['data'][$product['item']]['cataloged'] = null;
                            }


                            $stock_outs[$product['category0']]['data'][$product['item']]['product_store_id'] = $product_store->id;
                            $stock_outs[$product['category0']]['data'][$product['item']]['status'] = ($product_store->product_state != null) ? $product_store->product_state->state_name : null;
                            $stock_outs[$product['category0']]['data'][$product['item']]['status_class'] = ($product_store->product_state != null) ? $product_store->product_state->state_class : null;
                        }
                        else{
                            $stock_outs[$product['category0']]['data'][$product['item']]['product_store_id'] = null;
                            $stock_outs[$product['category0']]['data'][$product['item']]['stock_quantity'] = null;
                            $stock_outs[$product['category0']]['data'][$product['item']]['enabled'] = null;
                            $stock_outs[$product['category0']]['data'][$product['item']]['cataloged'] = null;
                            $stock_outs[$product['category0']]['data'][$product['item']]['status'] = null;
                            $stock_outs[$product['category0']]['data'][$product['item']]['status_class'] = null;
                        }
                    }

                    $exist = true;
                }

                foreach ($sections as $section) {

                    if($store_data->store_code != 'HC67'){
                        $section_code = intval($section->section_code);
                    }
                    else{
                        $section_code = $section->section_code;
                    }

                    if(isset($stock_outs[$section_code])){
                        $stock_outs[$section_code]['section'] = $section;
                    }
                }

                ksort($stock_outs);

                $barcode = new BarcodeGeneratorPNG();
                $product_states = $this->ProductStates->find('all')->select(['ProductStates.state_keyword', 'ProductStates.state_name', 'ProductStates.state_class'])->where(['ProductStates.state_type' => 'stock_out'])->toArray();

                $zippedi_images = [];
                foreach($stock_outs as $section_name => $info){
                    if(count($info['data']) > 0 && isset($info['data'])){
                        foreach($info['data'] as $product){
                            $zippedi_images[$product['detection_id']]['facing_image_base64'] = $report->getFacingCrop($store_data->store_code, $session_id, $product['detection_id'], 'base64');
                        }
                    }
                }


                //die('haasday');

                $CakePdf = new \CakePdf\Pdf\CakePdf();
                $CakePdf->template('stock_out', 'default');
                $CakePdf->viewVars(['barcode' => $barcode, 'store_data' => $store_data, 'stock_outs' => $stock_outs, 'session_code' => $session_id, 'session_date' => $session_date, 'zippedi_images' => $zippedi_images, 'product_states' => $product_states]);
                // Get the PDF string returned
                //$pdf = $CakePdf->output();
                // Or write it to file directly
                $pdf = $CakePdf->write($pdf_path); 

                $this->set('product_states', $product_states);
                
                $this->set('barcode', $barcode);
                $this->set('store_data', $store_data);
                $this->set('stock_outs', $stock_outs);
                $this->set('session_code', $session_id);
                $this->set('session_date',  $session_date);
            }
            else{
                
                $this->autoRender = false;
                echo $report_view;
                error_reporting(E_ERROR | E_PARSE);
            }
        }
    }

    public function getStockOut($supermarket_code = null, $session_code = null, $update = false){

        if($supermarket_code == null){
            return false;
        }

        if($session_code == null){
            return false;
        }

        $store_data = $this->Stores->find('all')
            ->select(['Stores.id', 'Stores.company_id','Stores.store_code'])
            ->where(['Stores.store_code' => $supermarket_code])
            ->first();

        if($store_data == null){
            $response = new \stdClass();
            $response->status = false;
            $response->error = __('Store not found');

            $this->response->type('json');
            $this->response->body(json_encode($response));

            return $this->response;
        }

        $json_route = ROOT . DIRECTORY_SEPARATOR . 'logs'. DIRECTORY_SEPARATOR .'stock_out_'.$store_data->company_id.$store_data->id.$session_code.'.json';

        if(file_exists($json_route) && $update == false){
            $string = file_get_contents($json_route);
            $arr = json_decode($string, true);
            return $arr;
        }

        $auth_data = array(
            'username' => 'zippedi',
            'password' => 'YuL1fcfQQIwFL6Es2Zx'
        );

        $http = new Client();
        $request_token = $http->post($this->endpoint.'/auth', json_encode($auth_data), ['type' => 'json']);

        $http = new Client([
            'headers' => ['Authorization' => 'jwt ' . $request_token->json['access_token']]
        ]);

        $url = $this->endpoint.'/results/facing';

        $arr = [];

        $robot_response = $http->get($url, ['store' => $supermarket_code, 'session' => $session_code, 'stock_alert' => 1]);

        if($robot_response->getStatusCode() != 200){

            $response = new \stdClass();
            $response->status = false;
            $response->error = __('Error '.$robot_response->code);

            $this->response->type('json');
            $this->response->body(json_encode($response));

            return $this->response;
        }

        $arr = [];
        
        if(isset($robot_response->json[0]['message'])){
            return $arr;
        }
        else{
            $arr = json_decode($robot_response->json, true);

            if(file_exists($json_route) && $update == true){
                unlink($json_route);
            }
            
            $fp = fopen($json_route, 'w');
            fwrite($fp, json_encode($arr, JSON_PRETTY_PRINT));
            fclose($fp);

        }

        //$arr = json_decode($robot_response->json, true); 

        return $arr;
    }

    public function getSessionCode($store_code = null, $date = null){

        if($store_code == null){
            $response = new \stdClass();
            $response->status = false;
            $response->error = __('Store code is null');
            $response->message = '';

            $this->response->type('json');
            $this->response->body(json_encode($response));

            return $this->response;
        }

        if($store_code == null){
            $response = new \stdClass();
            $response->status = false;
            $response->error = __('Date is null');
            $response->message = '';

            $this->response->type('json');
            $this->response->body(json_encode($response));

            return $this->response;
        }

        $http = new Client();
        $request_token = $http->post($this->endpoint.'/auth', json_encode($this->auth_data), ['type' => 'json']);
        $url = $this->endpoint.'/status/session_list';

        $http = new Client([
            'headers' => ['Authorization' => 'jwt ' . $request_token->json['access_token']]
        ]);

        $this->loadModel('Stores');
        $store_data = $this->Stores->find('all')->select(['Stores.id', 'Stores.store_code'])->where(['Stores.id' => $store_code])->orWhere(['Stores.store_code' => $store_code])->first();


        $robot_response = $http->get($url, ['store' => $store_data->store_code]);

        /*if($robot_response->getStatusCode() != 200){

            $response = new \stdClass();
            $response->status = false;
            $response->error = __('Error {0}', $robot_response->code);
            $response->message = '';
            $response->data = [
                'sessions' => $robot_response->json
            ];

            $this->response->type('json');
            $this->response->body(json_encode($response));

            return $this->response;
        }*/

        $date_format = new Time($date);
        $sessions = $robot_response->json;

        print_r($sessions);

        //die('ewae');



        if(count($robot_response->json) > 0){

            foreach ($robot_response->json as $session) {
                if($session['date'] == $date_format->format('Ymd') && $session['is_test'] == ''){
                    echo $session['session'];
                    //die();
                }
                
            }
        }    
    }

    function downloadPdf($report_keyword = null){

        $report = $this->RobotReports->find('all')->select(['RobotReports.report_keyword'])->where(['RobotReports.report_keyword' => $report_keyword])->first();

        switch ($report->report_keyword) {
            case 'assortmentReport':
                $response = $this->$report->report_keyword.'PdfReport'();
                break;
            
            default:
                echo 'nada';
                die();
            //return $this->redirect();
                break;
        }


        
    }

    function assortmentReportPdf($saludo = null){

        $invoice = 'sd';
        $this->viewBuilder()->options([
            'pdfConfig' => [
                'orientation' => 'portrait',
                'filename' => 'hola.pdf'
            ]
        ]);
        $this->set('invoice', $invoice);

        //die();
    }

    function unique_multidim_array($array, $key) { 
        $temp_array = array(); 
        $i = 0; 
        $key_array = array(); 
        
        foreach($array as $val) { 
            if (!in_array($val[$key], $key_array)) { 
                $key_array[$i] = $val[$key]; 
                $temp_array[$i] = $val; 
            } 
            $i++; 
        } 
        return $temp_array; 
    } 
}