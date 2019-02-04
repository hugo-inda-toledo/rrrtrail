<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Network\Http\Client;
use Cake\Core\Configure;
use Cake\I18n\Time;

class SuppliersApiController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Suppliers');
    }

    var $auth_data = array(
        'username' => 'zippedi',
        'password' => 'YuL1fcfQQIwFL6Es2Zx'
    );

    var $endpoint = 'https://reports.zippedi.cl';
    var $endpoint_fake = 'http://192.168.0.116:5020';

    function getSuppliersList($id = null){

        $http = new Client();
        $request_token = $http->post($this->endpoint.'/auth', json_encode($this->auth_data), ['type' => 'json']);

        echo '<pre>';
        print_r($request_token->json);
        echo '</pre>';

        $url = $this->endpoint_fake.'/suppliers/suppliers';

        $http = new Client([
            'headers' => ['Authorization' => 'jwt ' . $request_token->json['access_token']]
        ]);

        $response = $http->get($url, ['id' => $id], ['ssl_verify_peer' => false]);

        if($response->getStatusCode() != 200){

            die('error');
            $this->Flash->error('Error '.$response->code);
            return $this->redirect(['controller' => 'RobotReports', 'action' => 'index']);
        }

        echo '<pre>';
        print_r($response->json);
        echo '</pre>';
        die();
        return $response->json;

    }
}
