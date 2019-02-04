<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Network\Http\Client;
use Cake\Core\Configure;
use Cake\I18n\Time;

use stdClass;

/**
 * RobotReports Controller
 *
 * @property \App\Model\Table\RobotReportsTable $RobotReports
 *
 * @method \App\Model\Entity\RobotReport[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class RobotReportsController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
        $this->loadModel('Stores');
    }

    var $auth_data = array(
        'username' => 'zippedi',
        'password' => 'YuL1fcfQQIwFL6Es2Zx'
    );

    //var $endpoint = 'https://reports2.zippedi.cl';
    var $endpoint = 'https://reports.zippedi.cl';

    public function phpinfo(){
        phpinfo();
        die();
    }

    public function index()
    {
        $this->viewBuilder()->setLayout('new_default');
        $this->set('robotReports', $this->RobotReports->find('all')->order(['RobotReports.report_name' => 'ASC'])->toArray());
    }

    /********** FORMS REPORT **************/
    public function assortmentReport($store_id = null){

        $this->viewBuilder()->setLayout('new_default');
        $this->loadModel('Companies');
        
        $user_companies_ids = $this->Companies->getAuthIds($this->request->session()->read('Auth.User.id'));

        if(count($user_companies_ids) == 0){
            $this->Flash->warning(__("You don't have associated stores to generate the assortment report").'.');
            return $this->redirect($this->referer());
        }

        $this->set('companies_list', $this->Companies->find('list', ['conditions' => ['Companies.id IN' => $user_companies_ids], 'order' => ['Companies.company_name' => 'ASC']]));

        if($store_id != null){
            
            $this->loadModel('Stores');
            $store = $this->Stores->get($store_id, ['contain' => ['Companies']]);

            if($store){

                $this->loadModel('Sections');
                $sections = $this->Sections->find('list', ['conditions' => ['Sections.company_id' => $store->company_id, 'Sections.section_name <>' => ''], 'order' => ['Sections.section_name' => 'ASC']])->toArray();

                $sections_list = ['all' => __('All'), $sections];
                $this->set('stores_list', $this->Stores->find('list', ['order' => ['Stores.store_name' => 'ASC']]));
                $this->set('sections_list', $sections_list);

                $this->set('company_default', $store->company_id);
                $this->set('store_default', $store->id);
            }
            
        }
    }

    public function priceDifferenceReport($store_id = null){
        
        $this->viewBuilder()->setLayout('new_default');
        $this->loadModel('Companies');

        $user_companies_ids = $this->Companies->getAuthIds($this->request->session()->read('Auth.User.id'));

        if(count($user_companies_ids) == 0){
            $this->Flash->warning(__("You don't have associated stores to generate the price difference report").'.');
            return $this->redirect($this->referer());
        }

        $this->set('companies_list', $this->Companies->find('list', ['conditions' => ['Companies.id IN' => $user_companies_ids], 'order' => ['Companies.company_name' => 'ASC']]));

        $enable_to_update = false;

        if($this->request->session()->read('Auth.User.id') != '' && $this->request->session()->read('Auth.User.is_admin')){
            $enable_to_update = true;
        }

        $this->set('enable_to_update', $enable_to_update);

        if($store_id != null){
            
            $this->loadModel('Stores');
            $store = $this->Stores->get($store_id, ['contain' => ['Companies']]);

            if($store){

                $this->set('stores_list', $this->Stores->find('list', ['order' => ['Stores.store_name' => 'ASC']]));


                $sessions_data = $this->getSessionsList($store_id, true);
                $sessions_data = array_unique($sessions_data);

                krsort($sessions_data);

                $this->set('sessions_list', $sessions_data);
                $this->set('company_default', $store->company_id);
                $this->set('store_default', $store->id);
            }
            
        }
    }

    public function stockOutReport($store_id = null)
    {
        $this->viewBuilder()->setLayout('new_default');
        $this->loadModel('Companies');
        
        $user_companies_ids = $this->Companies->getAuthIds($this->request->session()->read('Auth.User.id'));

        if(count($user_companies_ids) == 0){
            $this->Flash->warning(__("You don't have associated stores to generate the stock out report").'.');
            return $this->redirect($this->referer());
        }

        $this->set('companies_list', $this->Companies->find('list', ['conditions' => ['Companies.id IN' => $user_companies_ids], 'order' => ['Companies.company_name' => 'ASC']]));

        $enable_to_update = false;

        if($this->request->session()->read('Auth.User.id') != '' && $this->request->session()->read('Auth.User.is_admin')){
            $enable_to_update = true;
        }

        $this->set('enable_to_update', $enable_to_update);

        if($store_id != null){
            
            $this->loadModel('Stores');
            $store = $this->Stores->get($store_id, ['contain' => ['Companies']]);

            if($store){

                $this->set('stores_list', $this->Stores->find('list', ['order' => ['Stores.store_name' => 'ASC']]));


                $sessions_data = $this->getSessionsList($store_id, true);
                $sessions_data = array_unique($sessions_data);

                krsort($sessions_data);

                $this->set('sessions_list', $sessions_data);
                $this->set('company_default', $store->company_id);
                $this->set('store_default', $store->id);
            }
            
        }
    }

    /********** END FORMS REPORT **************/

    /**
    // Get the stores list that Zippedi has active
    **/
    public function getStoresList(){

        $http = new Client();
        $request_token = $http->post($this->endpoint.'/auth', json_encode($this->auth_data), ['type' => 'json', 'ssl_verify_peer' => false, 'timeout' => 180]);
        $url = $this->endpoint.'/status/store_list';

        $http = new Client([
            'headers' => ['Authorization' => 'jwt ' . $request_token->json['access_token']]
        ]);

        $response = $http->get($url);

        if($response->getStatusCode() != 200){

            $this->Flash->error('Error '.$response->code);
            return $this->redirect(['controller' => 'RobotReports', 'action' => 'index']);
        }

        return $response->json;
    }

    public function getSessionStadistics($store_code = null, $session_code = false, $show = false){

        if($store_code == null){
            $this->Flash->error(__('The store code cannot be null'));
            return $this->redirect(['controller' => 'Dashboard', 'action' => 'index']);
        }

        $http = new Client();

        try {
            $request_token = $http->post($this->endpoint.'/auth', json_encode($this->auth_data), ['type' => 'json', 'ssl_verify_peer' => false, 'timeout' => 90]);
        } catch (\Exception $e) {
            // error
        }
        
        $url = $this->endpoint.'/results/statistics';

        $http = new Client([
            'headers' => ['Authorization' => 'jwt ' . $request_token->json['access_token']]
        ]);


        try {
            $robot_response = $http->get($url, ['store' => $store_code, 'session' => $session_code], ['ssl_verify_peer' => false, 'timeout' => 90]);
            if($robot_response->getStatusCode() != 200){
                //echo 'holi';
                /*$response = new \stdClass();
                $response->status = false;
                $response->error = __('Error {0}', $robot_response->code);
                $response->message = '';
                $response->data = [
                    'sessions' => $robot_response->json
                ];

                $this->response->type('json');
                $this->response->body(json_encode($response));*/
                $arr = json_decode($robot_response->body, true);

                return $arr;
            }

            $arr = json_decode($robot_response->json, true);

            if($show == true){

                echo '<pre>';
                print_r($arr);
                echo '</pre>';
                die();
            }
            else{
                return $arr;
            }
        } catch (\Exception $e) {
            // error
        }
    
    }

    function downloadPdf($report_keyword = null){

        $report = $this->RobotReports->find('all')->select(['RobotReports.report_keyword'])->where(['RobotReports.report_keyword' => $report_keyword])->first();

        $response = $this->assortmentReportPdf();

        /*switch ($report->report_keyword) {
            case 'assortmentReport':
                $response = $this->assortmentReportPdfReport();
                break;
            
            default:
                echo 'nada';
                die();
            //return $this->redirect();
                break;
        }*/


        
    }

    function assortmentReportPdfFile($invoice = 'asgs'){

        $this->viewBuilder()->options([
            'pdfConfig' => [
                'orientation' => 'portrait',
                'filename' => 'hola.pdf'
            ]
        ]);
        $this->set('invoice', $invoice);

        //die();
    }

    /**
    // Get the sessions list that Zippedi has made
    **/
    public function getSessionsList($store_code = null, $list = false, $ajax = false, $show = false){

        if($store_code == null){
            $this->Flash->error(__('The store code cannot be null'));
            return $this->redirect(['controller' => 'Dashboard', 'action' => 'index']);
        }

        $http = new Client();
        $request_token = $http->post($this->endpoint.'/auth', json_encode($this->auth_data), ['type' => 'json', 'ssl_verify_peer' => false, 'timeout' => 180]);
        $url = $this->endpoint.'/status/session_list';

        $http = new Client([
            'headers' => ['Authorization' => 'jwt ' . $request_token->json['access_token']]
        ]);

        $this->loadModel('Stores');
        $store_data = $this->Stores->find('all')->select(['Stores.id', 'Stores.store_code'])->where(['Stores.id' => $store_code])->orWhere(['Stores.store_code' => $store_code])->first();


        $robot_response = $http->get($url, ['store' => $store_data->store_code], ['ssl_verify_peer' => false, 'timeout' => 180]);

        if($robot_response->getStatusCode() != 200){

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
        }

        if($show == true){

            $sessions = json_decode($robot_response->body, true);
            echo '<pre>';
            print_r($sessions);
            echo '</pre>';
            die();
        }
        else{
            
            if($robot_response->json != ''){
                $sessions = $robot_response->json;
            }
            else{
                $sessions = json_decode($robot_response->body, true);
            }
            

            if($list == true){
                $sessions_list = [];

                if($store_data->store_code == 'HC67' || $store_data->store_code == 'L739'){
                    foreach ($robot_response->json as $session) {
                        if($session['includes_qa'] == 1){
                            $date_session = New Time(substr($session['date'], 0, 4).'-'.substr($session['date'], 4, 2).'-'.substr($session['date'], 6, 2));

                            $sessions_list[] = $date_session->format('Y-m-d');
                        }
                        
                    }
                }
                else{
                    foreach ($robot_response->json as $session) {
                        if($session['is_test'] == '' && $session['includes_qa'] == 1){
                            $date_session = New Time(substr($session['date'], 0, 4).'-'.substr($session['date'], 4, 2).'-'.substr($session['date'], 6, 2));

                            $sessions_list[] = $date_session->format('Y-m-d');
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
                return $sessions;
            }

            
        }
    }

    /**
    // Get the all data of the sessions list that Zippedi has made
    **/
    public function getFullSessionsList($store_code = null, $session_code = null, $date = null, $days = null, $list = false, $ajax = false, $show = false){

        if($store_code == null){
            $this->Flash->error(__('The store code cannot be null'));
            return $this->redirect(['controller' => 'Dashboard', 'action' => 'index']);
        }

        $http = new Client();
        $request_token = $http->post($this->endpoint.'/auth', json_encode($this->auth_data), ['type' => 'json', 'ssl_verify_peer' => false, 'timeout' => 180]);
        $url = $this->endpoint.'/status/full';

        $http = new Client([
            'headers' => ['Authorization' => 'jwt ' . $request_token->json['access_token']]
        ]);

        $this->loadModel('Stores');
        $store_data = $this->Stores->find('all')->select(['Stores.id', 'Stores.store_code'])->where(['Stores.id' => $store_code])->orWhere(['Stores.store_code' => $store_code])->first();

        $opts = [];

        if($session_code != null){
            $opts['session'] = $session_code;
        }

        if($date != null){
            $opts['date'] = $date;
        }

        if($days != null){
            $opts['days'] = $days;
        }


        $robot_response = $http->get($url, ['store' => $store_data->store_code, $opts], ['ssl_verify_peer' => false, 'timeout' => 180]);

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

        $robot_response->body = str_replace('NaN', 'null', $robot_response->body);

        //print_r($robot_response->body);
        $robot_response->body = json_decode($robot_response->body, true); 


        //die('oki');

        if($show == true){
            echo '<pre>';
            print_r($robot_response->body);
            echo '</pre>';
            die();
        }
        else{

            //$sessions = $robot_response->json;
            $sessions = $robot_response->body;

            if($list == true){
                $sessions_list = [];

                if($store_data->store_code == 'HC67' || $store_data->store_code == 'L739'){
                    foreach ($robot_response->json as $session) {
                        if($session['includes_qa'] == 1){
                            $date_session = New Time(substr($session['date'], 0, 4).'-'.substr($session['date'], 4, 2).'-'.substr($session['date'], 6, 2));

                            $sessions_list[] = $date_session->format('Y-m-d');
                        }
                        
                    }
                }
                else{
                    foreach ($robot_response->json as $session) {
                        if($session['is_test'] == '' && $session['includes_qa'] == 1){
                            $date_session = New Time(substr($session['date'], 0, 4).'-'.substr($session['date'], 4, 2).'-'.substr($session['date'], 6, 2));

                            $sessions_list[] = $date_session->format('Y-m-d');
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
                return $sessions;
            }

            
        }
    }

    /**
    // Get the session code by date
    **/
    public function getSessionStatus($store_code = null, $session_code = null, $show = false){

        if($store_code == null){
            $this->Flash->error(__('The store code cannot be null'));
            return $this->redirect(['controller' => 'Dashboard', 'action' => 'index']);
        }

        if($session_code == null){
            $this->Flash->error(__('The session_code cannot be null'));
            return $this->redirect(['controller' => 'Dashboard', 'action' => 'index']);
        }

        $http = new Client();

        try {
            $request_token = $http->post($this->endpoint.'/auth', json_encode($this->auth_data), ['type' => 'json', 'ssl_verify_peer' => false, 'timeout' => 90]);
        } catch (\Exception $e) {
            // error
        }
        
        $url = $this->endpoint.'/status/session';

        $http = new Client([
            'headers' => ['Authorization' => 'jwt ' . $request_token->json['access_token']]
        ]);

        $this->loadModel('Stores');
        $store_data = $this->Stores->find('all')->select(['Stores.id', 'Stores.store_code'])->where(['Stores.id' => $store_code])->orWhere(['Stores.store_code' => $store_code])->first();


        try {
            $robot_response = $http->get($url, ['store' => $store_data->store_code, 'session' => $session_code], ['ssl_verify_peer' => false, 'timeout' => 90]);

            if($robot_response->getStatusCode() != 200){

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
            }

            if($show == true){
                echo '<pre>';
                print_r($robot_response->json);
                echo '</pre>';

                die();
            }
            
            return $robot_response->json;     

        } catch (\Exception $e) {
            // error
        }   
    }

    /**
    // Get the session data that Zippedi has made
    **/
    public function getSessionData($store_code = null, $session_code = null){

        if($store_code == null){
            $this->Flash->error(__('The store code cannot be null'));
            return $this->redirect(['controller' => 'Dashboard', 'action' => 'index']);
        }

        if($session_code == null){
            $this->Flash->error(__('The session code cannot be null'));
            return $this->redirect(['controller' => 'Dashboard', 'action' => 'index']);
        }

        $http = new Client();
        $request_token = $http->post($this->endpoint.'/auth', json_encode($this->auth_data), ['type' => 'json', 'ssl_verify_peer' => false, 'timeout' => 180]);
        $url = $this->endpoint.'/status/session';

        $http = new Client([
            'headers' => ['Authorization' => 'jwt ' . $request_token->json['access_token']]
        ]);

        $response = $http->get($url, ['store' => $store_code, 'session' => $session_code], ['ssl_verify_peer' => false, 'timeout' => 180]);

        if($response->getStatusCode() != 200){

            $this->Flash->error('Error '.$response->code.': '.$response->json['message']);
            return $this->redirect(['controller' => 'RobotReports', 'action' => 'index']);
        }

        return $response->json;
    }

    public function getAllLabelsSumary($supermarket_code = null, $session_code = null, $update = false, $show = false){

        if($supermarket_code == null){
            return false;
        }

        if($session_code == null){
            return false;
        }


        $json_route = ROOT . DIRECTORY_SEPARATOR . 'logs'. DIRECTORY_SEPARATOR .'seen_labels_'.$session_code.'_'.$supermarket_code.'.json';

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
        $request_token = $http->post($this->endpoint.'/auth', json_encode($auth_data), ['type' => 'json', 'ssl_verify_peer' => false, 'timeout' => 180]);

        $http = new Client([
            'headers' => ['Authorization' => 'jwt ' . $request_token->json['access_token']]
        ]);

        $url = $this->endpoint.'/results/summary';
        $response = $http->get($url, ['store' => $supermarket_code, 'session' => $session_code], ['ssl_verify_peer' => false, 'timeout' => 180]);

        /*echo '<pre>';
        print_r($response);
        echo '</pre>';
        $response->json = json_decode($response->json, true);

        echo '<pre>';
        print_r($$response->json);
        echo '</pre>';

        echo '<pre>';
        print_r($$response->body);
        echo '</pre>';*/

        $arr = [];
        /*if($response->getStatusCode() != 200){

            $this->Flash->error('Error '.$response->code);
            return $this->redirect(['controller' => 'RobotReports', 'action' => 'index']);
        }*/
        $update = false;

        if(isset($response->json[0]['message'])){
            return $arr;
        }
        else{

            $arr = json_decode($response->json, true);

            if(file_exists($json_route) && $update == true){
                unlink($json_route);
            }
                    
            $fp = fopen($json_route, 'w');
            fwrite($fp, json_encode($arr, JSON_PRETTY_PRINT));
            fclose($fp);

        }

        if($show == true){
            $arr = str_replace('NaN', 'null', $response->json);
            $arr = json_decode($response->json, true); 

            echo '<pre>';
            print_r($arr);
            echo '</pre>';

            echo '<pre>';
            print_r($response);
            echo '</pre>';

            echo '<pre>';
            print_r($response->body);
            echo '</pre>';

            echo '<pre>';
            print_r($response->json);
            echo '</pre>';
            die();
        }
        else{
            $arr = str_replace('NaN', 'null', $response->json);
            $arr = json_decode($response->json, true); 
            return $arr;
        }
    }

    /****
    Get all labels that zippedi saw
    *****/
    public function getSeenProducts($supermarket_code = null, $session_code = null, $show = false){

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
        $request_token = $http->post($this->endpoint.'/auth', json_encode($auth_data), ['type' => 'json', 'ssl_verify_peer' => false, 'timeout' => 180]);

        $http = new Client([
            'headers' => ['Authorization' => 'jwt ' . $request_token->json['access_token']]
        ]);

        $url = $this->endpoint.'/results/seen_products';
        $response = $http->get($url, ['store' => $supermarket_code, 'session' => $session_code], ['ssl_verify_peer' => false, 'timeout' => 180]);

        /*if($response->getStatusCode() != 200){

            $this->Flash->error('Error '.$response->code);
            return $this->redirect(['controller' => 'RobotReports', 'action' => 'index']);
        }*/

        /*if($supermarket_code == 'HC67'){
            //$response->body = str_replace('NaN', 'null', $response->body);
            $arr = json_decode($response->json, true); 
            return $arr;
        }*/


        if($show == true){
            echo '<pre>';
            print_r($response);
            echo '</pre>';

            /*echo '<pre>';
            print_r($response->body);
            echo '</pre>';*/
            
            $arr = str_replace('NaN', 'null', $response->json);
            $arr = json_decode($response->json, true); 

            echo '<pre>';
            print_r($arr);
            echo '</pre>';

            echo '<pre>';
            print_r($response->json);
            echo '</pre>';
            die();
        }
        else{
            //$response->json = str_replace('NaN', null, $response->json);
            $arr = json_decode($response->json, true); 
            return $arr;
        }
    }

    public function getSeenLabels($supermarket_code = null, $session_code = null, $update = false, $show = false){

        if($supermarket_code == null){
            return false;
        }

        if($session_code == null){
            return false;
        }


        $json_route = ROOT . DIRECTORY_SEPARATOR . 'logs'. DIRECTORY_SEPARATOR .'seen_labels_'.$session_code.'_'.$supermarket_code.'.json';

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
        $request_token = $http->post($this->endpoint.'/auth', json_encode($auth_data), ['type' => 'json', 'ssl_verify_peer' => false, 'timeout' => 180]);

        $http = new Client([
            'headers' => ['Authorization' => 'jwt ' . $request_token->json['access_token']]
        ]);

        $url = $this->endpoint.'/results/seen_labels';
        $response = $http->get($url, ['store' => $supermarket_code, 'session' => $session_code], ['ssl_verify_peer' => false, 'timeout' => 180]);

        /*if($response->getStatusCode() != 200){

            $this->Flash->error('Error '.$response->code);
            return $this->redirect(['controller' => 'RobotReports', 'action' => 'index']);
        }*/
        $update = false;

        if(isset($response->json[0]['message'])){
            return $arr;
        }
        else{

            $arr = json_decode($response->json, true);

            if(file_exists($json_route) && $update == true){
                unlink($json_route);
            }
                    
            $fp = fopen($json_route, 'w');
            fwrite($fp, json_encode($arr, JSON_PRETTY_PRINT));
            fclose($fp);

        }

        /*if($supermarket_code == 'HC67'){
            //$response->body = str_replace('NaN', 'null', $response->body);
            $arr = json_decode($response->json, true); 
            return $arr;
        }*/


        if($show == true){
            $arr = str_replace('NaN', 'null', $response->json);
            $arr = json_decode($response->json, true); 

            echo '<pre>';
            print_r($arr);
            echo '</pre>';

            echo '<pre>';
            print_r($response);
            echo '</pre>';

            echo '<pre>';
            print_r($response->body);
            echo '</pre>';

            echo '<pre>';
            print_r($response->json);
            echo '</pre>';
            die();
        }
        else{
            $arr = str_replace('NaN', 'null', $response->json);
            $arr = json_decode($response->json, true); 
            return $arr;
        }
    }

    /****
    Get all labels that zippedi saw with difference price
    *****/
    public function getPriceDifferences($supermarket_code = null, $session_code = null, $show = false){

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
        $request_token = $http->post($this->endpoint.'/auth', json_encode($auth_data), ['type' => 'json', 'ssl_verify_peer' => false, 'timeout' => 180]);

        $http = new Client([
            'headers' => ['Authorization' => 'jwt ' . $request_token->json['access_token']]
        ]);

        $url = $this->endpoint.'/reports/price_differences';
        $response = $http->get($url, ['store' => $supermarket_code, 'session' => $session_code, 'source' => 'q'], ['ssl_verify_peer' => false, 'timeout' => 180]);

        $json_route = ROOT . DIRECTORY_SEPARATOR . 'logs'. DIRECTORY_SEPARATOR .'price_differences_'.$session_code.'_'.$supermarket_code.'.json';



        /*if($response->getStatusCode() != 200){

            $this->Flash->error('Error '.$response->code.': '.);
            return $this->redirect(['controller' => 'RobotReports', 'action' => 'index']);
        }*/

        $response->json = json_decode($response->json, true);

        if($show == true){
            echo '<pre>';
            print_r($response);
            echo '</pre>';

            echo '<pre>body';
            print_r($response->body);
            echo '</pre>';

            echo '<pre>json';
            print_r($response->json);
            echo '</pre>';
            die();
        }
        else{

            $arr = [];

            if(isset($response->json[0]['message'])){
                return $arr;
            }
            else{

                //$arr = json_decode($response->json, true);

                if(file_exists($json_route)){
                    unlink($json_route);
                }
                        
                $fp = fopen($json_route, 'w');
                fwrite($fp, json_encode($response->json, JSON_PRETTY_PRINT));
                fclose($fp);

            }
            return $response->json;
        }
    }

    /****
    Get all labels that zippedi saw with difference price
    *****/
    public function getStockOut($supermarket_code = null, $session_code = null, $show = false){

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
        $request_token = $http->post($this->endpoint.'/auth', json_encode($auth_data), ['type' => 'json', 'ssl_verify_peer' => false, 'timeout' => 180]);

        $http = new Client([
            'headers' => ['Authorization' => 'jwt ' . $request_token->json['access_token']]
        ]);

        $url = $this->endpoint.'/reports/stockout';
        $robot_response = $http->get($url, ['store' => $supermarket_code, 'session' => $session_code], ['ssl_verify_peer' => false, 'timeout' => 180]);

        if($robot_response->getStatusCode() != 200){

            $response = new \stdClass();
            $response->status = false;
            $response->error = __('Error '.$robot_response->code);

            $this->response->type('json');
            $this->response->body(json_encode($response));

            return $this->response;
        }

        $store_data = $this->Stores->find('all')
            ->select(['Stores.id', 'Stores.company_id','Stores.store_code'])
            ->where(['Stores.store_code' => $supermarket_code])
            ->first();

        $json_route = ROOT . DIRECTORY_SEPARATOR . 'logs'. DIRECTORY_SEPARATOR .'stock_out_'.$store_data->company_id.$store_data->id.$session_code.'.json';

        $arr = [];

        
        if(isset($robot_response->json[0]['message'])){

            return $arr;
        }
        else{

            $arr = json_decode($robot_response->json, true);

            if($show == true){
                echo '<pre>';
                print_r($robot_response);
                echo '</pre>';

                echo '<pre>body';
                print_r($robot_response->body);
                echo '</pre>';

                echo '<pre>json';
                print_r($robot_response->json);
                echo '</pre>';

                echo '<pre>json';
                print_r($arr);
                echo '</pre>';
                die();
            }

            

            if(file_exists($json_route)){
                unlink($json_route);
            }
            
            $fp = fopen($json_route, 'w');
            fwrite($fp, json_encode($arr, JSON_PRETTY_PRINT));
            fclose($fp);   

            /*echo '<pre>';
            print_r($arr);
            echo '</pre>';*/
            //die();

            return $arr;

        }
    }


    /****
    Get a specified label that zippedi saw
    *****/
    public function getLabelCrop($store_code = null, $session_code = null, $detection_id = null, $type = null, $return_type = null){

        if($store_code == null){
            $this->Flash->error(__('The store code cannot be null'));
            return $this->redirect(['controller' => 'Dashboard', 'action' => 'index']);
        }

        if($session_code == null){
            $this->Flash->error(__('The session code cannot be null'));
            return $this->redirect(['controller' => 'Dashboard', 'action' => 'index']);
        }

        if($detection_id == null){
            $this->Flash->error(__('The detecion id cannot be null'));
            return $this->redirect(['controller' => 'Dashboard', 'action' => 'index']);
        }

        if($type == null){
            $this->Flash->error(__('The type cannot be null'));
            return $this->redirect(['controller' => 'Dashboard', 'action' => 'index']);
        }

        if($return_type == null){
            $this->Flash->error(__('The type cannot be null'));
            return $this->redirect(['controller' => 'Dashboard', 'action' => 'index']);
        }

        $http = new Client();
        $request_token = $http->post($this->endpoint.'/auth', json_encode($this->auth_data), ['type' => 'json', 'ssl_verify_peer' => false, 'timeout' => 180]);
        $url = $this->endpoint.'/resources/label_crop';

        $http = new Client([
            'headers' => ['Authorization' => 'jwt ' . $request_token->json['access_token']]
        ]);

        $response = $http->get($url, ['store' => $store_code, 'session' => $session_code, 'detection_id' => $detection_id, 'type' => $type], ['ssl_verify_peer' => false, 'timeout' => 180]);

        if($response->getStatusCode() != 200){

            //$this->Flash->error('Error '.$response->code);
            return false;
        }

        /*echo '<pre>';
        print_r($response->body);
        echo '</pre>';*/

        $image = imagecreatefromstring($response->body); 

        ob_start(); //You could also just output the $image via header() and bypass this buffer capture.
        imagejpeg($image, null, 80);
        $data = ob_get_contents();
        ob_end_clean();

        if($return_type == 'image_tag'){
            $response = new \stdClass();
            $response->status = true;
            $response->error = '';
            $response->message = __('Get image');
            $response->data = [
                'image_html_label' => '<img id="img-'.$detection_id.'"style="width: 240px;" src="data:image/jpg;base64,' .  base64_encode($data)  . '" data-zoom-image="data:image/jpg;base64,' .  base64_encode($data)  . '" />'
            ];

            $this->response->type('json');
            $this->response->body(json_encode($response));

            return $this->response;
        }
        else{
            if($return_type == 'save_path'){
                $file_name = $detection_id.'.jpg';
                $put_file_path = ROOT.DIRECTORY_SEPARATOR.'webroot'.DIRECTORY_SEPARATOR.'img'.DIRECTORY_SEPARATOR.'labels'.DIRECTORY_SEPARATOR.$type.DIRECTORY_SEPARATOR.$file_name;

                $png_name = $detection_id.'.png';
                $put_png_file_path = ROOT.DIRECTORY_SEPARATOR.'webroot'.DIRECTORY_SEPARATOR.'img'.DIRECTORY_SEPARATOR.'labels'.DIRECTORY_SEPARATOR.$type.DIRECTORY_SEPARATOR.$png_name;

                $image_file_path = 'labels'.DIRECTORY_SEPARATOR.$type.DIRECTORY_SEPARATOR.$png_name;
                //file_put_contents($put_file_path, $data);

                //return $file_name;


                $imgData1 = 'data:image/jpg;base64,'.base64_encode($data);
                // get rid of everything up to and including the last comma
                $imgData1 = substr($imgData1, 1+strrpos($imgData1, ','));
                // write the decoded file
                $success = file_put_contents($put_file_path, base64_decode($imgData1));

                $image = imagecreatefromjpeg($put_file_path);
                imagepng($image, $put_png_file_path);
                //imagepng(imagecreatefromstring(file_get_contents($success)), $put_png_file_path);

                unlink($put_file_path);



                //$img = $_POST['img'];
                //$img = str_replace('data:image/png;base64,', '', $img);
                //$img = str_replace(' ', '+', $img);
                //$data_decode = base64_decode($data);
                //$file = UPLOAD_DIR . uniqid() . '.png';
                //$success = file_put_contents($put_file_path, $data_decode);
                //print $success ? $put_file_path : 'Unable to save the file.';

                //print $data;
                //print $data_decode;

                //die();
                return $image_file_path;
            }
            else{
                if($return_type == 'base64'){
                    return $data;
                }
                else{
                    return $response->body;
                }
            }
        }
    }

    public function getAssortment($supermarket_code = null, $date = null, $show = false){

        if($supermarket_code == null){
            return false;
        }

        if($date == null){
            return false;
        }

        

        $json_route = ROOT . DIRECTORY_SEPARATOR . 'logs'. DIRECTORY_SEPARATOR .'assortment_'.$supermarket_code.'_'.$date.'.json';

        if(file_exists($json_route)){
            $string = file_get_contents($json_route);

            $string = str_replace('NaN', 'null', $string);

            $arr = json_decode($string, true);

            //$new_arr = json_encode($arr, JSON_PRETTY_PRINT);
            return $arr;
        }

        $auth_data = array(
            'username' => 'zippedi',
            'password' => 'YuL1fcfQQIwFL6Es2Zx'
        );


        $http = new Client();
        $request_token = $http->post($this->endpoint.'/auth', json_encode($auth_data), ['type' => 'json', 'ssl_verify_peer' => false, 'timeout' => 180]);

        $http = new Client([
            'headers' => ['Authorization' => 'jwt ' . $request_token->json['access_token']]
        ]);

        $url = $this->endpoint.'/reports/assortment';
        //$response = $http->get($url, ['store' => $supermarket_code, 'date' => $date], ['ssl_verify_peer' => false]);
        $response = $http->get($url, ['store' => $supermarket_code, 'date' => $date], ['ssl_verify_peer' => false, 'timeout' => 180]);

        /*if($response->getStatusCode() != 200){

            $this->Flash->error('Error '.$response->code);
            return $this->redirect(['controller' => 'RobotReports', 'action' => 'index']);
        }*/

        /*if($supermarket_code == 'HC67'){
            //$response->body = str_replace('NaN', 'null', $response->body);
            $arr = json_decode($response->json, true); 
            return $arr;
        }*/


        if($show == true){
            echo '<pre>';
            print_r($response);
            echo '</pre>';

            /*echo '<pre>';
            print_r($response->body);
            echo '</pre>';*/
            
            $arr = str_replace('NaN', 'null', $response->json);
            $arr = json_decode($response->json, true); 

            echo '<pre>';
            print_r($arr);
            echo '</pre>';

            echo '<pre>';
            print_r($response->json);
            echo '</pre>';
            die();
        }
        else{
            //$response->json = str_replace('NaN', null, $response->json);

            $arr = json_decode($response->json, true); 


            /*if(file_exists($json_route)){
                unlink($json_route);
            }*/
            
            $fp = fopen($json_route, 'w');
            fwrite($fp, json_encode($arr, JSON_PRETTY_PRINT));
            fclose($fp);   


            return $arr;
        }
    }

    /****
    Get a specified facing that zippedi saw
    *****/
    public function getFacingCrop($store_code = null, $session_code = null, $detection_id = null, $return_type = null){

        if($store_code == null){
            $this->Flash->error(__('The store code cannot be null'));
            return false;
        }

        if($session_code == null){
            $this->Flash->error(__('The session code cannot be null'));
            return false;
        }

        if($detection_id == null){
            $this->Flash->error(__('The detecion id cannot be null'));
            return false;
        }

        if($return_type == null){
            $this->Flash->error(__('The return type cannot be null'));
            return false;
        }

        $http = new Client();

        $request_token = $http->post($this->endpoint.'/auth', json_encode($this->auth_data), ['type' => 'json', 'ssl_verify_peer' => false, 'timeout' => 180]);
        $url = $this->endpoint.'/resources/facing_crop';

        $http = new Client([
            'headers' => ['Authorization' => 'jwt ' . $request_token->json['access_token']]
        ]);

        $response = $http->get($url, ['store' => $store_code, 'session' => $session_code, 'detection_id' => $detection_id], ['ssl_verify_peer' => false, 'timeout' => 180]);

        if($response->getStatusCode() != 200){

            //$this->Flash->error('Error '.$response->code);
            return false;
        }

        $image = imagecreatefromstring($response->body); 

        ob_start(); //You could also just output the $image via header() and bypass this buffer capture.
        imagejpeg($image, null, 80);
        $data = ob_get_contents();
        ob_end_clean();

        if($return_type == 'image_tag'){
            $response = new \stdClass();
            $response->status = true;
            $response->error = '';
            $response->message = __('Get image');
            $response->data = [
                'image_html_label' => '<img id="img-'.$detection_id.'" src="data:image/jpg;base64,' .  base64_encode($data)  . '" data-zoom-image="data:image/jpg;base64,' .  base64_encode($data)  . '" />'
            ];

            $this->response->type('json');
            $this->response->body(json_encode($response));

            return $this->response;
        }
        else{
            if($return_type == 'save'){
                $file_name = $detection_id.'.jpg';
                $put_file_path = ROOT.DIRECTORY_SEPARATOR.'webroot'.DIRECTORY_SEPARATOR.'img'.DIRECTORY_SEPARATOR.'labels'.DIRECTORY_SEPARATOR.'facing'.DIRECTORY_SEPARATOR.$file_name;
                file_put_contents($put_file_path, $data);

                return $file_name;
            }
            else{
                if($return_type == 'base64'){
                    return $data;
                }
                else{
                    return $response->body;    
                }
            }
        }
    }

    public function test(){
        phpinfo();
        
        $this->autoRender = false;
    }
}
