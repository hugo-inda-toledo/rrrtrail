<?php
namespace App\View\Cell;

use Cake\View\Cell;
use Cake\Network\Response;
use stdClass;
use Cake\Network\Http\Client;

class RobotApiCell extends Cell
{

    public function renderData($url = null, $report_keyword = null)
    {
        $auth_data = array(
            'username' => 'zippedi',
            'password' => 'YuL1fcfQQIwFL6Es2Zx'
        );

        $http = new Client();
        $response = $http->post('https://reports.zippedi.cl/auth', json_encode($auth_data), ['type' => 'json']);

        $options = array('http' => array(
            'method'  => 'GET',
            'header' => array('Content-Type: application/json', 'Authorization: jwt '.$response->json['access_token'])
        ));

        //$url = 'https://reports.zippedi.cl/jumbo/active_products?supermarket=J512&date=03-04-2018&department=7';
        //$url = 'https://reports.zippedi.com/jumbo/active_products?supermarket=J512&date=09-04-2018&department=7';
        $url = 'https://reports.zippedi.cl/jumbo/active_products?supermarket=J513&date=09-04-2018&department=7';

        $context  = stream_context_create($options);
        $webservice= json_decode(file_get_contents($url, false, $context));
        

        echo '<pre>';
        print_r($webservice); 
        echo '</pre>';
        //die());

        /*switch ($report_keyword ) {
            case 'price_difference':
                $this->displayPriceDifference($data_array);
                break;
            
            case 'active_products':
                # code...
                break;
            
            default:
                # code...
                break;
        }*/

        $this->set('json_array' , $webservice);
    }

    public function displayPriceDifference($data_array = null){
        echo '<pre>'; 
        print_r($data_array);
        echo '</pre>';

        $this->set('data_array', $data_array);
    }


    public function robotGetAccessToken($method, $url, $data = false)
    {
        $curl = curl_init();

        switch ($method)
        {
            case "POST":
                curl_setopt($curl, CURLOPT_POST, true);

                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_PUT, 1);
                break;
            default:
                if ($data)
                    $url = sprintf("%s?%s", $url, http_build_query($data));
        }

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);                                                                  
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(                                                                          
            'Content-Type: application/json',                                                                                
            'Content-Length: ' . count($data))                                                                       
        );                                          


        $result = curl_exec($curl);

        curl_close($curl);

        return $result;
    }

    public function callRobotAPI($method, $url, $data = false, $authorization = null)
    {
        $curl = curl_init();

        switch ($method)
        {
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);

                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_PUT, 1);
                break;
            default:
                if ($data)
                    $url = sprintf("%s?%s", $url, http_build_query($data));
      }

      // Cabezera con Access Token:
      curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json' , 
            sprintf('Authorization: jwt %s', $authorization)
      ));

      curl_setopt($curl, CURLOPT_URL, $url);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

      //curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

      $result = curl_exec($curl);

      curl_close($curl);

      return $result;
    }

}