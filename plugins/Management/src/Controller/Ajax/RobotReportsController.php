<?php

namespace Management\Controller\Ajax;

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
use Picqer\Barcode\BarcodeGeneratorHTML;

use Management\Controller\AppController;

class RobotReportsController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadModel('ProductsStores');
        $this->loadModel('Stores');
        $this->loadModel('Sections');
        $this->Auth->allow(['doAssortmentReport']);
        $this->Auth->allow(['downloadPdf']);
        $this->Auth->allow(['assortmentReportPdfReport']);
        $this->Auth->allow(['getSessionsList']);

        
        $this->loadComponent('RequestHandler');
    }

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
        $request_token = $http->post('https://reports.zippedi.cl/auth', json_encode($this->auth_data), ['type' => 'json']);
        $url = 'https://reports.zippedi.cl/session_list';

        $http = new Client([
            'headers' => ['Authorization' => 'jwt ' . $request_token->json['access_token']]
        ]);

        $robot_response = $http->get($url, ['supermarket' => $store_data->store_code]);

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

            $master_date = new Time($this->request->data('end_date'));
            $master_date->modify('-1 days');

            $products_stores = $this->ProductsStores->find('all')
                ->contain('Products', function ($q) {
                    return $q
                        ->select(['Products.ean13', 'Products.ean13_digit', 'Products.id', 'Products.product_description']);
                })
                ->contain('AnalyzedProducts', function ($q) {
                    return $q
                        ->select(['AnalyzedProducts.id']);
                })
                ->contain('Categories', function ($q) {
                    return $q
                        ->select(['Categories.id', 'Categories.category_name']);
                })
                ->select([
                    'ProductsStores.company_internal_code',
                    'ProductsStores.cataloged',
                    'ProductsStores.enabled',
                    'ProductsStores.category_id'
                ])
                ->where([
                    'ProductsStores.company_id' => $this->request->data('company_id'),
                    'ProductsStores.store_id' => $this->request->data('store_id'),
                    'ProductsStores.section_id' => $this->request->data('section_id'),
                    'DATE(ProductsStores.master_catalog_date)' => $master_date->format('Y-m-d'),
                ])
                ->order([
                    'ProductsStores.section_id' => 'ASC',
                    'ProductsStores.category_id' => 'ASC',
                    'ProductsStores.sub_category_id' => 'ASC',
                ])
                ->toArray();

            if(count($products_stores) > 0){

                $global_data = [
                    'numbers_stats' => [
                        'total_master' => count($products_stores),
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
                ];

                $section = $this->ProductsStores->Sections->find('all')
                    ->contain('Categories', function ($q) {
                        return $q
                            ->select(['Categories.category_name', 'Categories.category_code', 'Categories.section_id']);
                    })
                    ->where([
                        'Sections.id' => $this->request->data('section_id')
                    ])
                    ->first();

                $store = $this->ProductsStores->Stores->get($this->request->data('store_id'), ['contain' => ['Companies']]);
                $not_readed_products = [];

                foreach($section->categories as $category){
                    $not_readed_products[$category->category_name]['data'] = [];
                    $not_readed_products[$category->category_name]['category'] = $category;
                }

                foreach($products_stores as $product_store){

                    /**** Global Data ****/
                    if($product_store->analyzed_product != null){
                        $global_data['numbers_stats']['readed_products'] = $global_data['numbers_stats']['readed_products'] + 1;

                        if($product_store->cataloged == 0){
                            $global_data['numbers_stats']['readed_and_discontinued_products'] = $global_data['numbers_stats']['readed_and_discontinued_products'] + 1; 
                        }

                        if($product_store->enabled == 0){
                            $global_data['numbers_stats']['readed_and_blocked_products'] = $global_data['numbers_stats']['readed_and_blocked_products'] + 1; 
                        }
                    }
                    else{
                        $global_data['numbers_stats']['unreaded_products'] = $global_data['numbers_stats']['unreaded_products'] + 1;

                        if($product_store->enabled == 0){
                            $global_data['numbers_stats']['unreaded_and_blocked_products'] = $global_data['numbers_stats']['unreaded_and_blocked_products'] + 1; 
                        }

                        if($product_store->cataloged == 0){
                            $global_data['numbers_stats']['unreaded_and_discontinued_products'] = $global_data['numbers_stats']['unreaded_and_discontinued_products'] + 1; 
                        }

                        $not_readed_products[$product_store->category->category_name]['data'][] = $product_store;
                    }
                    /**** End Global Data ****/
                }

                //Liberar memoria
                unset($products_stores);

                /*** Porcentajes por segmento ***/
                $global_data['percent_stats']['readed_products'] = ($global_data['numbers_stats']['readed_products'] * 100) / ($global_data['numbers_stats']['readed_products'] + $global_data['numbers_stats']['unreaded_products']);
                $global_data['percent_stats']['unreaded_products'] = ($global_data['numbers_stats']['unreaded_products'] * 100) / ($global_data['numbers_stats']['readed_products'] + $global_data['numbers_stats']['unreaded_products']);

                $global_data['percent_stats']['readed_and_blocked_products'] = ($global_data['numbers_stats']['readed_and_blocked_products'] * 100) / ($global_data['numbers_stats']['readed_and_blocked_products'] + $global_data['numbers_stats']['unreaded_and_blocked_products']);
                $global_data['percent_stats']['unreaded_and_blocked_products'] = ($global_data['numbers_stats']['unreaded_and_blocked_products'] * 100) / ($global_data['numbers_stats']['readed_and_blocked_products'] + $global_data['numbers_stats']['unreaded_and_blocked_products']);


                if(($global_data['numbers_stats']['readed_and_discontinued_products'] + $global_data['numbers_stats']['unreaded_and_discontinued_products']) == 0){
                    $global_data['percent_stats']['readed_and_discontinued_products'] = 0;
                }
                else{
                    $global_data['percent_stats']['readed_and_discontinued_products'] = ($global_data['numbers_stats']['readed_and_discontinued_products'] * 100) / ($global_data['numbers_stats']['readed_and_discontinued_products'] + $global_data['numbers_stats']['unreaded_and_discontinued_products']);
                }
                
                if(($global_data['numbers_stats']['readed_and_discontinued_products'] + $global_data['numbers_stats']['unreaded_and_discontinued_products']) == 0){
                    $global_data['percent_stats']['unreaded_and_discontinued_products'] = 0;
                }
                else{
                    $global_data['percent_stats']['unreaded_and_discontinued_products'] = ($global_data['numbers_stats']['unreaded_and_discontinued_products'] * 100) / ($global_data['numbers_stats']['readed_and_discontinued_products'] + $global_data['numbers_stats']['unreaded_and_discontinued_products']);  
                }
                
                /*** Fin Porcentajes ***/

                $this->set('section', $section);
                $this->set('store', $store);
                $this->set('global_data', $global_data);
                $this->set('not_readed_products', $not_readed_products);
            }

            $report_date = new Time($this->request->data('end_date'));
            $this->set('end_date', $report_date);
        }
    }

    function doPriceDifferenceReport(){
         if($this->request->is('post')){

            if($this->request->data('company_id') == null || $this->request->data('store_id') == null || $this->request->data('session_id') == null){
                $response = new \stdClass();
                $response->status = false;
                $response->message = '';
                $response->data = [];
                $response->error = __('Invalid Params.');

                $this->response->type('json');
                $this->response->body(json_encode($response));

                return $this->response;
            }

            //if(($vista_reporte = Cache::read('price_difference_ajax_view_'.$this->request->data('company_id').$this->request->data('store_id').$this->request->data('session_id'), 'config_cache_report')) === false) {


                $store_data = $this->Stores->find('all')->contain(['Companies'])->where(['Stores.id' => $this->request->data('store_id')])->first();

                $sections = $this->Sections->find('all')->select(['Sections.section_code', 'Sections.section_name'])->where(['Sections.company_id' => $store_data->company->id, 'Sections.section_name <>' => ''])->toArray();

                $session_date = New Time(substr($this->request->data('session_id'), 0, 4).'-'.substr($this->request->data('session_id'), 4, 2).'-'.substr($this->request->data('session_id'), 6, 2));
                $master_date = New Time(substr($this->request->data('session_id'), 0, 4).'-'.substr($this->request->data('session_id'), 4, 2).'-'.substr($this->request->data('session_id'), 6, 2));
                $master_date->modify('-1 days');

                $x = 0;
                foreach ($sections as $section) {

                    $products = $this->getPriceDifferences($store_data->store_code,  $this->request->data('session_id'), intval($section->section_code));

                    $products_differences[$section->section_name]['data'] = $products;

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
                    }*/

                    $products_differences[$section->section_name]['section_code'] = $section->section_code;
                }


                $barcode = new BarcodeGeneratorHTML();
                
                $this->set('barcode', $barcode);
                $this->set('store_data', $store_data);
                $this->set('products_differences', $products_differences);
                $this->set('session_code', $this->request->data('session_id'));

                // cucho: escribe el render en un cache
                Cache::write('price_difference_ajax_view_'.$this->request->data('company_id').$this->request->data('store_id').$this->request->data('session_id'), $this->render(), 'config_cache_report');
            /*}
            else{
                
                // cucho: lee el cache y le hace un render, parece que ultrajo los estandares)
                echo Cache::read('price_difference_ajax_view_'.$this->request->data('company_id').$this->request->data('store_id').$this->request->data('session_id'), 'config_cache_report');

                $this->autoRender = false;
                
            }*/
        }
    }

    public function getPriceDifferences($supermarket_code = null, $session_code = null, $section_code = null){

        if($supermarket_code == null){
            return false;
        }

        if($session_code == null){
            return false;
        }

        $auth_data = array(
            'username' => 'zippedi',
            'password' => 'YuL1fcfQQIwFL6Es2Zx'
        );

        $http = new Client();
        $request_token = $http->post('https://reports.zippedi.cl/auth', json_encode($auth_data), ['type' => 'json']);

        $http = new Client([
            'headers' => ['Authorization' => 'jwt ' . $request_token->json['access_token']]
        ]);

        $url = 'https://reports.zippedi.cl/price_differences';


        $robot_response = $http->get($url, ['supermarket' => $supermarket_code, 'session' => $session_code, 'category0' => $section_code, 'source' => 'q']);

        return $robot_response->json;
    }

    function doStockOutReport(){
         if($this->request->is('post')){

            if($this->request->data('company_id') == null || $this->request->data('store_id') == null || $this->request->data('session_id') == null){
                $response = new \stdClass();
                $response->status = false;
                $response->message = '';
                $response->data = [];
                $response->error = __('Invalid Params.');

                $this->response->type('json');
                $this->response->body(json_encode($response));

                return $this->response;
            }

            //if(($vista_reporte = Cache::read('price_difference_ajax_view_'.$this->request->data('company_id').$this->request->data('store_id').$this->request->data('session_id'), 'config_cache_report')) === false) {


                $store_data = $this->Stores->find('all')->contain(['Companies'])->where(['Stores.id' => $this->request->data('store_id')])->first();

                $sections = $this->Sections->find('all')->select(['Sections.section_code', 'Sections.section_name'])->where(['Sections.company_id' => $store_data->company->id, 'Sections.section_name <>' => ''])->toArray();

                
                $x = 0;
                foreach ($sections as $section) {
                    $stock_outs[$section->section_name]['data'] = $this->getStockOut($store_data->store_code,  $this->request->data('session_id'), intval($section->section_code));

                    $stock_outs[$section->section_name]['section_code'] = $section->section_code;

                }


                die();
                $barcode = new BarcodeGeneratorHTML();
                
                $this->set('barcode', $barcode);
                $this->set('store_data', $store_data);
                $this->set('stock_outs', $stock_outs);
                $this->set('session_code', $this->request->data('session_id'));

                // cucho: escribe el render en un cache
                //Cache::write('price_difference_ajax_view_'.$this->request->data('company_id').$this->request->data('store_id').$this->request->data('session_id'), $this->render(), 'config_cache_report');
            /*}
            else{
                
                // cucho: lee el cache y le hace un render, parece que ultrajo los estandares)
                echo Cache::read('price_difference_ajax_view_'.$this->request->data('company_id').$this->request->data('store_id').$this->request->data('session_id'), 'config_cache_report');

                $this->autoRender = false;
                
            }*/
        }
    }

    public function getStockOut($supermarket_code = null, $session_code = null, $section_code = null){

        if($supermarket_code == null){
            return false;
        }

        if($session_code == null){
            return false;
        }

        $auth_data = array(
            'username' => 'zippedi',
            'password' => 'YuL1fcfQQIwFL6Es2Zx'
        );

        $http = new Client();
        $request_token = $http->post('https://reports.zippedi.cl/auth', json_encode($auth_data), ['type' => 'json']);

        $http = new Client([
            'headers' => ['Authorization' => 'jwt ' . $request_token->json['access_token']]
        ]);

        $url = 'https://reports.zippedi.cl/facing';


        $robot_response = $http->get($url, ['supermarket' => $supermarket_code, 'session' => $session_code, 'category0' => $section_code, 'stock_alert' => 1]);

        echo '<pre>';
        print_r($robot_response);
        echo '</pre>';

        echo '<pre>';
        print_r($robot_response->json);
        echo '</pre>';

        //die();

        return $robot_response->json;
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
}