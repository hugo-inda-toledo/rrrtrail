<?php
namespace App\Shell;

use Cake\Console\Shell;
use Cake\Event\Event;
use Cake\Network\Http\Client;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;
use Cake\I18n\FrozenTime;
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
use App\View\Helper\SlackHelper;
use Picqer\Barcode\BarcodeGeneratorPNG;

class ProductShell extends Shell
{

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('RobotSessions');
        $this->loadModel('ProductsStores');
        $this->loadModel('Detections');
        $this->loadModel('PriceUpdates');
        $this->loadModel('CatalogUpdates');
        $this->loadModel('Stores');
        $this->loadModel('Sections');
        $this->loadModel('Categories');
        $this->loadModel('SubCategories');
        $this->loadModel('Companies');
        $this->loadModel('MeasurementUnits');
        $this->loadModel('Aisles');
        $this->loadModel('AnalyzedProducts');
    }

    public $tasks = ['Cencosud', 'Sodimac', 'Walmart'];

    public function processCatalogs(){
        $companies = $this->Stores->Companies->find('all')
            ->where([
                'Companies.active' => 1
            ])
            ->toArray();

        if(count($companies) > 0){
           foreach($companies as $company){
                $this->dispatchShell(['command' => 'product getActiveMaster '.$company->company_keyword]);
           } 
        }
    }

    function getDetectionsDispatch($company_keyword = null, $type = null, $persistent = 'false', $email = 'false', $shutdown = 'false'){

        if($type == null){
            $this->out(__('Defined a type process'));
            return false;
        }

        $companies = $this->Stores->Companies->find('all')
            ->where([
                'Companies.company_keyword' => $company_keyword,
                'Companies.active' => 1
            ])
            ->toArray();


        if(count($companies) > 0){
            foreach($companies as $company){   
                $this->out(__('<question>Dispatching: Zippedi Detections Cron For {0}</question>', [$company->company_name]));

                $this->dispatchShell(['command' => 'product searchSessions '.$company->company_keyword.' '.$type.' '.$persistent.' '.$email]);

                /*if($persistent == true){

                    if($email == true){
                        $this->dispatchShell(['command' => 'product getSeenProducts '.$company->company_keyword.' '.$persis]);
                    }
                    else{
                        $this->dispatchShell(['command' => 'product getSeenProducts '.$company->company_keyword.' true']);
                    }
                }
                else{
                    if($email == true){
                        $this->dispatchShell(['command' => 'product getSeenProducts '.$company->company_keyword.' false true']);
                    }
                    else{
                        $this->dispatchShell(['command' => 'product getSeenProducts '.$company->company_keyword]);
                    }
                    
                }*/
            }


            if($shutdown == 'true'){
                $this->dispatchShell(['command' => 'product shutdownServer ']);
            }
        }
        else{
            $this->out(__('<error>No active companies</error>'));
        }


    }

    public function priceUpdateDispatch($company_keyword = null){


        $companies = $this->Stores->Companies->find('all')
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
                'Companies.active' => 1
            ])
            ->toArray();

        if(count($companies) > 0){
            foreach($companies as $company){   
                if(count($company->stores) > 0){

                    foreach($company->stores as $store){

                        switch ($company->company_keyword) {
                            case 'jumbo':
                                $this->out(__('<question>Dispatching: Price Update Cron For {0} - [{1}] {2}</question>', [$company->company_name, $store->store_code, $store->store_name]));
                                $this->dispatchShell(['command' => 'product updatePriceProducts '.$store->store_code]);
                                break;
                            
                            default:
                                $this->out(__('Not function for {0}', $company->company_name));
                                break;
                        }
                    } 
                }
                else{
                    $this->out(__('<error>No active stores for {0}</error>', $company->company_name));
                }
            }
        }
        else{
            $this->out(__('<error>No active companies</error>'));
        }
    }

    //on terminal
    //sudo php bin/cake.php product getActiveMaster param1 param2
    //Proceso para contruir la maestra de productos del día
    public function getActiveMaster($company_keyword = null)
    {
        if($company_keyword != null){

            /*$store = $this->Stores->find('all')
                ->contain([
                    'Companies'
                ])
                ->where([
                    'Stores.store_code' => $store_code
                ])
                ->first();*/

            /*$company = $this->Companies->find('all')
                ->contain([
                    'Stores' => function (\Cake\ORM\Query $query){
                        return $query->where(['Stores.active' => 1]);
                    }
                ])
                ->where([
                    'Companies.company_keyword' => $company_keyword
                ])
                ->toArray();*/

            //if($company != null){

            switch ($company_keyword) {
                case 'jumbo':
                    $response = $this->Cencosud->doMasterProcess($company_keyword);
                    
                    //$this->Cencosud->updatePriceProducts($store->store_code, null, null, $upload_cloud);
                    break;

                case 'homecenter':
                    
                    $response = $this->Sodimac->doMasterProcess($company_keyword);

                    /*if($upload_cloud == true && is_array($response)){

                        print_r($response);
                        foreach($response as $register){

                            if($register['start'] != '' &&$register['end'] != ''){
                                $this->dispatchShell([ 'command' => 'cloud importDatabase '.$register['table'].' '.$register['start'].' '.$register['end']]);
                            }
                        }
                    }*/
                    
                    break;

                case 'lider':
                    
                    $response = $this->Walmart->doMasterProcess($store, $upload_cloud);

                    if($upload_cloud == true && is_array($response)){

                        foreach($response as $register){

                            if($register['start'] != '' &&$register['end'] != ''){
                                $this->dispatchShell([ 'command' => 'cloud importDatabase '.$register['table'].' '.$register['start'].' '.$register['end']]);
                            }
                        }
                    }
                    
                    break;
                
                default:
                    # code...
                    break;
            }
            //}
            /*else{
                $this->out(__('Store not exist'));
            }*/
        }
        else{
            $this->out(__('You must provided a store code'));
        }
    }

    public function putPriceInEmptyProducts($store_code = null){

        if($store_code != null){

            $store = $this->Stores->find('all')
                ->contain([
                    'Companies'
                ])
                ->where([
                    'Stores.store_code' => $store_code
                ])
                ->first();

            if($store != null){

                switch ($store->company->company_keyword) {
                    case 'jumbo':

                        $this->Cencosud->doPutPriceInEmptyProducts($store);
                        
                        break;

                    case 'homecenter':
                        
                        //$this->Sodimac->doUpdateProcess();
                        
                        break;

                    case 'lider':
                        
                        //$this->Walmart->doUpdateProcess();
                        
                        break;
                    
                    default:
                        # code...
                        break;
                }
            }
            else{
                $this->out(__('Store not exist'));
            }
        }
        else{
            $this->out(__('You must provided a store code'));
        }
    }

    //on terminal
    //sudo php bin/cake.php product updatePriceProducts param1 param2 param3 param4
    //Proceso para actualizar los precios de los productos
    public function updatePriceProducts($store_code = null, $upload_cloud = false, $from_date = null, $from_time = null){
        if($store_code != null){

            $store = $this->Stores->find('all')
                ->contain([
                    'Companies'
                ])
                ->where([
                    'Stores.store_code' => $store_code
                ])
                ->first();

            if($store != null){

                switch ($store->company->company_keyword) {
                    case 'jumbo':

                        //$product_store = $this->ProductsStores->find('all')->select(['ProductsStores.company_update'])->where(['ProductsStores.company_id' => $store->company_id, 'ProductsStores.store_id' => $store->id])->order(['ProductsStores.company_update' => 'DESC'])->first();
                        $product_store = null;

                        if($product_store != null){
                            if($product_store->company_update != null || $product_store->company_update != ''){
                                $this->Cencosud->doUpdateProcess($store, $upload_cloud, $product_store->company_update->format('Y-m-d'), $product_store->company_update->format('H:i:s'));
                                $this->out(__('Last update: {0} {1}', [$product_store->company_update->format('Y-m-d'), $product_store->company_update->format('H:i:s')]));
                            }
                            else{
                                $this->out(__('Never updated'));
                                $this->Cencosud->doUpdateProcess($store, $upload_cloud);
                            }
                        }
                        else{
                            $this->out(__('Never updated'));
                            $this->Cencosud->doUpdateProcess($store, $upload_cloud);
                        }
                        
                        break;

                    case 'homecenter':
                        
                        $this->Sodimac->doUpdateProcess();
                        
                        break;

                    case 'lider':
                        
                        $this->Walmart->doUpdateProcess();
                        
                        break;
                    
                    default:
                        # code...
                        break;
                }
            }
            else{
                $this->out(__('Store not exist'));
            }
        }
        else{
            $this->out(__('You must provided a store code'));
        }
    }


    public function getInitialPrices($store_code = null){
        if($store_code != null){

            $store = $this->Stores->find('all')
                ->contain([
                    'Companies'
                ])
                ->where([
                    'Stores.store_code' => $store_code
                ])
                ->first();

            if($store != null){

                switch ($store->company->company_keyword) {
                    case 'jumbo':

                        $this->Cencosud->doLoadInitialPrices($store);
                        
                        break;

                    case 'homecenter':
                        
                        $this->Sodimac->doUpdateProcess();
                        
                        break;

                    case 'lider':
                        
                        $this->Walmart->doUpdateProcess();
                        
                        break;
                    
                    default:
                        # code...
                        break;
                }
            }
            else{
                $this->out(__('Store not exist'));
            }
        }
        else{
            $this->out(__('You must provided a store code'));
        }
    }

    function updateStockProducts($store_code = null, $upload_cloud = false){
        if($store_code != null){

            $store = $this->Stores->find('all')
                ->contain([
                    'Companies'
                ])
                ->where([
                    'Stores.store_code' => $store_code
                ])
                ->first();

            if($store != null){

                switch ($store->company->company_keyword) {
                    case 'jumbo':
                        $this->Cencosud->updateStockProducts($store, $upload_cloud);
                        break;

                    case 'homecenter':
                        
                        //$this->Sodimac->doMasterProcess($store, $upload_cloud);
                        
                        break;

                    case 'lider':
                        
                        //$this->Walmart->doMasterProcess($store, $upload_cloud);
                        
                        break;
                    
                    default:
                        # code...
                        break;
                }
            }
            else{
                $this->out(__('Store not exist'));
            }
        }
        else{
            $this->out(__('You must provided a store code'));
        }
    }

    function updateDealProducts($company_keyword = null){
        if($company_keyword != null){

            /*$store = $this->Stores->find('all')
                ->contain([
                    'Companies'
                ])
                ->where([
                    'Stores.store_code' => $store_code
                ])
                ->first();

            if($store != null){*/

                switch ($company_keyword) {
                    case 'jumbo':
                        $this->Cencosud->updateDealProducts($company_keyword);
                        break;

                    case 'homecenter':
                        
                        //$this->Sodimac->doMasterProcess($store, $upload_cloud);
                        
                        break;

                    case 'lider':
                        
                        //$this->Walmart->doMasterProcess($store, $upload_cloud);
                        
                        break;
                    
                    default:
                        # code...
                        break;
                }
            /*}
            else{
                $this->out(__('Store not exist'));
            }*/
        }
        else{
            $this->out(__('You must provided a store code'));
        }
    }


    //on terminal
    //sudo php bin/cake.php product getSeenProducts param1 param 2 param3
    //Proceso para contruir la maestra de productos del día
    public function searchSessions($company_keyword = null, $type = null, $persistent = false, $email = null, $attemp = null, $processed_stores = 0){

        $companies_conditions = [];
        $repeat = true;

        if($company_keyword != null){
            $companies_conditions['Companies.company_keyword'] = $company_keyword;
        }

        $companies = $this->Companies->find('all')
            ->contain([
                /*'Stores' => function (\Cake\ORM\Query $query){
                    return $query->where(['Stores.active' => 1]);
                }*/

                'Stores' => [
                    //'foreignKey' => false,
                    'queryBuilder' => function (\Cake\ORM\Query $query) {
                        return $query->where(['Stores.active' => 1]);
                    },
                    'Companies'
                ]
            ])
            ->where(
                $companies_conditions
            )
            ->toArray();

        if($processed_stores == 0){
            $processed_stores = 0;
        }
        

        if(count($companies)> 0){
            foreach ($companies as $company) {
                if(count($company->stores) > 0){
                    foreach ($company->stores as $store) {
                        
                        $robot_sessions = $this->getAndUpdateRobotSessions($store, $type);

                        array_reverse($robot_sessions);

                        //print_r($robot_sessions);

                        if(count($robot_sessions) > 0){

                            if($attemp == null){
                                $attemp = 0;
                            }

                            foreach ($robot_sessions as $robot_session) {

                                $store['company']['company_keyword'] = $company->company_keyword;
                                $store['company']['company_name'] = $company->company_name;
                                $store['company']['company_logo'] = $company->company_logo;


                                //Obtener el numero total de flejes y ponerlos en la sesión

                                switch ($type) {
                                    case 'price_differences':
                                        $detection_process_result = $this->doPricesDiferencesProcess($store, $robot_session, $email);
                                        break;

                                    case 'facing':
                                        $detection_process_result = $this->doFacingProcess($store, $robot_session, $email);
                                        break;

                                    case 'assortment':
                                        $detection_process_result = $this->doAssortmentProcess($store, $robot_session, $email);
                                        break;

                                    case 'all_detections':
                                        $detection_process_result = $this->doAllDetectionsProcess($store, $robot_session, $email);
                                        break;
                                    
                                    case 'all_base':
                                        $detection_process_result = $this->doPricesDiferencesProcess($store, $robot_session, $email);
                                        $detection_process_result = $this->doFacingProcess($store, $robot_session, $email);
                                        break;

                                    case 'all':
                                        $detection_process_result = $this->doPricesDiferencesProcess($store, $robot_session, $email);
                                        //$detection_process_result = $this->doAllDetectionsProcess($store, $robot_session, $email);
                                        $detection_process_result = $this->doAssortmentProcess($store, $robot_session, $email);
                                        $detection_process_result = $this->doFacingProcess($store, $robot_session, $email);
                                        break;

                                    default:
                                        $this->out(__('You must insert a type process to work with the labels'));
                                        die();
                                        break;
                                }
                                //$detection_process_result = $this->doPricesDiferencesProcess($store, $robot_session, $email);


                                /*print_r($detection_process_result);
                                $this->out('persistent: '.$persistent);*/

                                
                                //Infinito si $persistent = true
                                /*if($detection_process_result['loaded'] == 1){

                                    $processed_stores = $processed_stores + 1;

                                    if($processed_stores == count($company->stores)){
                                        $repeat = false;
                                    }
                                }
                                else{

                                }*/
                            }
                            
                        }
                        else{
                            $this->out(__('No hay sesiónes por procesar'));
                        }
                    }
                }
                else{

                }
            }
        }
        
        if($attemp >= 50400){
            $this->out(__('7 hours trying'));
            return false;
        }
        else{
            if($repeat == true && $persistent == true){

                $this->out(__('No found data - Retry in 5 minutes'));
                sleep(300);
                $attemp = $attemp + 300;
                $this->searchSessions($company_keyword, $type, $persistent, $email, $attemp, $processed_stores);
            }
        }
    }

    //on terminal
    //sudo php bin/cake.php product getSeenProducts param1 param 2 param3
    //Proceso para contruir la maestra de productos del día
    public function getSeenProducts($company_keyword = null, $persistent = false, $email = null, $attemp = null){

        $companies_conditions = [];
        $repeat = true;

        if($company_keyword != null){
            $companies_conditions['Companies.company_keyword'] = $company_keyword;
        }

        $companies = $this->Companies->find('all')
            ->contain([
                /*'Stores' => function (\Cake\ORM\Query $query){
                    return $query->where(['Stores.active' => 1]);
                }*/

                'Stores' => [
                    //'foreignKey' => false,
                    'queryBuilder' => function (\Cake\ORM\Query $query) {
                        return $query->where(['Stores.active' => 1]);
                    },
                    'Companies'
                ]
            ])
            ->where(
                $companies_conditions
            )
            ->toArray();

        if(count($companies)> 0){
            foreach ($companies as $company) {
                if(count($company->stores) > 0){
                    foreach ($company->stores as $store) {
                        
                        $robot_sessions = $this->getAndUpdateRobotSessions($store, 'to_process');

                        array_reverse($robot_sessions);

                        if(count($robot_sessions) > 0){

                            if($attemp == null){
                                $attemp = 0;
                            }

                            foreach ($robot_sessions as $robot_session) {

                                //if($robot_session->includes_qa == 1 && $robot_session->includes_facing == 1 && $robot_session->is_test == 0){

                                    $store['company']['company_keyword'] = $company->company_keyword;
                                    $store['company']['company_name'] = $company->company_name;
                                    $store['company']['company_logo'] = $company->company_logo;
                            
                                    $detection_process_result = $this->doAllDetectionsProcess($store, $robot_session, $email);
                                    //$detection_process_result = $this->doPricesDiferencesProcess($store, $robot_session, $email);


                                    /*print_r($detection_process_result);
                                    $this->out('persistent: '.$persistent);*/

                                    if($detection_process_result['loaded'] == 1){
                                        $repeat = false;
                                    }
                                    else{

                                    }
                                //}
                            }
                            
                        }
                        else{
                            $this->out(__('No hay sesiónes por procesar'));
                        }
                    }
                }
                else{

                }
            }
        }
        
        if($attemp >= 50400){
            $this->out(__('7 hours trying'));
            return false;
        }
        else{
            if($repeat == true && $persistent == true){

                $this->out(__('No found data - Retry in 5 minutes'));
                sleep(300);
                $attemp = $attemp + 300;
                $this->getSeenProducts($company_keyword, $persistent, $attemp);
            }
        }
    }

    function shutdownServer(){
        system('shutdown -h now');
    }

    //PASAR FUNCION AL MODELO ROBOTSESSIONS
    function getAndUpdateRobotSessions($store = null, $type = null){

        $robot_session_array = [];

        $robot_reports = new RobotReportsController();
        //$robot_sessions = new RobotSessionsController();

        //llamar a api robot con las secciones disponibles
        
        $sessions_list = $robot_reports->getSessionsList($store->store_code);
        //$sessions_list = $robot_reports->getSessionsList($store->store_code, null, null, 10, false, false, false);
        
        print_r($sessions_list);
        //die();

        //$sessions_list = $robot_sessions->getSessionsList($store->id, 'to_process', false, false);

        //Validar que hay secciones
        $seen_products = [];
        

        if(count($sessions_list) > 0 && $sessions_list != null){

            $sessions_list = array_reverse($sessions_list);
            
            //Buscar la session del día de hoy
            foreach($sessions_list as $session){

                if($session['is_test'] == ''){
                    $robot_session = $this->RobotSessions->find('all')->where(['RobotSessions.store_id' => $store->id, 'RobotSessions.session_code' => $session['session']])->first();

                    if(count($robot_session) == 0){

                        $session_date = substr($session['session'], 0, 4).'-'.substr($session['session'], 4, 2).'-'.substr($session['session'], 6, 2).' '.substr($session['session'], 8, 2).':'.substr($session['session'], 10, 2).':00';

                        $calendar_date = substr($session['date'], 0, 4).'-'.substr($session['date'], 4, 2).'-'.substr($session['session'], 6, 2);

                        //echo $session_date;
                        $robot_session = $this->RobotSessions->newEntity();
                        $robot_session->store_id = $store->id;
                        $robot_session->session_code = $session['session'];
                        $robot_session->session_date = New Time($session_date);
                        $robot_session->includes_qa = ($session['includes_qa'] == 1) ?  1 : 0;
                        $robot_session->includes_facing = ($session['includes_facing'] == 1) ?  1 : 0;
                        $robot_session->is_test = ($session['is_test'] == 1) ?  1 : 0;
                        $robot_session->calendar_date = New Time($calendar_date);


                        if($session['robot_start'] != ''){
                            $datetime = explode('T', $session['robot_start']);
                            $new_time_robot_start = new Time($datetime[0].' '.$datetime[1]);

                            $robot_session->robot_start = $new_time_robot_start;
                            $this->RobotSessions->save($robot_session);
                        }

                        if($session['robot_end'] != ''){
                            $datetime = explode('T', $session['robot_end']);
                            $new_time_robot_end = new Time($datetime[0].' '.$datetime[1]);

                            $robot_session->robot_end = $new_time_robot_end;
                            $this->RobotSessions->save($robot_session);
                        }


                        if($this->RobotSessions->save($robot_session)){
                            $this->out(__('New sessión saved {0} - {1}', [$store->store_code, $session['session']]));
                        }
                    }
                    else{

                        if($session['robot_start'] != '' && $robot_session->robot_start == null){
                            $datetime = explode('T', $session['robot_start']);
                            $new_time_robot_start = new Time($datetime[0].' '.$datetime[1]);

                            $robot_session->robot_start = $new_time_robot_start;
                            $this->RobotSessions->save($robot_session);
                        }

                        if($session['robot_end'] != '' && $robot_session->robot_end){
                            $datetime = explode('T', $session['robot_end']);
                            $new_time_robot_end = new Time($datetime[0].' '.$datetime[1]);

                            $robot_session->robot_end = $new_time_robot_end;
                            $this->RobotSessions->save($robot_session);
                        }

                        if($session['date'] != '' && $robot_session->calendar_date == null){
                            
                            $calendar_date = substr($session['date'], 0, 4).'-'.substr($session['date'], 4, 2).'-'.substr($session['session'], 6, 2);
                            $calendar_date = new Time($calendar_date);
                            $robot_session->calendar_date = $calendar_date;
                            $this->RobotSessions->save($robot_session);
                        }
                    }


                    //print_r($store);
                    //print_r($robot_session);

                    

                    $session_full_data = $robot_reports->getSessionStadistics($store->store_code, $robot_session->session_code);

                    if(isset($session_full_data[0]['seen_labels']) && $robot_session->total_detections  == null){
                        $robot_session->total_detections = $session_full_data[0]['seen_labels'];
                        $this->RobotSessions->save($robot_session);
                    }

                    if(isset($session_full_data[0]['price_differences']) && $robot_session->total_price_difference_detections  == null){
                        $robot_session->total_price_difference_detections = $session_full_data[0]['price_differences'];
                        $this->RobotSessions->save($robot_session);
                    }
                    
                    if(isset($session_full_data[0]['stock_alerts']) && $robot_session->total_stock_alert_detections  == null){
                        $robot_session->total_stock_alert_detections = $session_full_data[0]['stock_alerts'];
                        $this->RobotSessions->save($robot_session);
                    }
                    
                    $session_status = $robot_reports->getSessionStatus($store->store_code, $robot_session->session_code);

                    //Sessiones no ignoradas
                    switch ($type) {
                        case 'price_differences':

                            if($robot_session->price_differences_ignore_session != 1){

                                if($robot_session->price_differences_labels_processing == 0 && $robot_session->price_differences_labels_finished == 0 && $robot_session->facing_labels_processing == 0){

                                    if(isset($session_status[0]) && $session_status[0]['session_ready'] == 1){
                                        $robot_session_array[] = $robot_session;
                                    }                           
                                }
                            }
                            else{
                                $this->out(__('<error>Ignore session for price differences: {0} - {1}</error>', [$robot_session->session_code, $store->store_code]));
                            }
                            
                            break;

                        case 'facing':

                            if($robot_session->facing_ignore_session != 1){
                                if($robot_session->facing_labels_processing == 0 && $robot_session->facing_labels_finished == 0 && $robot_session->price_differences_labels_processing == 0){

                                    if(isset($session_status[0]) && $session_status[0]['session_ready'] == 1 && isset($session_status[0]) && $session_status[0]['facing_ready'] == 1){
                                        $robot_session_array[] = $robot_session;
                                    }                           
                                }
                            }
                            else{
                                $this->out(__('<error>Ignore session for facing: {0} - {1}</error>', [$robot_session->session_code, $store->store_code]));
                            }
                            
                            break;

                        case 'assortment':
                            if($robot_session->assortment_ignore_session != 1){
                                    if($robot_session->assortment_processing == 0 && $robot_session->assortment_finished == 0 && $robot_session->price_differences_labels_processing == 0 && $robot_session->price_differences_labels_finished == 1){

                                    //if(isset($session_status[0]) && $session_status[0]['session_ready'] == 1){
                                        $robot_session_array[] = $robot_session;
                                    //}                           
                                }
                            }
                            else{
                                $this->out(__('<error>Ignore session for assortment: {0} - {1}</error>', [$robot_session->session_code, $store->store_code]));
                            }
                            
                            
                            break;

                        case 'all_labels':
                            if($robot_session->labels_processing == 0 && $robot_session->labels_finished == 0){

                                if(isset($session_status[0]) && $session_status[0]['session_ready'] == 1){
                                    $robot_session_array[] = $robot_session;
                                }                           
                            }
                            
                            break;

                        case 'all':
                            if($robot_session->labels_processing == 0 && $robot_session->labels_finished == 0){

                                if(isset($session_status[0]) && $session_status[0]['session_ready'] == 1){
                                    $robot_session_array[] = $robot_session;
                                }                           
                            }
                            
                            break;
                        
                        default:
                            $this->out(__('You must insert a type process for sessions list'));
                            die();
                            break;
                    }
                }
            }
        }

        return $robot_session_array;
    }

    function doAllDetectionsProcess($store = null, $robot_session = null, $send_email = false){
        
        //Validacion de parametros
        if($store == null || $robot_session == null){

            $this->out(__('Invalid Params.'));
            return false;
        }

        $robot_reports = new RobotReportsController();

        $detection_process_result['loaded'] = 0;

        $seen_labels = $robot_reports->getAllLabelsSumary($store->store_code, $robot_session->session_code);

        if(count($seen_labels) > 0 && $seen_labels != null){

            $detection_process_result['loaded'] = $detection_process_result['loaded'] + 1;

            $arr = [];

            //$send_email = true;

            if($send_email == true){
                /*$start_data = [
                    'store' => [
                        'store_name' => $store->store_name,
                        'store_code' => $store->store_code,
                    ],
                    'company' => [
                        'company_name' => $store['company']['company_name'],
                        'company_keyword' => $store['company']['company_keyword'],
                        'company_logo' => $store['company']['company_logo'],
                    ],
                    'robot_session' => [
                        'session_code' => $robot_session->session_code,
                        'session_date' => $robot_session->session_date
                    ],
                    'products_quantity' => count($seen_labels)
                ];*/

                //$this->out('Mails');


                //$email = new EmailsController;
                //$email->sendDetectionsProcessEmail($start_data);

                $slackHelper = new SlackHelper(new \Cake\View\View());
                $slack_response = $slackHelper->message(__('[All detections] Start - {0} {1}', [$store->store_code, $robot_session->session_date->format('d-m')]), 'reportes', ':checkered_flag:');

                print_r($slack_response);
            }

            $robot_session->labels_processing = 1;
            $robot_session->labels_processing_date = new Time();

            if($robot_session->total_detections == null){
                $robot_session->total_detections = count($seen_labels);
            }
            
            $this->RobotSessions->save($robot_session);

            $data = [
                'price_difference' => [
                    'stats' => [
                        'total_detections' => $robot_session->total_detections,
                        'total_detections_differences' => 0,
                        'total_products' => 0,
                        'total_products_differences' => 0,
                        'total_products_without_price' => 0,
                        'total_differences' => 0
                    ],
                    'products' => [

                    ]
                ],
                'stock_alert' => [
                    'stats' => [
                        'total_products_in_alerts' => 0,
                        'total_products' => 0,
                        'total_detections' => $robot_session->total_detections
                    ],
                    'products' => [

                    ]
                ]
            ];

            //Full memory to iteration
            ini_set('memory_limit', '-1');

            //Set variables to filter query
            $robot_session_id = $robot_session->id;
            $store_id = $robot_session->store_id;

            //OPCION NUEVA 1
            $detectionsTable = TableRegistry::get('Detections');

            $aisles_list = [];
            $aisles_count = [];
            $aisles = $this->Aisles->find('all')
                ->select([
                    'Aisles.id',
                    'Aisles.aisle_number'
                ])
                ->where([
                    'Aisles.store_id' => $store_id
                ])
                ->group('Aisles.aisle_number')
                ->toArray();

            foreach($aisles as $aisle){
                $aisles_list[$aisle->aisle_number] = $aisle->id;
            }

            $exist_detections = $detectionsTable->find('list', [
                    'keyField' => 'detection_code',
                    'valueField' => 'id',
                    'conditions' => [
                        'Detections.robot_session_id' => $robot_session_id,
                    ]
                ])
                ->toArray();

            $detections = [];
            $products = [];
            $processed = 0;

            $x = 0;

            $this->out(__('{0} labels to load for {1} session', [$robot_session->total_detections, $robot_session->session_code]));
            $this->out('Processing labels...');



            foreach($seen_labels as $seen_label){

                array_push($aisles_count, $seen_label['aisle']);

                if(isset($exist_detections[$seen_label['detection_id']])){
                    continue;
                }

                $detections[$x]['robot_session_id'] = $robot_session->id;
                $detections[$x]['detection_code'] = $seen_label['detection_id'];
                $detections[$x]['label_price'] = $seen_label['price'];
                $detections[$x]['location_x'] = $seen_label['location_x'];
                $detections[$x]['location_y'] = $seen_label['location_y'];
                $detections[$x]['location_z'] = $seen_label['location_z'];
                

                if(isset($seen_label['stock_alert']) && $seen_label['stock_alert'] != ''){
                    $detections[$x]['stock_alert'] = $seen_label['stock_alert'];
                }
                else{
                    $detections[$x]['stock_alert']  = null;
                }

                if(isset($seen_label['facing_width']) && $seen_label['facing_width'] != ''){
                    $detections[$x]['facing_width'] = $seen_label['facing_width'];
                }
                else{
                    $detections[$x]['facing_width'] = null;
                }

                if(isset($seen_label['facing_height']) && $seen_label['facing_height'] != ''){
                    $detections[$x]['facing_height'] = $seen_label['facing_height'];
                }
                else{
                    $detections[$x]['facing_height'] = null;
                }


                //Fix para sodimac sin EAN
                if($store->company->company_keyword != 'homecenter'){
                    //Verificacion de producto
                    if(!isset($products[$seen_label['ean']])){

                        $product = $detectionsTable->ProductsStores->find('all')
                            ->contain('PriceUpdates', function ($q) use ($store_id){
                                return $q
                                    ->select(['PriceUpdates.id', 'PriceUpdates.product_store_id', 'PriceUpdates.price', 'PriceUpdates.company_updated'])
                                    ->where([
                                        'PriceUpdates.store_id' => $store_id
                                    ])
                                    ->order([
                                        'PriceUpdates.company_updated' => 'DESC'
                                    ]);
                            })
                            ->select([
                                'ProductsStores.id', 'ProductsStores.ean13', 'ProductsStores.internal_code', 'ProductsStores.company_id'
                            ])
                            ->where([
                                'ProductsStores.company_id' => $store->company_id, 
                                'ProductsStores.internal_code' => $seen_label['item'], 
                                'ProductsStores.ean13' => $seen_label['ean']
                            ])
                            ->first();

                        if($product != null){
                            $detections[$x]['product_store_id'] = $product->id;
                            $products[$product->ean13] = $product->id;
                        }
                        else{

                            if(isset($seen_label['category0']) && $seen_label['category0'] != ''){
                                $section = $this->getSectionData($store->company_id, intval($seen_label['category0']), $seen_label['category0']);
                            }
                            else{
                                $section = null;
                            }

                            if(isset($seen_label['category1']) && $seen_label['category1'] != '' && $section != null){
                                $category = $this->getCategoryData($store->company_id, intval($seen_label['category1']), $seen_label['category1'], $section->id);
                            }
                            else{
                                $category = null;
                            }

                            $product = $detectionsTable->ProductsStores->newEntity();
                            $product->company_id = $store->company_id;
                            $product->section_id = ($section != null) ? $section->id : $section;
                            $product->category_id = ($category != null) ? $category->id : $category;
                            $product->description = utf8_encode(ucwords(strtolower($seen_label['description'])));
                            $product->internal_code = $seen_label['item'];
                            $product->ean13 = trim($seen_label['ean']);

                            if(!$this->ProductsStores->save($product)){

                                //$this->out(__('<error>Error while saving the product [EAN: {0} INT.CODE: {1}]</error>', [$product->ean13, $product->internal_code]));
                            }
                            else{
                                //$this->out(__('<success>Se agrego producto inicial [EAN: {0} INT.CODE: {1} DESCRIPTION: {2}]</success>', [$product->ean13, $product->internal_code, $product->description]));
                                
                                //Set fake ORM
                                $detections[$x]['product_store_id'] = $product->id;
                                $products[$product->ean13] = $product->id;
                                $product->price_updates = [];
                            }
                        }
                    }
                    else{
                        $detections[$x]['product_store_id'] = $products[$seen_label['ean']];
                        $products[$product->ean13] = $product->id;
                    }
                }
                else{

                    //Verificacion de producto
                    if(!isset($products[$seen_label['item']])){

                        $product = $detectionsTable->ProductsStores->find('all')
                            ->contain('PriceUpdates', function ($q) use ($store_id){
                                return $q
                                    ->select(['PriceUpdates.id', 'PriceUpdates.product_store_id', 'PriceUpdates.price', 'PriceUpdates.company_updated'])
                                    ->where([
                                        'PriceUpdates.store_id' => $store_id
                                    ])
                                    ->order([
                                        'PriceUpdates.company_updated' => 'DESC'
                                    ]);
                            })
                            ->select([
                                'ProductsStores.id', 'ProductsStores.internal_code', 'ProductsStores.company_id'
                            ])
                            ->where([
                                'ProductsStores.company_id' => $store->company_id, 
                                'ProductsStores.internal_code' => $seen_label['item']
                            ])
                            ->first();

                        if($product != null){
                            $detections[$x]['product_store_id'] = $product->id;
                            $products[$product->internal_code] = $product->id;
                        }
                        else{

                            if(isset($seen_label['category0']) && $seen_label['category0'] != ''){
                                $section = $this->getSectionData($store->company_id, intval($seen_label['category0']), $seen_label['category0']);
                            }
                            else{
                                $section = null;
                            }

                            if(isset($seen_label['category1']) && $seen_label['category1'] != '' && $section != null){
                                $category = $this->getCategoryData($store->company_id, intval($seen_label['category1']), $seen_label['category1'], $section->id);
                            }
                            else{
                                $category = null;
                            }

                            $product = $detectionsTable->ProductsStores->newEntity();
                            $product->company_id = $store->company_id;
                            $product->section_id = ($section != null) ? $section->id : $section;
                            $product->category_id = ($category != null) ? $category->id : $category;
                            $product->description = utf8_encode(ucwords(strtolower($seen_label['description'])));
                            $product->internal_code = $seen_label['item'];
                            $product->ean13 = null;

                            if(!$this->ProductsStores->save($product)){

                                //$this->out(__('<error>Error while saving the product [EAN: {0} INT.CODE: {1}]</error>', [$product->ean13, $product->internal_code]));
                            }
                            else{
                                //$this->out(__('<success>Se agrego producto inicial [EAN: {0} INT.CODE: {1} DESCRIPTION: {2}]</success>', [$product->ean13, $product->internal_code, $product->description]));
                                
                                //Set fake ORM
                                $detections[$x]['product_store_id'] = $product->id;
                                $products[$product->internal_code] = $product->id;
                                $product->price_updates = [];
                            }
                        }
                    }
                    else{
                        $detections[$x]['product_store_id'] = $products[$seen_label['item']];
                        $products[$product->internal_code] = $product->id;
                    }
                }

                if(isset($aisles_list[$seen_label['aisle']])){
                    $detections[$x]['aisle_id'] = $aisles_list[$seen_label['aisle']];
                }
                else{

                    $aisle_data = $detectionsTable->Aisles->newEntity();
                    $aisle_data->company_id = $store->company_id;
                    $aisle_data->store_id = $store->id;
                    $aisle_data->aisle_number = $seen_label['aisle'];
                    $aisle_data->enabled = 1;

                    if($this->Aisles->save($aisle_data)){
                        $detections[$x]['aisle_id'] = $aisle_data->id;

                        //Se agrega pasillo nuevo al array base de verificacion de pasillos
                        $aisles_list[$aisle_data->aisle_number] = $aisle_data->id;
                    }
                }

                if(count($detections) > 2000){

                    $processed = $processed + 2000;
                    $entities = $detectionsTable->newEntities($detections);
                    if($detectionsTable->saveMany($entities)){
                        $this->out(__('{0} / {1} Detections saved', [$processed, $robot_session->total_detections]));

                        $x = 0;
                        $detections = [];
                    }
                    else{
                        $this->out('Detections not saved');
                    }
                }
                else{
                    $x++;
                }
            }


            if(count($detections) > 0){
                $processed = $processed + count($detections);
                $entities = $detectionsTable->newEntities($detections);

                if($detectionsTable->saveMany($entities)){
                    $this->out(__('{0} / {1} Detections saved', [$processed, $robot_session->total_detections]));
                }
                else{
                    $this->out('Detections not saved');

                    $slackHelper = new SlackHelper(new \Cake\View\View());
                    $slack_response = $slackHelper->message(__('Error on save the detections'), 'reportes', ':open_mouth:');
                }
            }

            //////////////

            $filtered_aisles = array_unique($aisles_count);

            $robot_session->analyzed_aisles = count($filtered_aisles);
            //Agregar columna a db
            //$robot_session->analyzed_products = count($products);
            $robot_session->labels_processing = 0;
            $robot_session->labels_finished = 1;
            $robot_session->labels_finished_date = New Time();
            $this->RobotSessions->save($robot_session);

            $this->out(__('{0} aisles analyzed', $robot_session->analyzed_aisles));
            $this->out(__('{0} products analyzed', $robot_session->analyzed_products));

            $this->out(__('<success>Session {0} processed</success>', $robot_session->session_code));


            //Generar PDF Diferencia de precio
            //Enviar email
            if($send_email == true){

                $slackHelper = new SlackHelper(new \Cake\View\View());
                $slack_response = $slackHelper->message(__('[All detections] End - {0} {1}: {2} detections', [$store->store_code, $robot_session->session_date->format('d-m'), $processed]), 'reportes');
            }

            //Generar PDF Stock Out
            /*if($send_email == true){

                $this->out(__('<question>Creating Stock Alert PDF Report</question>'));
                $data_pdf = $reports->stockAlert('cron', 'list', $robot_session->id);

                $files['stock_alert'] = $data_pdf;
            }
            
            if($send_email == true){

                $mail_data = [
                    'store' => [
                        'store_name' => $store->store_name,
                        'store_code' => $store->store_code,
                    ],
                    'company' => [
                        'company_name' => $store->company->company_name,
                        'company_keyword' => $store->company->company_keyword,
                        'company_logo' => $store->company->company_logo,
                    ],
                    'robot_session' => [
                        'session_code' => $robot_session->session_code,
                        'session_date' => $robot_session->session_date
                    ]
                ];

                print_r($files);

                $this->out('Mails');
                $email = new EmailsController;
                $email->sendFinishDetectionsProcessEmail($mail_data, $files);
            }*/

            $this->out(__('<comment>Session processed [SESSION: {0} STORE: {1}]</comment>', [$robot_session->session_code, $store->store_code]));
        }
        else{

            $this->out(__('<error>No data [SESSION: {0} STORE: {1}]</error>', [$robot_session->session_code, $store->store_code]));

            //Borar archivo .json
            $json_route = ROOT . DIRECTORY_SEPARATOR . 'logs'. DIRECTORY_SEPARATOR .'seen_labels_'.$robot_session->session_code.'_'.$store->store_code.'.json';

            if(file_exists($json_route)){
                unlink($json_route);
            }
        }

        return $detection_process_result;
    }


    function doPricesDiferencesProcess($store = null, $robot_session = null, $send_email = false){


        //Validacion de parametros
        if($store == null || $robot_session == null){

            $this->out(__('Invalid Params.'));
            return false;
        }

        $robot_reports = new RobotReportsController();

        $detection_process_result['loaded'] = 0;

        $this->out(__('<info>Searching price differences for {0} session - {1} into Zippedi API...</info>', [$robot_session->session_code, $store->store_code]));
        $labels_with_difference = $robot_reports->getPriceDifferences($store->store_code, $robot_session->session_code);

        if(count($labels_with_difference) > 0 || $labels_with_difference != null){

            $detection_process_result['loaded'] = $detection_process_result['loaded'] + 1;

            $arr = [];

            //$send_email = true;

            if($send_email == true){
                $start_data = [
                    'store' => [
                        'store_name' => $store->store_name,
                        'store_code' => $store->store_code,
                    ],
                    'company' => [
                        'company_name' => $store['company']['company_name'],
                        'company_keyword' => $store['company']['company_keyword'],
                        'company_logo' => $store['company']['company_logo'],
                    ],
                    'robot_session' => [
                        'session_code' => $robot_session->session_code,
                        'session_date' => $robot_session->session_date
                    ],
                    'products_quantity' => count($labels_with_difference)
                ];

                $this->out('Mails');


                $email = new EmailsController;
                //$email->sendDetectionsProcessEmail($start_data);
                
                $slackHelper = new SlackHelper(new \Cake\View\View());
                //$slack_response = $slackHelper->message(__('[Price Differences] Start - {0} {1}', [$store->store_code, $robot_session->session_date->format('d-m')]), 'reportes', ':checkered_flag:');

                //print_r($slack_response);
            }

            $data = [
                'price_difference' => [
                    'stats' => [
                        'total_detections_differences' => $robot_session->total_price_difference_detections,
                        'total_products_differences' => 0,
                        'total_detections_without_price' => 0,
                    ],
                    'products' => [

                    ],
                    'count' => [
                        'inserted' => 0,
                        'updated' => 0
                    ]

                ]
            ];

            //Set variables to filter query
            $robot_session_id = $robot_session->id;
            $store_id = $robot_session->store_id;
            $processed = 0;

            $detectionsTable = TableRegistry::get('Detections');

            $aisles_list = [];
            $aisles_count = [];
            $aisles = $this->Aisles->find('all')
                ->select([
                    'Aisles.id',
                    'Aisles.aisle_number'
                ])
                ->where([
                    'Aisles.store_id' => $store_id
                ])
                ->group('Aisles.aisle_number')
                ->toArray();

            foreach($aisles as $aisle){
                $aisles_list[$aisle->aisle_number] = $aisle->id;
            }

            //Full memory to iteration
            ini_set('memory_limit', '-1');

            $x=0;

            $this->out(__('<question>Startup price differences process for {0} {1} ({2}) - {3}</question>', [$store->company->company_name, $store->store_name, $store->store_code, $robot_session->session_date->format('d-m-Y H:i:s')]));
            $this->out(__('{0} labels to load for {1} session', [$robot_session->total_price_difference_detections, $robot_session->session_code]));
            $this->out('Processing labels...');

            $robot_session->price_differences_labels_processing = 1;
            $robot_session->price_differences_labels_processing_date = new Time();
            $this->RobotSessions->save($robot_session);

            $robot_session_id = $robot_session->id;
            $detections = [];

            foreach($labels_with_difference as $label){


                if($label['store_update_timestamp'] != 'NaN' && $label['price_pos'] != null){

                    $time = New Time($label['store_update_timestamp']);
                    $detection_code = $label['detection_id'];

                    if($store->company->company_keyword != 'homecenter'){
                        //Verificacion de producto
                        $product = $detectionsTable->ProductsStores->find('all')
                            ->contain([
                                'Detections' => function (\Cake\ORM\Query $query) use ($robot_session_id, $detection_code){
                                    return $query
                                        ->select(['Detections.id', 'Detections.product_store_id', 'Detections.detection_code'])
                                        ->where([
                                            'Detections.robot_session_id' => $robot_session_id, 
                                            'Detections.detection_code' => $detection_code
                                        ]);
                                },
                                'Aisles'
                            ])
                            ->select([
                                'ProductsStores.id'
                            ])
                            ->where([
                                'ProductsStores.company_id' => $store->company_id, 
                                'ProductsStores.internal_code' => $label['item'], 
                                'ProductsStores.ean13' => $label['ean']
                            ])
                            ->first();
                    }
                    else{
                        //Verificacion de producto
                        $product = $detectionsTable->ProductsStores->find('all')
                            ->contain([
                                'Detections' => function (\Cake\ORM\Query $query) use ($robot_session_id, $detection_code){
                                    return $query
                                        ->select(['Detections.id', 'Detections.product_store_id', 'Detections.detection_code'])
                                        ->where([
                                            'Detections.robot_session_id' => $robot_session_id, 
                                            'Detections.detection_code' => $detection_code
                                        ]);
                                },
                                'Aisles'
                            ])
                            ->select([
                                'ProductsStores.id'
                            ])
                            ->where([
                                'ProductsStores.company_id' => $store->company_id, 
                                'ProductsStores.internal_code' => $label['item'],
                            ])
                            ->first();
                    }
                    

                    if($product != null){

                        //Si existe la detección
                        if(count($product->detections) > 0){

                            if(isset($label['price']) && $label['price'] != ''){
                                $product->detections[0]->label_price = $label['price'];
                            }
                            else{
                                $product->detections[0]->label_price  = null;
                            }

                            if(isset($label['price_pos']) && $label['price_pos'] != ''){
                                $product->detections[0]->price_pos = $label['price_pos'];
                            }
                            else{
                                $product->detections[0]->price_pos = null;
                            }

                            //if(isset($label['price_difference_alert']) && $label['price_difference_alert'] != ''){
                                $product->detections[0]->price_difference_alert = 1;
                                $product->detections[0]->price_update = $time->format('Y-m-d H:i:s');

                            /*if(isset($label['price_update']) && $label['price_update'] != ''){
                                $product->detections[0]->price_update = $label['price_update'];
                            }
                            else{
                                $product->detections[0]->price_update = null;
                            }*/


                            $detectionsTable->save($product->detections[0]);
                            $data['price_difference']['count']['updated'] = $data['price_difference']['count']['updated'] + 1;
                            continue;
                        }
                        else{

                            $detections[$x]['product_store_id'] = $product->id;
                            $detections[$x]['robot_session_id'] = $robot_session->id;
                            $detections[$x]['detection_code'] = $label['detection_id'];

                            //Guarda el mismo precio que devuelve el endpoint en el fleje
                            //Fix con mala práctica
                            $detections[$x]['label_price'] = $label['price'];
                            $detections[$x]['price_pos'] = $label['price_pos'];


                            $detections[$x]['location_x'] = $label['location_x'];
                            $detections[$x]['location_y'] = $label['location_y'];
                            $detections[$x]['location_z'] = $label['location_z'];
                            $detections[$x]['price_difference_alert'] = 1;

                            //$time = Time::createFromTimestamp($label['timestamp_pos_update']);
                            $detections[$x]['price_update'] = $time->format('Y-m-d H:i:s');
                        }
                    }
                    else{

                        if(isset($label['category0']) && $label['category0'] != ''){
                            $section = $this->getSectionData($store->company_id, intval($label['category0']), $label['category0']);
                        }
                        else{
                            $section = null;
                        }

                        if(isset($label['category1']) && $label['category1'] != '' && $section != null){
                            $category = $this->getCategoryData($store->company_id, intval($label['category1']), $label['category1'], $section->id);
                        }
                        else{
                            $category = null;
                        }

                        $product = $detectionsTable->ProductsStores->newEntity();
                        $product->company_id = $store->company_id;
                        $product->section_id = ($section != null) ? $section->id : $section;
                        $product->category_id = ($category != null) ? $category->id : $category;
                        $product->description = utf8_encode(ucwords(strtolower($label['description'])));
                        $product->internal_code = $label['item'];
                        $product->ean13 = ($store->company->company_keyword != 'homecenter') ? $label['ean'] : null;

                        if($this->ProductsStores->save($product)){
                            $detections[$x]['product_store_id'] = $product->id;
                        }
                    }

                    //**** BCKP DE PRECIO ****/

                    $price_update = $this->PriceUpdates->newEntity();
                    $price_update->product_store_id = $product->id;
                    $price_update->store_id = $store_id;
                    $price_update->price = $label['price_pos'];
                    $price_update->previous_price = null;
                    $price_update->company_updated = $time;
                    $this->PriceUpdates->save($price_update);

                    //**** BCKP DE PRECIO ****/



                    $detections[$x]['robot_session_id'] = $robot_session->id;
                    $detections[$x]['detection_code'] = $label['detection_id'];

                    //Guarda el mismo precio que devuelve el endpoint en el fleje
                    //Fix con mala práctica
                    $detections[$x]['label_price'] = $label['price'];
                    $detections[$x]['price_pos'] = $label['price_pos'];


                    $detections[$x]['location_x'] = $label['location_x'];
                    $detections[$x]['location_y'] = $label['location_y'];
                    $detections[$x]['location_z'] = $label['location_z'];
                    $detections[$x]['price_difference_alert'] = 1;

                    
                    $detections[$x]['price_update'] = $time->format('Y-m-d H:i:s');

                    if(isset($aisles_list[$label['aisle']])){
                        $detections[$x]['aisle_id'] = $aisles_list[$label['aisle']];
                    }
                    else{

                        $aisle_data = $detectionsTable->Aisles->newEntity();
                        $aisle_data->company_id = $store->company_id;
                        $aisle_data->store_id = $store->id;
                        $aisle_data->aisle_number = $label['aisle'];
                        $aisle_data->enabled = 1;

                        if($this->Aisles->save($aisle_data)){
                            $detections[$x]['aisle_id'] = $aisle_data->id;
                        }
                    }

                    $data['price_difference']['count']['inserted'] = $data['price_difference']['count']['inserted'] + 1;
                    $x++;
                }
                else{
                    $this->out(__('Product {0}(EAN: {1} INT. CODE: {2}) without price and/or timestamp', [utf8_encode(ucwords(strtolower($label['description']))), $label['ean'], $label['item']]));
                }
            }

            print_r($data);

            if(count($detections) > 0){

                $entities = $detectionsTable->newEntities($detections);

                if(!$detectionsTable->saveMany($entities)){
                    $this->out('Detections not saved');

                    $slackHelper = new SlackHelper(new \Cake\View\View());
                    $slack_response = $slackHelper->message(__('Error on save the price differences detections'), 'reportes', ':open_mouth:');
                }
            }
            else{
                if($data['price_difference']['count']['updated'] == 0){

                    $this->out('Detections not saved');
                    $slackHelper = new SlackHelper(new \Cake\View\View());
                    $slack_response = $slackHelper->message(__('[Price Differences] Error - {0} {1}: Detections have not been added or updated'), 'reportes', ':open_mouth:');


                    $robot_session->price_differences_labels_processing = 0;
                    $robot_session->price_differences_labels_processing_date = null;
                    $robot_session->price_differences_load_attemps = $robot_session->price_differences_load_attemps + 1;            

                    $this->RobotSessions->save($robot_session);

                    $detection_process_result['loaded'] = 0;

                    return $detection_process_result['loaded'];
                }

            }

            $robot_session->price_differences_labels_processing = 0;
            $robot_session->price_differences_labels_finished = 1;
            $robot_session->price_differences_labels_finished_date = New Time();

            $this->RobotSessions->save($robot_session);

            $this->out(__('{0} price differences detections saved', [count($detections)]));
            $this->out(__('{0} price differences detections updated', $data['price_difference']['count']['updated']));

            $slackHelper = new SlackHelper(new \Cake\View\View());
            $slack_response = $slackHelper->message(__('[Price Differences] End - {0} {1}: {2} labels / {3} labels', [$store->store_code, $robot_session->session_date->format('d-m'), $robot_session->total_price_difference_detections, $robot_session->total_detections]), 'reportes');

            //Generar PDF Diferencia de precio
            //Enviar email
            if($send_email == true){

                $this->out(__('<question>Creating Price Difference PDF Report</question>'));

                //$barcode = new BarcodeGeneratorPNG();
                $reports = new ReportsController();

                //Cambiar reporte de diferencia de precio por nuevo con nuevo flag price_difference_alert
                $data_pdf = $reports->priceDifference('cron', 'list', $robot_session->id);
                //$data_pdf = $reports->priceDifference('cron', 'list', $robot_session->id);

                print_r($data_pdf);

                $session_id = $robot_session->id;

                $robot_session = $this->RobotSessions->get($session_id);

                if($store->company_id == 1){

                    $files['price_difference_inv'] = $data_pdf['price_difference_inv'];
                }

                $files['price_difference'] = $data_pdf['price_difference'];
                //$files['price_difference_xlsx'] = $data_pdf['price_difference_xlsx'];

                $chart_array= [];
                $last_robot_session = [];

                $now = new Time($robot_session->session_date->format('Y-m-d H:i:s'));;
                $last_30_days = new Time($robot_session->session_date->format('Y-m-d H:i:s'));
                $last_30_days->modify('-30 days');

                $robot_sessions_for_chart = $this->RobotSessions->find()
                    ->select([
                        'RobotSessions.session_code',
                        'RobotSessions.session_date',
                        'RobotSessions.total_price_difference_detections',
                        'RobotSessions.total_price_difference_products',
                        'RobotSessions.total_detections'
                    ])
                    ->where([
                        'RobotSessions.store_id' => $robot_session->store_id,
                        'RobotSessions.price_differences_labels_processing' => 0,
                        'RobotSessions.price_differences_labels_finished' => 1,
                        'RobotSessions.total_price_difference_detections IS NOT NULL',
                        'DATE(RobotSessions.session_date) >=' => $last_30_days->format('Y-m-d'),
                        'DATE(RobotSessions.session_date) <=' => $now->format('Y-m-d'),
                    ])
                    ->order([
                        'RobotSessions.session_date' => 'DESC'
                    ])
                    ->limit(16)
                    ->toArray();

                //print_r($last_robot_session);

                if(count($robot_sessions_for_chart) > 2){
                    
                    $last_robot_session = $robot_sessions_for_chart[1];

                    $line = '';
                    $detections_line = '';

                    $goal_line = '';
                    
                    $dates = '';
                    $x = 0;

                    $chart_data = [];

                    foreach($robot_sessions_for_chart as $session){
                        $chart_data[$session->session_code]['total_price_difference_detections'] = $session->total_price_difference_detections;
                        $chart_data[$session->session_code]['total__detections'] = number_format($session->total_detections, 0, '', '.');
                        $chart_data[$session->session_code]['session_date'] = $session->session_date->format('d-m');
                    }

                    $new_chart_data = array_reverse($chart_data);

                    foreach($new_chart_data as $session_code => $value){
                        $line .= $value['total_price_difference_detections'].',';

                        if($x == 0){
                            if($store['company']['company_keyword'] == 'jumbo'){
                                $goal_line .= '%7C50,';
                            }
                            
                            $detections_line = '%7C'.$value['total__detections'].',';
                            $dates .= '%7C'.$value['session_date'];
                            
                        }
                        else{

                            if($store['company']['company_keyword'] == 'jumbo'){
                                $goal_line .= '50,';  
                            }
                            
                            $detections_line .= $value['total__detections'].',';  

                            if($x % 2 == 0){
                                $dates .= '%7C'.$value['session_date'];
                            }
                        }

                        $x++;
                        
                    }

                    if(count($new_chart_data) % 2 == 0){
                        $dates .= '%7C'.$robot_session->session_date->format('d-m');
                    }

                    $line = substr($line, 0, -1);
                    $detections_line = substr($detections_line, 0, -1);

                    if($store['company']['company_keyword'] == 'jumbo'){
                        $goal_line = substr($goal_line, 0, -1);

                        $chart_array['price_differences_detections_url'] = 'https://chart.googleapis.com/chart?cht=lc&chs=600x400&chd=t:'.$line.$goal_line.$detections_line.'&chco=003399,ff6600,696969&chds=0,400,0,400,0,20&chm=B,FFFFFF,0,0,0%7CB,FFFFFF,0,0,0%7CB,E0E0E0,2,2,0&chls=3%7C2,6,3&chg=20,50,1,5&chxt=x,y,r&chxl=0:'.$dates.'&chxp=0,3&chxs=2N**K&chxr=1,0,400,100%7C2,0,20,5&chtt=Flejes+con+diferencia+de+precio&chdl=Alertas+Precio%7CMeta%7CFlejes+Leidos&chdlp=b%7Cl';
                    }
                    else{

                        $chart_array['stock_alert_detections_url'] = 'https://chart.googleapis.com/chart?cht=lc&chs=600x400&chd=t:'.$line.$detections_line.'&chco=003399,696969&chds=0,400,0,20&chm=B,E0E0E0,1,1,0&chls=3&chg=20,50,1,5&chxt=x,y,r&chxl=0:'.$dates.'&chxp=0,3&chxs=2N**K&chxr=1,0,400,100%7C2,0,20,5&chtt=Flejes+con+diferencia+de+precio&chdl=Alertas+Precio%7CFlejes+Leidos&chdlp=b%7Cl';
                    }

                    
                }
                
                $mail_data = [
                    'store' => [
                        'store_name' => $store->store_name,
                        'store_code' => $store->store_code,
                    ],
                    'company' => [
                        'company_name' => $store->company->company_name,
                        'company_keyword' => $store->company->company_keyword,
                        'company_logo' => $store->company->company_logo,
                    ],
                    'robot_session' => $robot_session,
                    'last_robot_session' => $last_robot_session,
                    'chart_array' => $chart_array,
                    'type_report' => 'priceDifferenceReport'
                ];

                print_r($files);

                $this->out('Mails');
                $email = new EmailsController;
                $email->sendFinishDetectionsProcessEmail($mail_data, $files);
            }
        }
        else{

            $slackHelper = new SlackHelper(new \Cake\View\View());
            $slack_response = $slackHelper->message(__('[Price Differences] Error - {0} {1}: empty response from Zippedi API', [$store->store_code, $robot_session->session_date->format('d-m')]), 'reportes');

            $this->out(__('<error>Empty response to price differences for {0} session - {1} into Zippedi API</error>', [$robot_session->session_code, $store->store_code]));

            $robot_session->price_differences_load_attemps = $robot_session->price_differences_load_attemps + 1;
            $this->RobotSessions->save($robot_session);            

        }

        return $detection_process_result;
    }

    function doFacingProcess($store = null, $robot_session = null, $send_email = false){


        //Validacion de parametros
        if($store == null || $robot_session == null){

            $this->out(__('Invalid Params.'));
            return false;
        }

        $robot_reports = new RobotReportsController();

        $detection_process_result['loaded'] = 0;

        $this->out(__('<info>Searching facing detections for {0} session - {1} into Zippedi API...</info>', [$robot_session->session_code, $store->store_code]));
        $facing_labels = $robot_reports->getStockOut($store->store_code, $robot_session->session_code);

        if(count($facing_labels) > 0 || $facing_labels != null){

            $arr = [];

            //$send_email = true;

            if($send_email == true){
                $start_data = [
                    'store' => [
                        'store_name' => $store->store_name,
                        'store_code' => $store->store_code,
                    ],
                    'company' => [
                        'company_name' => $store['company']['company_name'],
                        'company_keyword' => $store['company']['company_keyword'],
                        'company_logo' => $store['company']['company_logo'],
                    ],
                    'robot_session' => [
                        'session_code' => $robot_session->session_code,
                        'session_date' => $robot_session->session_date
                    ],
                    'products_quantity' => count($facing_labels)
                ];

                $this->out('Mails');


                $email = new EmailsController;
                //$email->sendDetectionsProcessEmail($start_data);

                $slackHelper = new SlackHelper(new \Cake\View\View());
                //$slack_response = $slackHelper->message(__('[Facing] Start - {0} {1}', [$store->store_code, $robot_session->session_date->format('d-m')]), 'reportes');

                //print_r($slack_response);
            }

            $robot_session->facing_labels_processing = 1;
            $robot_session->facing_labels_processing_date = new Time();
            $this->RobotSessions->save($robot_session);

            $data = [
                'stock_alert' => [
                    'stats' => [
                        'total_products_in_alerts' => 0,
                        'total_products' => 0,
                        'total_detections' => $robot_session->total_stock_alert_detections,
                        'total_detections_inserted' => 0,
                        'total_detections_updated' => 0
                    ],
                    'products' => [

                    ]
                ]
            ];

            //Set variables to filter query
            $robot_session_id = $robot_session->id;
            $store_id = $robot_session->store_id;
            $processed = 0;

            $detectionsTable = TableRegistry::get('Detections');

            $aisles_list = [];
            $aisles_count = [];
            $aisles = $this->Aisles->find('all')
                ->select([
                    'Aisles.id',
                    'Aisles.aisle_number'
                ])
                ->where([
                    'Aisles.store_id' => $store_id
                ])
                ->group('Aisles.aisle_number')
                ->toArray();

            foreach($aisles as $aisle){
                $aisles_list[$aisle->aisle_number] = $aisle->id;
            }

            //Full memory to iteration
            ini_set('memory_limit', '-1');

            $x=0;

            $this->out(__('{0} facing labels to load for {1} session', [$robot_session->total_stock_alert_detections, $robot_session->session_code]));
            $this->out('Processing labels...');

            $detections = [];

            foreach($facing_labels as $label){

                $detection_code = $label['detection_id'];

                if($store->company->company_keyword != 'homecenter'){
                    //Verificacion de producto
                    $product = $detectionsTable->ProductsStores->find('all')
                        ->contain([
                            'Detections' => function (\Cake\ORM\Query $query) use ($robot_session_id, $detection_code){
                                return $query
                                    ->select(['Detections.id', 'Detections.product_store_id', 'Detections.detection_code'])
                                    ->where([
                                        'Detections.robot_session_id' => $robot_session_id, 
                                        'Detections.detection_code' => $detection_code
                                    ]);
                            },
                            'Aisles'
                        ])
                        ->select([
                            'ProductsStores.id'
                        ])
                        ->where([
                            'ProductsStores.company_id' => $store->company_id, 
                            'ProductsStores.internal_code' => $label['item'], 
                            'ProductsStores.ean13' => $label['ean']
                        ])
                        ->first();
                }
                else{
                    //Verificacion de producto
                    $product = $detectionsTable->ProductsStores->find('all')
                        ->contain([
                            'Detections' => function (\Cake\ORM\Query $query) use ($robot_session_id, $detection_code){
                                return $query
                                    ->select(['Detections.id', 'Detections.product_store_id', 'Detections.detection_code'])
                                    ->where([
                                        'Detections.robot_session_id' => $robot_session_id, 
                                        'Detections.detection_code' => $detection_code
                                    ]);
                            },
                            'Aisles'
                        ])
                        ->select([
                            'ProductsStores.id'
                        ])
                        ->where([
                            'ProductsStores.company_id' => $store->company_id, 
                            'ProductsStores.internal_code' => $label['item'],
                        ])
                        ->first();
                }
                
                //Si encuentra producto
                if($product != null){

                    //Si existe la detección
                    if(count($product->detections) > 0){

                        if(isset($label['stock_alert']) && $label['stock_alert'] != ''){
                            $product->detections[0]->stock_alert = $label['stock_alert'];
                        }
                        else{
                            $product->detections[0]->stock_alert  = null;
                        }

                        if(isset($label['facing_width']) && $label['facing_width'] != ''){
                            $product->detections[0]->facing_width = $label['facing_width'];
                        }
                        else{
                            $product->detections[0]->facing_width = null;
                        }

                        if(isset($label['facing_height']) && $label['facing_height'] != ''){
                            $product->detections[0]->facing_height = $label['facing_height'];
                        }
                        else{
                            $product->detections[0]->facing_height = null;
                        }

                        //*** Start STOCKS **//

                        if(isset($label['store_status'])){

                            if(isset($label['store_status']['stock_in_warehouse'])){
                                $product->detections[0]->stock_in_warehouse = $label['store_status']['stock_in_warehouse'];
                            }  

                            if(isset($label['store_status']['stock_in_transit'])){
                                $product->detections[0]->stock_in_transit = $label['store_status']['stock_in_transit'];
                            }  

                            if(isset($label['store_status']['stock_on_hand'])){
                                $product->detections[0]->stock_on_hand = $label['store_status']['stock_on_hand'];
                            }    

                        }

                        //*** End STOCKS **//

                        $detectionsTable->save($product->detections[0]);
                        $data['stock_alert']['stats']['total_detections_updated'] = $data['stock_alert']['stats']['total_detections_updated'] + 1;
                        continue;
                    }
                    else{

                        //Si no existe
                        $detections[$x]['product_store_id'] = $product->id;

                        if(isset($aisles_list[$label['aisle']])){
                            $detections[$x]['aisle_id'] = $aisles_list[$label['aisle']];
                        }
                        else{


                            $aisle_data = $detectionsTable->Aisles->newEntity();
                            $aisle_data->company_id = $store->company_id;
                            $aisle_data->store_id = $store->id;
                            $aisle_data->aisle_number = $label['aisle'];
                            $aisle_data->enabled = 1;

                            if($this->Aisles->save($aisle_data)){
                                $detections[$x]['aisle_id'] = $aisle_data->id;
                            }
                        }


                        //$detections[$x] = $detectionsTable->newEntity();
                        $detections[$x]['robot_session_id'] = $robot_session->id;
                        $detections[$x]['detection_code'] = $label['detection_id'];
                        $detections[$x]['label_price'] = $label['price'];
                        $detections[$x]['location_x'] = $label['location_x'];
                        $detections[$x]['location_y'] = $label['location_y'];
                        $detections[$x]['location_z'] = $label['location_z'];

                        if(isset($label['stock_alert']) && $label['stock_alert'] != ''){
                            $detections[$x]['stock_alert'] = $label['stock_alert'];
                        }
                        else{
                            $detections[$x]['stock_alert']  = null;
                        }

                        if(isset($label['facing_width']) && $label['facing_width'] != ''){
                            $detections[$x]['facing_width'] = $label['facing_width'];
                        }
                        else{
                            $detections[$x]['facing_width'] = null;
                        }

                        if(isset($label['facing_height']) && $label['facing_height'] != ''){
                            $detections[$x]['facing_height'] = $label['facing_height'];
                        }
                        else{
                            $detections[$x]['facing_height'] = null;
                        }

                        //*** Start STOCKS **//

                        if(isset($label['store_status'])){

                            if(isset($label['store_status']['stock_in_warehouse'])){
                                $detections[$x]['stock_in_warehouse'] = $label['store_status']['stock_in_warehouse'];
                            }  

                            if(isset($label['store_status']['stock_in_transit'])){
                                $detections[$x]['stock_in_transit'] = $label['store_status']['stock_in_transit'];
                            }  

                            if(isset($label['store_status']['stock_on_hand'])){
                                $detections[$x]['stock_on_hand'] = $label['store_status']['stock_on_hand'];
                            }    

                        }

                        //*** End STOCKS **//


                        $data['stock_alert']['stats']['total_detections_inserted'] = $data['stock_alert']['stats']['total_detections_inserted'] + 1;
                        $x++;

                        continue;
                    }
                }
                else{

                    if(isset($label['category0']) && $label['category0'] != ''){
                        $section = $this->getSectionData($store->company_id, intval($label['category0']), $label['category0']);
                    }
                    else{
                        $section = null;
                    }

                    if(isset($label['category1']) && $label['category1'] != '' && $section != null){
                        $category = $this->getCategoryData($store->company_id, intval($label['category1']), $label['category1'], $section->id);
                    }
                    else{
                        $category = null;
                    }

                    $product = $detectionsTable->ProductsStores->newEntity();
                    $product->company_id = $store->company_id;
                    $product->section_id = ($section != null) ? $section->id : $section;
                    $product->category_id = ($category != null) ? $category->id : $category;
                    $product->description = utf8_encode(ucwords(strtolower($label['description'])));
                    $product->internal_code = $label['item'];
                    $product->ean13 = ($store->company->company_keyword != 'homecenter') ? $label['ean'] : null;

                    if(!$this->ProductsStores->save($product)){

                        //$this->out(__('<error>Error while saving the product [EAN: {0} INT.CODE: {1}]</error>', [$product->ean13, $product->internal_code]));
                    }
                    else{
                        //$this->out(__('<success>Se agrego producto inicial [EAN: {0} INT.CODE: {1} DESCRIPTION: {2}]</success>', [$product->ean13, $product->internal_code, $product->description]));
                        
                        //Set fake ORM
                        $detections[$x]['product_store_id'] = $product->id;

                        if(isset($aisles_list[$label['aisle']])){
                            $detections[$x]['aisle_id'] = $aisles_list[$label['aisle']];
                        }
                        else{


                            $aisle_data = $detectionsTable->Aisles->newEntity();
                            $aisle_data->company_id = $store->company_id;
                            $aisle_data->store_id = $store->id;
                            $aisle_data->aisle_number = $label['aisle'];
                            $aisle_data->enabled = 1;

                            if($this->Aisles->save($aisle_data)){
                                $detections[$x]['aisle_id'] = $aisle_data->id;
                            }
                        }


                        //$detections[$x] = $detectionsTable->newEntity();
                        $detections[$x]['robot_session_id'] = $robot_session->id;
                        $detections[$x]['detection_code'] = $label['detection_id'];
                        $detections[$x]['label_price'] = $label['price'];
                        $detections[$x]['location_x'] = $label['location_x'];
                        $detections[$x]['location_y'] = $label['location_y'];
                        $detections[$x]['location_z'] = $label['location_z'];

                        if(isset($label['stock_alert']) && $label['stock_alert'] != ''){
                            $detections[$x]['stock_alert'] = $label['stock_alert'];
                        }
                        else{
                            $detections[$x]['stock_alert']  = null;
                        }

                        if(isset($label['facing_width']) && $label['facing_width'] != ''){
                            $detections[$x]['facing_width'] = $label['facing_width'];
                        }
                        else{
                            $detections[$x]['facing_width'] = null;
                        }

                        if(isset($label['facing_height']) && $label['facing_height'] != ''){
                            $detections[$x]['facing_height'] = $label['facing_height'];
                        }
                        else{
                            $detections[$x]['facing_height'] = null;
                        }

                        if(isset($label['store_status'])){

                            if(isset($label['store_status']['stock_in_warehouse'])){
                                $detections[$x]['stock_in_warehouse'] = $label['store_status']['stock_in_warehouse'];
                            }  

                            if(isset($label['store_status']['stock_in_transit'])){
                                $detections[$x]['stock_in_transit'] = $label['store_status']['stock_in_transit'];
                            }  

                            if(isset($label['store_status']['stock_on_hand'])){
                                $detections[$x]['stock_on_hand'] = $label['store_status']['stock_on_hand'];
                            }    

                        }

                        //*** End STOCKS **//

                        $data['stock_alert']['stats']['total_detections_inserted'] = $data['stock_alert']['stats']['total_detections_inserted'] + 1;
                        $x++;
                        continue;
                    }
                }
            }

            if(count($detections) > 0){

                $entities = $detectionsTable->newEntities($detections);

                if($detectionsTable->saveMany($entities)){                    

                    $this->out(__('{0} facing detections saved', [count($detections)]));

                }
                else{
                    $this->out('Detections not saved');
                    $slackHelper = new SlackHelper(new \Cake\View\View());
                    $slack_response = $slackHelper->message(__('Error on save the facing detections'), 'reportes', ':open_mouth:');
                }
            }
            else{
                if($data['stock_alert']['stats']['total_detections_updated'] == 0){

                    $this->out('Detections not saved');
                    $slackHelper = new SlackHelper(new \Cake\View\View());
                    $slack_response = $slackHelper->message(__('[Facing] Error - {0} {1}: Detections have not been added or updated', [$store->store_code, $robot_session->session_date->format('d-m')]), 'reportes', ':open_mouth:');

                    $robot_session->facing_labels_processing = 0;
                    $robot_session->facing_labels_processing_date = null;
                    $robot_session->facing_load_attemps = $robot_session->facing_load_attemps + 1;

                    $this->RobotSessions->save($robot_session);

                    $detection_process_result['loaded'] = 0;

                    return $detection_process_result['loaded'];
                }
            }

            print_r($data);

            $detection_process_result['loaded'] = $detection_process_result['loaded'] + 1;
            
            //Guardar cantidad de flejes y productos en la robot session
            $robot_session->facing_labels_processing = 0;
            $robot_session->facing_labels_finished = 1;
            $robot_session->facing_labels_finished_date = New Time();
            $this->RobotSessions->save($robot_session);

            $slackHelper = new SlackHelper(new \Cake\View\View());
            $slack_response = $slackHelper->message(__('[Facing] End - {0} {1}: {2} detections / {3} detections', [$store->store_code, $robot_session->session_date->format('d-m'), count($facing_labels), $robot_session->total_detections]), 'reportes', ":sunglasses:");

            //Generar PDF Stock Out
            //Enviar email
            if($send_email == true){

                //print_r($data);

                $reports = new ReportsController();

                $this->out(__('<question>Creating Stock Alert PDF Report</question>'));
                $data_pdf = $reports->stockAlert('cron', 'list', $robot_session->id);

                $files['stock_alert'] = $data_pdf;

                $chart_array= [];
                $last_robot_session = [];


                $session_id = $robot_session->id;

                $robot_session = $this->RobotSessions->get($session_id);

                $now = new Time($robot_session->session_date->format('Y-m-d H:i:s'));;
                $last_30_days = new Time($robot_session->session_date->format('Y-m-d H:i:s'));
                $last_30_days->modify('-30 days');

                $robot_sessions_for_chart = $this->RobotSessions->find()
                    ->select([
                        'RobotSessions.session_code',
                        'RobotSessions.session_date',
                        'RobotSessions.total_stock_alert_detections',
                        'RobotSessions.total_detections'
                    ])
                    ->where([
                        'RobotSessions.store_id' => $robot_session->store_id,
                        'RobotSessions.facing_labels_processing' => 0,
                        'RobotSessions.facing_labels_finished' => 1,
                        'RobotSessions.total_stock_alert_detections IS NOT NULL',
                        'DATE(RobotSessions.session_date) >=' => $last_30_days->format('Y-m-d'),
                        'DATE(RobotSessions.session_date) <=' => $now->format('Y-m-d'),
                    ])
                    ->order([
                        'RobotSessions.session_date' => 'DESC'
                    ])
                    ->limit(16)
                    ->toArray();

                

                if(count($robot_sessions_for_chart) > 2){
                    
                    $last_robot_session = $robot_sessions_for_chart[1];

                    $line = '';
                    $detections_line = '';
                    $dates = '';
                    $x = 0;

                    $chart_data = [];

                    foreach($robot_sessions_for_chart as $session){
                        $chart_data[$session->session_code]['total_stock_alert_detections'] = $session->total_stock_alert_detections;
                        $chart_data[$session->session_code]['total__detections'] = number_format($session->total_detections, 0, '', '.');
                        $chart_data[$session->session_code]['session_date'] = $session->session_date->format('d-m');
                    }

                    $new_chart_data = array_reverse($chart_data);

                    foreach($new_chart_data as $session_code => $value){
                        $line .= $value['total_stock_alert_detections'].',';

                        if($x == 0){
                            $detections_line = '%7C'.$value['total__detections'].',';
                            $dates .= '%7C'.$value['session_date'];
                            
                        }
                        else{

                            $detections_line .= $value['total__detections'].',';  

                            if($x % 2 == 0){
                                $dates .= '%7C'.$value['session_date'];
                            }
                        }

                        $x++;
                        
                    }

                    if(count($new_chart_data) % 2 == 0){
                        $dates .= '%7C'.$robot_sessions_for_chart[0]->session_date->format('d-m');
                    }
                    

                    $line = substr($line, 0, -1);
                    $detections_line = substr($detections_line, 0, -1);


                    $chart_array['stock_alert_detections_url'] = 'https://chart.googleapis.com/chart?cht=lc&chs=600x400&chd=t:'.$line.$detections_line.'&chco=003399,696969&chds=0,400,0,20&chm=B,E0E0E0,1,1,0&chls=3&chg=20,50,1,5&chxt=x,y,r&chxl=0:'.$dates.'&chxp=0,3&chxs=2N**K&chxr=1,0,400,100%7C2,0,20,5&chtt=Flejes+con+Alerta+de+Reposici%C3%B3n&chdl=Alertas+Reposici%C3%B3n%7CFlejes+Leidos&chdlp=b%7Cl';

                }


                $mail_data = [
                    'store' => [
                        'store_name' => $store->store_name,
                        'store_code' => $store->store_code,
                    ],
                    'company' => [
                        'company_name' => $store->company->company_name,
                        'company_keyword' => $store->company->company_keyword,
                        'company_logo' => $store->company->company_logo,
                    ],
                    'robot_session' => $robot_session,
                    'last_robot_session' => $last_robot_session,
                    'chart_array' => $chart_array,
                    'type_report' => 'stockOutReport'
                ];

                print_r($files);

                $this->out('Mails');
                $email = new EmailsController;
                
                //Nuevo mailing 
                $email->sendFinishDetectionsProcessEmail($mail_data, $files);

                //print_r($slack_response);
                
            }

        }
        else{

            $slackHelper = new SlackHelper(new \Cake\View\View());
            $slack_response = $slackHelper->message(__('[Facing] Error - {0} {1}: empty response from Zippedi API', [$store->store_code, $robot_session->session_date->format('d-m')]), 'reportes');

            $this->out(__('<error>Empty response to facing detections for {0} session - {1} into Zippedi API...</error>', [$robot_session->session_code, $store->store_code]));

            $robot_session->facing_load_attemps = $robot_session->facing_load_attemps + 1;
            $this->RobotSessions->save($robot_session);
        }

        return $detection_process_result;
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

    function insertProduct($product_name = null, $ean_full = null){

        if($product_name != null){

            //Object producto
            $product = $this->Products->newEntity();

            $product->product_name = utf8_encode(ucwords(strtolower($product_name)));
            $product->product_description = utf8_encode(ucwords(strtolower($product_name)));

            if($ean_full != null){
                //$product->product_name = utf8_encode(ucwords(strtolower($product_name)));

                $ean_original_length = strlen($ean_full);
                $ean_new_length = $ean_original_length -1;
                $product->ean13 = substr($ean_full, 0, $ean_new_length);
                $product->ean13_digit = substr($ean_full, -1);
            }
            

            if ($this->Products->save($product)) {
                $this->out(__('<info>{0} [EAN: {1}] Saved</info>', [$product->product_description, $product->ean13.$product->ean13_digit]));

                return $product;
            }
        }

        return false;
    }

    function insertAnalyzedProduct($product_store = null, $seen_label = null, $process_date = null, $session_id = null, $upload_cloud = false){

        if($product_store != null && $seen_label != null){
            $analyzed_product = $this->AnalyzedProducts->newEntity();
            $analyzed_product->product_store_id = $product_store->id;
            $analyzed_product->seen_strip_price = $seen_label['price'];
            $analyzed_product->session_date = $process_date->format('Y-m-d');
            $analyzed_product->session_code = $session_id;

            //Analizar diferencia de precio
            if(!is_null($product_store->strip_price)){
                if($product_store->strip_price != $seen_label['price']){

                    if($product_store->strip_price > $seen_label['price']){
                        $analyzed_product->difference = ($product_store->strip_price - $seen_label['price']);
                    }

                    if($seen_label['price'] > $product_store->strip_price){
                        $analyzed_product->difference = ($seen_label['price'] - $product_store->strip_price);
                    }
                }
            }

            if(!$this->AnalyzedProducts->save($analyzed_product)){

                $this->out(__('Error while trying saved the analyzed product'));
                return false;
            }
            else{

                // INICIO Guardar pasillos
                if(count($seen_label['placement']) > 0){
                    for($x=0; $x < count($seen_label['placement']); $x++){
                        
                        //Buscar pasillo
                        $aisle_data = $this->Aisles->find('all')
                            ->select([
                                'Aisles.id'
                            ])
                            ->where([
                                'Aisles.aisle_number' => $seen_label['placement'][$x]['aisle'],
                                'Aisles.company_id' => $product_store->company_id,
                                'Aisles.store_id' => $product_store->store_id
                            ])
                            ->first();

                        //Si no existe se crea en la DB
                        if($aisle_data == null){

                            $aisle_data = $this->Aisles->newEntity();
                            $aisle_data->company_id = $product_store->company_id;
                            $aisle_data->store_id = $product_store->store_id;
                            $aisle_data->aisle_number = $seen_label['placement'][$x]['aisle'];
                            $aisle_data->enabled = 1;

                            if(!$this->Aisles->save($aisle_data)){
                                $this->out(__('Error while saving the aisle'));
                                return false;
                            }
                        }

                        $analyzed_product_aisle = $this->AnalyzedProductsAisles->newEntity();
                        $analyzed_product_aisle->analyzed_product_id = $analyzed_product->id;
                        $analyzed_product_aisle->aisle_id = $aisle_data->id;
                        $analyzed_product_aisle->location_x = $seen_label['placement'][$x]['location_x'];
                        $analyzed_product_aisle->location_y = $seen_label['placement'][$x]['location_y'];
                        $analyzed_product_aisle->location_z = $seen_label['placement'][$x]['location_z'];
                        $analyzed_product_aisle->detection_id = $seen_label['detection_id'][$x];

                        if(!$this->AnalyzedProductsAisles->save($analyzed_product_aisle)){
                            $this->out(__('Error while saving the aisle for analyzed product'));
                            return false;
                        }
                    } 
                }
                //FIN Guardar pasillo si no tiene

                //Verificar si tiene ean, si no lo asigna al producto
                /*if($product_store->product->ean13 == null || $product_store->product->ean13_digit == null && $seen_label['ean'] != null){

                    //
                    $ean_original_length = strlen($seen_label['ean']);
                    $ean_new_length = $ean_original_length -1;
                    $product_store->product->ean13 = substr($seen_label['ean'], 0, $ean_new_length);
                    $product_store->product->ean13_digit = substr($seen_label['ean'], -1);

                    if($this->ProductsStores->Products->save($product_store->product)){
                        $this->out(__('Product {0} updated', $product_store->product->product_name));

                        if($upload_cloud == true){
                
                            $product_store->product->created = $product_store->product->created->format('Y-m-d H:i:s');
                            $product_store->product->modified = $product_store->product->modified->format('Y-m-d H:i:s');

                            $cloud_response = $this->uploadCloud($product_store->product, 'products');

                            if($cloud_response == false){
                                $this->out(__('Error while trying saving the product relation on Google BigQuery'));
                            }
                        }
                    }
                    else{
                        $this->out(__('Error on updated the product {0}', $product_store->product->product_name));
                    }
                }*/

                //Verificar si la relacion product_store tiene la fecha de la sessión la relacion, si no lo asigna a dicho product_store

                if($product_store->session_date == null){
                    $product_store->session_date = $process_date->format('Y-m-d');

                    if($this->ProductsStores->save($product_store)){
                        $this->out(__('Master product {0} session date updated', $product_store->product->product_name));

                        if($upload_cloud == true){
                    
                            $product_store->session_date = $process_date->format('Y-m-d');
                            $product_store->created = $product_store->created->format('Y-m-d H:i:s');
                            $product_store->modified = $product_store->modified->format('Y-m-d H:i:s');

                            $cloud_response = $this->uploadCloud($product_store, 'products_stores');

                            if($cloud_response == false){
                                $this->out(__('Error while trying saving the product relation on Google BigQuery'));
                            }
                        }
                    }
                    else{
                        $this->out(__('Error on updated the product {0}', $product_store->product->product_name));
                    }
                }

                if($upload_cloud == true){
                
                    if($analyzed_product->session_date != null){

                        $tmp_date = New Time($analyzed_product->session_date);
                        $analyzed_product->session_date = $tmp_date->format('Y-m-d H:i:s');
                    }

                    $analyzed_product->created = $analyzed_product->created->format('Y-m-d H:i:s');
                    $analyzed_product->modified = $analyzed_product->modified->format('Y-m-d H:i:s');

                    $cloud_response = $this->uploadCloud($analyzed_product, 'analyzed_products');

                    if($cloud_response == false){
                        $this->out(__('Error while trying saving the analyzed product relation on Google BigQuery'));
                    }
                }

                $this->out(__('<question>[Int. Code: {0}] Product: {1} product analized for Zippedi with ID: {2} saved on {3}</question>', [$product_store->company_internal_code, $product_store->product->product_name, $analyzed_product->id, date('d-m-Y H:i:s')]));

                return $analyzed_product;
            } 
        }
        else{
            return false;
        }
        
    }

    function generateNewProduct($product_name = null, $internal_code = null, $ean_code = null, $company_id = null, $store_id = null, $section_id = null, $category_id = null, $sub_category_id = null, $catalog_date = null, $cataloged = null, $enabled = null, $stock_up_to_date = null, $upload_cloud = false){

        //Object producto
        $product = $this->Products->newEntity();

        $product->product_name = utf8_encode(ucwords(strtolower($product_name)));
        $product->product_description = utf8_encode(ucwords(strtolower($product_name)));

        if($ean_code != null){
            $product->product_name = utf8_encode(ucwords(strtolower($product_name)));

            $ean_original_length = strlen($ean_code);
            $ean_new_length = $ean_original_length -1;
            $product->ean13 = substr($ean_code, 0, $ean_new_length);
            $product->ean13_digit = substr($ean_code, -1);
        }
        

        if ($this->Products->save($product)) {

            $this->out(__('<question>Product {0}: Saved successful on {1}</question>', [$product->product_name, date('d-m-Y H:i:s')]));

            //return $product;
            $master_date = new Time($catalog_date);

            $new_product_store = $this->ProductsStores->newEntity();
            $new_product_store->product_id = $product->id;
            $new_product_store->company_id = $company_id;
            $new_product_store->store_id = $store_id;
            $new_product_store->section_id = $section_id;
            $new_product_store->category_id = $category_id;
            $new_product_store->sub_category_id = $sub_category_id;
            $new_product_store->master_catalog_date = $master_date;
            $new_product_store->company_internal_code = $internal_code;
            $new_product_store->cataloged = ($cataloged != null) ? intval($cataloged) : null;
            $new_product_store->enabled = ($enabled != null) ? intval($enabled) : null;
            $new_product_store->stock_up_to_date = ($stock_up_to_date != null) ? intval($stock_up_to_date) : null;

            if(!$this->ProductsStores->save($new_product_store)){

                $this->out(__('<error>Error while saving the product {0} [SAP: {1}] and relationship</error>', [$product_name, $internal_code]));
                return false;
            }
            else{
                $this->out(__('<question>NEW Product {0}: Saved on cataloged products on {1}</question>', [$product->product_name, $master_date->format('d-m-Y')]));

                $new_product_store->product = $product; 

                return $new_product_store;
            }
        }
        else{
            $this->out(__('<error>Error while saving the product {0}</error>', [$product_name]));
            return false;
        }
    }

    /**
    **
    Busca la seccion por su codigo, de no encontrarla crea una nueva y devuelve el objeto
    **
    **/
    function getSectionData($company_id = null, $section_code = null, $section_name = null){

        $section = $this->ProductsStores->Sections->find('all', ['conditions' => ['Sections.section_code' => $section_code, 'Sections.company_id' => $company_id]])->select(['Sections.id', 'Sections.section_name', 'Sections.section_code'])->first();

        if(count($section) == 0){

            if($section_name == null){
                return null;
            }

            $section = $this->Sections->newEntity();
            $section->company_id = $company_id;
            $section->section_name = ucwords(strtolower($section_name));
            $section->section_code = ucwords(strtolower($section_code));

            if(!$this->Sections->save($section)){

                $this->out(__('Error while trying saved the section'));
                return false;
            }
        }

        return $section;
    }

    /**
    **
    Busca la categoria por su codigo, de no encontrarla crea una nueva y devuelve el objeto
    **
    **/
    function getCategoryData($company_id = null, $category_code = null, $category_name = null, $section_id = null){

        $category = $this->ProductsStores->Categories->find('all', ['conditions' => ['Categories.category_code' => $category_code, 'Categories.company_id' => $company_id, 'Categories.section_id' => $section_id]])->select(['Categories.id', 'Categories.category_code'])->first();

        

        if($category == null && $category_name != null && $section_id != null){

            if($category_name == null){
                return null;
            }

            $category = $this->Categories->newEntity();
            $category->company_id = $company_id;
            $category->section_id = $section_id;
            $category->category_name = ucwords(strtolower($category_name));
            $category->category_code = ucwords(strtolower($category_code));

            if(!$this->Categories->save($category)){

                $this->out(__('Error while trying saved the category'));
                return false;
            }
        }

        return $category;
    }


    function repairEans(){

        $products = $this->ProductsStores->find()
            ->select([
                'id', 'ean13'
            ])
            //->limit(150)
            ->toArray();

        foreach($products as $product){
            $original_ean_12 = substr($product->ean13, 0, 12);
            $original_verify_digit = substr($product->ean13, -1);

            $finded_digit = $this->ean13_checksum($original_ean_12);

            $this->out('Completo original: '.$product->ean13);

            if($finded_digit == $original_verify_digit){
                $this->out('Correct Digit');
            }
            else{

                $this->out('Bad Ean');

                $product->ean13 = $original_ean_12.$finded_digit;
                $this->ProductsStores->save($product);

                $this->out('Repair: '.$product->ean13);   
            }
        }
    }

    function ean13_checksum($message) {

        $checksum = 0;
            foreach (str_split(strrev($message)) as $pos => $val) {
            $checksum += $val * (3 - 2 * ($pos % 2));
        }
        return ((10 - ($checksum % 10)) % 10);
    }

    function refreshTotalDetections(){

        $companies = $this->Stores->Companies->find('all')
            ->contain([
                'Stores' => [
                    'queryBuilder' => function (\Cake\ORM\Query $query) {
                        return $query->where(['Stores.active' => 1]);
                    },
                    'RobotSessions'
                ]
            ])
            ->where([
                'Companies.active' => 1
            ])
            ->toArray();

        $robot_reports = new RobotReportsController();

        if(count($companies) > 0){
            foreach($companies as $company){
                if(count($company->stores) > 0){
                    foreach($company->stores as $store){

                        if(count($store->robot_sessions) > 0){
                            foreach($store->robot_sessions as $robot_session){
                                $session_full_data = $robot_reports->getSessionStadistics($store->store_code, $robot_session->session_code);

                                print_r($session_full_data);
                                print_r($robot_session->total_detections);

                                if(isset($session_full_data[0]['seen_labels']) && $robot_session->total_detections != $session_full_data[0]['seen_labels']){

                                    $this->out(__('se cambio cantidad de flejes de {0} a {1} en la sesión {2}', [$robot_session->total_detections, $session_full_data[0]['seen_labels'], $robot_session->session_code]));

                                    $robot_session->total_detections = $session_full_data[0]['seen_labels'];
                                    $this->RobotSessions->save($robot_session);
                                }
                                else{
                                    $this->out(__('No hay cambios/datos para la sesión {0}', [$robot_session->session_code]));
                                }
                            }
                        }
                    }
                }
            }
        }


        //$session_full_data = $robot_reports->getSessionStadistics($store->store_code, $robot_session->session_code);
    }


    function refreshDescription($store_code = null){

        if($store_code == null){
            $this->out(__('Store code not found'));
            return false;
        }

        $store_data = $this->Stores->find('all')
                ->contain([
                    'Companies'
                ])
                ->where([
                    'Stores.store_code' => $store_code
                ])
                ->first();

        //Si no existe tienda omite la fila
        if($store_data == null){
            
            $this->out(__('Store object not found'));
            return false;
        }

        $file_name = $store_data->store_code.'.csv';
        //Excel con maestra de cencosud
        $data_file = ROOT . DIRECTORY_SEPARATOR . 'webroot' . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . 'initials_prices'. DIRECTORY_SEPARATOR . $store_data->company->company_keyword . DIRECTORY_SEPARATOR . $file_name;

        if(!file_exists($data_file)) {
            $this->out(__('File not exist'));
            return false;
        }

        /* Lectura del excel */
        if(file_exists($data_file)){
            $filename = explode('.', $data_file);
            debug($filename);

            if($filename[1]=='csv'){
                
                $store_id = $store_data->id;
                $arr = [];
                $arr['new_products_count'] = 0;
                $arr['update_count'] = 0;
                $arr['keep_count'] = 0;
                $arr['initial_update_count'] = 0;


                $handle = fopen($data_file, "r");

                $x = 0;
                //Iteración productos de la api de precio de cencosud
                while (($original_row = fgetcsv($handle, 1000, ";")) !== FALSE){

                    if($x == 0){
                        $x++;
                        continue;
                    }

                    /*echo '<pre>';
                    print_r($original_row);
                    echo '</pre>';*/


                    if(count($original_row) == 1){
                        $row = explode(',', $original_row[0]);

                    }
                    else{
                        $row = $original_row;
                    }

                    /*echo '<pre>';
                    print_r($row);
                    echo '</pre>';*/

                    $product_store = $this->ProductsStores->find('all')
                        ->select([
                            'ProductsStores.id',
                            'ProductsStores.company_id',
                            'ProductsStores.section_id',
                            'ProductsStores.category_id',
                            'ProductsStores.sub_category_id',
                            'ProductsStores.internal_code',
                            'ProductsStores.description',
                            'ProductsStores.ean13'
                        ])
                        ->where([
                            'ProductsStores.company_id' => $store_data->company->id, 
                            'ProductsStores.internal_code' => $row[1], 
                            'ProductsStores.ean13' => $row[3].$row[4],
                        ])
                        ->first();

                    //if($product_store != null){

                        //$api_product_section_code = intval(substr($row[5], 0, 2));
                        //$api_product_category_code = intval(substr($row[5], 2, 2));
                        //$api_product_sub_category_code = intval(substr($row[5], 4, 2));

                        //Agregar product
                        //$section = $this->getSectionData($store_data->company_id, $api_product_section_code, $api_product_section_code);

                        //Se obtiene categoria
                        //$category = $this->getCategoryData($store_data->company_id, $api_product_category_code, $api_product_category_code, $section->id);

                        //Se obtiene sub categoria
                        //$sub_category = $this->getSubCategoryData($store_data->company_id, $api_product_sub_category_code, $api_product_sub_category_code, $category->id);

                        //$this->out(__('<info>Producto existente [INT.CODE: {0} EAN: {1} DESCRIPTION: {2}]</info>', [$row[1], $row[3].$row[4], utf8_encode(ucwords(strtolower($row[10])))]));

                        /*if($product_store->section_id == null && $section != null){
                            $product_store->section_id = $section->id;
                            $this->ProductsStores->save($product_store);

                            $this->out(__('<info>Se actualizo categoria 1 [INT.CODE: {0} EAN: {1} DESCRIPTION: {2}]</info>', [$row[1], $row[3].$row[4], utf8_encode(ucwords(strtolower($row[10])))]));
                        }

                        if($product_store->category_id == null && $category != null){
                            $product_store->category_id = $category->id;
                            $this->ProductsStores->save($product_store);

                            $this->out(__('<info>Se actualizo categoria 2 [INT.CODE: {0} EAN: {1} DESCRIPTION: {2}]</info>', [$row[1], $row[3].$row[4], utf8_encode(ucwords(strtolower($row[10])))]));
                        }

                        if($product_store->sub_category_id == null && $sub_category != null){
                            $product_store->sub_category_id = $sub_category->id;
                            $this->ProductsStores->save($product_store);

                            $this->out(__('<info>Se actualizo categoria 3 [INT.CODE: {0} EAN: {1} DESCRIPTION: {2}]</info>', [$row[1], $row[3].$row[4], utf8_encode(ucwords(strtolower($row[10])))]));
                        }*/

                        if($product_store != null && $product_store->description == '{}'){
                            $product_store->description = utf8_encode(ucwords(strtolower($row[10])));
                            $this->ProductsStores->save($product_store);

                            $this->out(__('<question>Se actualizo nombre [INT.CODE: {0} EAN: {1} DESCRIPTION: {2}]</question>', [$row[1], $row[3].$row[4], utf8_encode(ucwords(strtolower($row[10])))]));
                        }
                    //}
                }

                //print_r($arr);
            }
        } 
    }


    function doAssortmentProcess($store = null, $robot_session = null, $email = null){

        if($store == null){
            $this->out(__('No exist store'));
            return false;
        }

        if($robot_session == null){
            $this->out(__('No exist robot session'));
            return false;
        }

        $robot_reports = New RobotReportsController();

        $assort_date = new Time($robot_session->session_date->format('Y-m-d'));

        if($assort_date->isYesterday()){
            $assort_date->modify('+1 day');
        }

        $this->out(__('<info>Searching assortment for {0} - {1} into Zippedi API...</info>', [$assort_date->format('d-m-Y'), $store->store_code]));
        $catalogs = $robot_reports->getAssortment($store->store_code, $assort_date->format('Ymd'));

        $detection_process_result['loaded'] = 0;
        /*print_r($catalogs);
        print_r($assort_date);

        die();*/

        if(count($catalogs) > 0){

            $catalogsTable = TableRegistry::get('CatalogUpdates');
            //$stocksTable = TableRegistry::get('StockUpdates');

            $catalogs_to_add = [];
            $stocks_to_add = [];

            $x = 0;
            //$y= 0;
            $catalog_processed = 0;
            //$stock_processed = 0;
            
            $robot_session->assortment_processing = 1;
            $robot_session->assortment_processing_date = new Time();
            $robot_session->calendar_date = $assort_date;
            $this->RobotSessions->save($robot_session);

            $stats = [
                'total_catalogs' => 0,
                'total_catalog_readed_products' => 0,
                'total_catalog_unreaded_products' => 0,
                'total_catalog_readed_and_blocked_products' => 0,
                'total_catalog_unreaded_and_blocked_products' => 0,
            ];

            $this->out(__('Processing assortment for {0}: {1}', [$store->store_code, $assort_date->format('d-m-Y')]));

            $slackHelper = new SlackHelper(new \Cake\View\View());
            $slack_response = $slackHelper->message(__('[Assorment] Start - {0} {1}', [$store->store_code, $assort_date->format('d-m')]), 'reportes');

            foreach($catalogs as $catalog){

                if($catalog['store_status'] != '' || $catalog['store_status'] != null || count($catalog['store_status']) > 0){
                    
                    //Contarlo con dato de catalogado
                    $stats['total_catalogs'] = $stats['total_catalogs'] + 1;

                    //Fix para sodimac sin EAN
                    if($store->company->company_keyword != 'homecenter'){
                        
                        //Verificacion de producto
                        $product = $this->CatalogUpdates->ProductsStores->find('all')
                            ->select([
                                'ProductsStores.id',
                                'ProductsStores.section_id',
                                'ProductsStores.category_id',
                            ])
                            ->where([
                                'ProductsStores.company_id' => $store->company_id, 
                                'ProductsStores.internal_code' => $catalog['item'], 
                                'ProductsStores.ean13' => $catalog['ean']
                            ])
                            ->first();

                        if($product != null){
                            $catalogs_to_add[$x]['product_store_id'] = $product->id;
                            //$products[$product->ean13] = $product->id;



                            if(isset($catalog['category0']) && $catalog['category0'] != ''){
                                $section = $this->getSectionData($store->company_id, intval($catalog['category0']), $catalog['category0']);

                                if($section->id != $product->section_id){
                                    $product->section_id = $section->id;
                                    $this->ProductsStores->save($product);
                                }
                            }
                            else{
                                $section = null;
                            }

                            if(isset($catalog['category1']) && $catalog['category1'] != '' && $section != null){
                                $category = $this->getCategoryData($store->company_id, intval($catalog['category1']), $catalog['category1'], $section->id);

                                if($category->id != $product->category_id){
                                    $product->category_id = $category->id;
                                    $this->ProductsStores->save($product);
                                }
                            }
                            else{
                                $category = null;
                            }
                        }
                        else{

                            if(isset($catalog['category0']) && $catalog['category0'] != ''){
                                $section = $this->getSectionData($store->company_id, intval($catalog['category0']), $catalog['category0']);
                            }
                            else{
                                $section = null;
                            }

                            if(isset($catalog['category1']) && $catalog['category1'] != '' && $section != null){
                                $category = $this->getCategoryData($store->company_id, intval($catalog['category1']), $catalog['category1'], $section->id);
                            }
                            else{
                                $category = null;
                            }

                            $product = $this->CatalogUpdates->ProductsStores->newEntity();
                            $product->company_id = $store->company_id;
                            $product->section_id = ($section != null) ? $section->id : $section;
                            $product->category_id = ($category != null) ? $category->id : $category;
                            $product->description = utf8_encode(ucwords(strtolower($catalog['description'])));
                            $product->internal_code = $catalog['item'];
                            $product->ean13 = trim($catalog['ean']);

                            if(!$this->CatalogUpdates->ProductsStores->save($product)){

                                //$this->out(__('<error>Error while saving the product [EAN: {0} INT.CODE: {1}]</error>', [$product->ean13, $product->internal_code]));
                            }
                            else{
                                //$this->out(__('<success>Se agrego producto inicial [EAN: {0} INT.CODE: {1} DESCRIPTION: {2}]</success>', [$product->ean13, $product->internal_code, $product->description]));
                                
                                //Set fake ORM
                                $catalogs_to_add[$x]['product_store_id'] = $product->id;
                                //$products[$product->ean13] = $product->id;
                                //$product->price_updates = [];
                            }
                        }

                    }
                    else{

                        //Verificacion de producto
                        $product = $this->CatalogUpdates->ProductsStores->find('all')
                            ->select([
                                'ProductsStores.id',
                                'ProductsStores.section_id',
                                'ProductsStores.category_id',
                            ])
                            ->where([
                                'ProductsStores.company_id' => $store->company_id, 
                                'ProductsStores.internal_code' => $catalog['item']
                            ])
                            ->first();

                        if($product != null){
                            $catalogs_to_add[$x]['product_store_id'] = $product->id;
                            //$products[$product->internal_code] = $product->id;

                            if(isset($catalog['category0']) && $catalog['category0'] != ''){
                                $section = $this->getSectionData($store->company_id, intval($catalog['category0']), $catalog['category0']);

                                if($section->id != $product->section_id){
                                    $product->section_id = $section->id;
                                    $this->ProductsStores->save($product);
                                }
                            }
                            else{
                                $section = null;
                            }

                            if(isset($catalog['category1']) && $catalog['category1'] != '' && $section != null){
                                $category = $this->getCategoryData($store->company_id, intval($catalog['category1']), $catalog['category1'], $section->id);

                                if($category->id != $product->category_id){
                                    $product->category_id = $category->id;
                                    $this->ProductsStores->save($product);
                                }
                            }
                            else{
                                $category = null;
                            }
                        }
                        else{

                            if(isset($catalog['category0']) && $catalog['category0'] != ''){
                                $section = $this->getSectionData($store->company_id, intval($catalog['category0']), $catalog['category0']);
                            }
                            else{
                                $section = null;
                            }

                            if(isset($catalog['category1']) && $catalog['category1'] != '' && $section != null){
                                $category = $this->getCategoryData($store->company_id, intval($catalog['category1']), $catalog['category1'], $section->id);
                            }
                            else{
                                $category = null;
                            }

                            $product = $this->CatalogUpdates->ProductsStores->newEntity();
                            $product->company_id = $store->company_id;
                            $product->section_id = ($section != null) ? $section->id : $section;
                            $product->category_id = ($category != null) ? $category->id : $category;
                            $product->description = utf8_encode(ucwords(strtolower($catalog['description'])));
                            $product->internal_code = $catalog['item'];
                            $product->ean13 = null;

                            if(!$this->CatalogUpdates->ProductsStores->save($product)){

                                //$this->out(__('<error>Error while saving the product [EAN: {0} INT.CODE: {1}]</error>', [$product->ean13, $product->internal_code]));
                            }
                            else{
                                //$this->out(__('<success>Se agrego producto inicial [EAN: {0} INT.CODE: {1} DESCRIPTION: {2}]</success>', [$product->ean13, $product->internal_code, $product->description]));
                                
                                //Set fake ORM
                                $catalogs_to_add[$x]['product_store_id'] = $product->id;
                                //$products[$product->internal_code] = $product->id;
                                //$product->price_updates = [];
                            }
                        }
                    }

                    $catalogs_to_add[$x]['store_id'] = $store->id;
                    $catalogs_to_add[$x]['product_store_id'] = $product->id;
                    $catalogs_to_add[$x]['enabled'] = ($catalog['store_status']['enabled_purchase'] == true) ? 1 : 0;
                    $catalogs_to_add[$x]['cataloged'] = ($catalog['store_status']['enabled_sale'] == true) ? 1 : 0;
                    $catalogs_to_add[$x]['catalog_date'] = $assort_date->format('Y-m-d H:i:s');
                    $catalogs_to_add[$x]['seen'] = ($catalog['robot_status']['seen'] == true) ? 1 : 0;

                    if($catalog['robot_status']['seen'] == true){
                        $catalogs_to_add[$x]['times_seen'] = $catalog['robot_status']['times_seen'];

                        $last_seen_date_object = New Time(substr($catalog['robot_status']['last_seen'], 0, 4).'-'.substr($catalog['robot_status']['last_seen'], 4, 2).'-'.substr($catalog['robot_status']['last_seen'], 6, 2));

                        /*echo $catalog['robot_status']['last_seen'];
                        print_r($last_seen_date_object);
                        die();*/
                        $catalogs_to_add[$x]['last_seen'] = $last_seen_date_object->format('Y-m-d H:i:s');

                        $stats['total_catalog_readed_products'] = $stats['total_catalog_readed_products'] + 1;


                        if($catalog['store_status']['enabled_purchase'] != true){
                            $stats['total_catalog_readed_and_blocked_products'] = $stats['total_catalog_readed_and_blocked_products'] + 1;
                        }
                    }
                    else{
                        $stats['total_catalog_unreaded_products'] = $stats['total_catalog_unreaded_products'] + 1;

                        if($catalog['store_status']['enabled_purchase'] != true){
                            $stats['total_catalog_unreaded_and_blocked_products'] = $stats['total_catalog_unreaded_and_blocked_products'] + 1;
                        }
                    }


                    if(isset($catalog['store_status']['stock_available'])){
                        
                        $catalogs_to_add[$x]['stock'] = (isset($catalog['store_status']['stock_available'])) ? $catalog['store_status']['stock_available'] : null;

                        //Stock
                        /*$stocks_to_add[$y]['store_id'] = $store->id;
                        $stocks_to_add[$y]['product_store_id'] = $product->id;
                        $stocks_to_add[$y]['current_stock'] = (isset($catalog['store_status']['stock_available'])) ? $catalog['store_status']['stock_available'] : null;
                        $stocks_to_add[$y]['stock_updated'] = $robot_session->session_date->format('Y-m-d H:i:s');

                        $y++;*/
                    }

                    $x++;
                }
                else{
                    //$stats['total_catalog_without_store_status']++;
                }

                if(count($catalogs_to_add) == 3000){

                    $catalog_processed = $catalog_processed + 3000;
                    $entities = $catalogsTable->newEntities($catalogs_to_add);
                    if($catalogsTable->saveMany($entities)){
                        $this->out(__('{0} / {1} Catalogs saved', [$catalog_processed, count($catalogs)]));

                        $x = 0;
                        $catalogs_to_add = [];
                    }
                    else{
                        $this->out('Catalogs not saved');
                    }
                }

                /*if(count($stocks_to_add) == 3000){

                    $stock_processed = $stock_processed + 3000;
                    $entities = $stocksTable->newEntities($stocks_to_add);
                    if($stocksTable->saveMany($entities)){
                        $this->out(__('{0} / {1} Stocks saved', [$stock_processed, count($catalogs)]));

                        $x = 0;
                        $stocks_to_add = [];
                    }
                    else{
                        $this->out('Stocks not saved');
                    }
                }*/
            }

            if(count($catalogs_to_add) > 0){

                $catalog_processed = $catalog_processed + count($catalogs_to_add);
                $entities = $catalogsTable->newEntities($catalogs_to_add);
                if($catalogsTable->saveMany($entities)){
                    $this->out(__('{0} / {1} Catalogs saved', [$catalog_processed, count($catalogs)]));
                }
                else{
                    $this->out('Catalogs not saved');
                }
            }

            /*if(count($stocks_to_add) > 0){

                $stock_processed = $stock_processed + count($stocks_to_add);
                $entities = $stocksTable->newEntities($stocks_to_add);
                if($stocksTable->saveMany($entities)){
                    $this->out(__('{0} / {1} Stocks saved', [$stock_processed, count($catalogs)]));

                    $x = 0;
                    $stocks_to_add = [];
                }
                else{
                    $this->out('Stocks not saved');
                }
            }*/

            print_r($stats['total_catalogs']);

            if($stats['total_catalogs'] > 0){
                $send_email = true;

                $detection_process_result['loaded'] = $detection_process_result['loaded'] + 1;

                $robot_session->total_catalogs = $stats['total_catalogs'];
                $robot_session->total_catalog_readed_products = $stats['total_catalog_readed_products'];
                $robot_session->total_catalog_unreaded_products = $stats['total_catalog_unreaded_products'];
                $robot_session->total_catalog_readed_and_blocked_products = $stats['total_catalog_readed_and_blocked_products'];
                $robot_session->total_catalog_unreaded_and_blocked_products = $stats['total_catalog_unreaded_and_blocked_products'];
                
                $robot_session->assortment_processing = 0;
                $robot_session->assortment_finished = 1;
                $robot_session->assortment_finished_date = new Time();
                $this->RobotSessions->save($robot_session);

                //print_r($slack_response);

                $this->out(__('All Assortment reports from {0} - {1} Processed', [$store->store_code, $assort_date->format('d-m-Y')]));
            }
            else{
                $send_email = false;

                $robot_session->total_catalogs = null;
                $robot_session->total_catalog_readed_products = null;
                $robot_session->total_catalog_unreaded_products = null;
                $robot_session->total_catalog_readed_and_blocked_products = null;
                $robot_session->total_catalog_unreaded_and_blocked_products = null;

                $robot_session->assortment_processing_date = null;
                $robot_session->assortment_processing = 0;
                $robot_session->assortment_finished = 0;
                $robot_session->assortment_finished_date = null;
                $this->RobotSessions->save($robot_session);

                $detection_process_result['loaded'] = 0;
            }

            //Generar PDF Surtido
            //Enviar email
            if($send_email == true){

                $chart_array= [];
                $files= [];

                $sections = $this->RobotSessions->Stores->Companies->Sections->find()
                    ->where([
                        'Sections.company_id' => $store->company->id,
                        'Sections.enabled' => 1
                    ])
                    ->toArray();

                $mail_data = [
                    'store' => [
                        'store_name' => $store->store_name,
                        'store_code' => $store->store_code,
                    ],
                    'company' => [
                        'company_name' => $store->company->company_name,
                        'company_keyword' => $store->company->company_keyword,
                        'company_logo' => $store->company->company_logo,
                    ],
                    'sections' => $sections,
                    'robot_session' => $robot_session,
                    //'last_robot_session' => $last_robot_session,
                    'chart_array' => $chart_array,
                    'type_report' => 'assortmentReport'
                ];

                $slackHelper = new SlackHelper(new \Cake\View\View());
                $slack_response = $slackHelper->message(__('[Assorment] End - {0} {1}: {2} catalog products', [$store->store_code, $assort_date->format('d-m'), $catalog_processed]), 'reportes', ":sunglasses:");

                //print_r($files);

                //$this->out('Mails');
                $email = new EmailsController;
                
                //Nuevo mailing 
                $email->sendActiveAssortmentEmail($mail_data);

                if($store->company->company_keyword == 'jumbo'){
                    $email->sendNegativeAssortmentEmail($mail_data);
                    //$email->sendBlockedAssortmentEmail($mail_data);
                }
            }

        }
        else{
            //$this->out(__('No exist records for {0} to {1}', [$store->store_code, $assort_date->format('d-m-Y')]));

            //$slackHelper = new SlackHelper(new \Cake\View\View());
            //$slack_response = $slackHelper->message(__('[Assortment] Error - {0} {1}: empty response from Zippedi API', [$store->store_code, $robot_session->session_date->format('d-m')]), 'reportes');

            $this->out(__('<error>Empty response to assortment for {0} - {1} into Zippedi API</error>', [$assort_date->format('d-m-Y'), $store->store_code]));
        }

        return $detection_process_result;
    }

    public function printLabels($company_keyword = null){

        if($company_keyword == null){
            $this->out(__('You must provide a company keyword'));
        }

        switch ($company_keyword) {
            case 'homecenter':
                $this->Sodimac->printLabels();
                break;
            
            default:
                # code...
                break;
        }

    }
}