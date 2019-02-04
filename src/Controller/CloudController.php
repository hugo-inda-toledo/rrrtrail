<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Network\Http\Client;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;
use Cake\Datasource\ConnectionManager;
//use Google\Client;


class CloudController extends AppController
{
  	public function beforeFilter(Event $event)
  	{
     	parent::beforeFilter($event);
      	$this->loadModel('Products');
  	}

  	function index (){

  		//$product = $this->Products->find('all', ['conditions' => ['Products.id' => 1]])->first();

  		$array = json_decode(json_encode($this->Products->find('all', ['conditions' => ['Products.id' => 1]])->first()), true);

  		echo '<pre>';
  		print_r($array);
  		echo '</pre>';
  	}

  	function dumpingDatabaseModel(){

  		
  		//Start BigQuery API
  		$keyFile = ROOT . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'Cencosud-Chile-532be363cdb8.json';
  		$projectId = 'cencosud-chile';
  		$dataSet = 'public';

	    $bigQuery = new BigQueryClient([
		    'projectId' => $projectId,
		    'keyFile' => json_decode(file_get_contents($keyFile), true)
		]);

	    // Get all tables
	    $db = ConnectionManager::get('default');
		$tables = $db->schemaCollection()->listTables();

		foreach($tables as $key => $table) {

			if($table == 'logs'){
				continue;
			}
			
			$dataset = $bigQuery->dataset($dataSet);
        	$table_cloud = $dataset->table($table);

        	if($table_cloud->exists() == false){
        		//echo 'la tabla '.$table.' no existe<br>';


        		$fields = array();
				$x = 0;
				$tableData = TableRegistry::get($table);

			    // Get a single table (instance of Schema\TableSchema)
		        $tableSchema = $db->schemaCollection()->describe($table);
		        
		        //Get columns list from table
		        $columns = $tableSchema->columns();
		        
		        //Empty array for fields
		        $table_fields = [];
		        
		        //iterate columns
		        foreach ( $columns as $column ){
		            $table_fields[ $column ] = $tableSchema->column( $column );
		        }

		        //debug($tableData);
        		//debug($table_fields);

        		$x=0;
        		foreach($table_fields as $column_name => $data){
        			$fields[$x] = [
				  		'name' => $column_name,
				  		'type' => ($data['type'] == 'text') ? 'string' : $data['type'],
				  		'mode' => ($data['null'] == false) ? 'required' : ''
					];

					if($data['null'] == true){
				  		unset($fields[$x]['mode']);
					}

					$x++;
        		}

        		$schema = ['fields' => $fields];

          		$table_created = $this->create_table($projectId, $dataSet, $table, $schema);
          		echo 'la tabla '.$table.' ha sido creada<br>';
        	}
        	

        	//Inserta todas las filas de todas las tablas

        	$entityTable = TableRegistry::get($table);
        	$records = $entityTable->find('all')->toArray();

        	if(count($records) > 0){
        		echo __('La tabla {0} tiene {1} registros<br>', [$table, count($records)]);

        		$data = array();
        		foreach($records as $record){


        			$tableSchema = $db->schemaCollection()->describe($table);
		        
			        //Get columns list from table
			        $columns = $tableSchema->columns();
			        
			        //Empty array for fields
			        $table_fields = [];
			        
			        //iterate columns
			        foreach ( $columns as $column ){
			            $column_data = $tableSchema->column( $column );

			            if($column_data['type'] == 'datetime'){

			            	if($record->$column != null){
			            		$dato = $record->$column->format('Y-m-d H:i:s');
			            	}
			            	else{
			            		$dato = null;
			            	}
			            }
			            else{
			            	$dato = $record->$column;
			            }

			            $data[$column] = $dato;
			        }

			        if($this->stream_row($projectId, $dataSet, $table, $data, $record->id) == true){
		            	echo 'OK ROW<br>';
		            }
		            else{
		            	echo 'ERROR ROW<br>';
		            }
        		}
        	}
        	else{
        		echo __('La tabla {0} no tiene registros<br>', [$table]);
        	}
		}
  	}

  	/**
	* Stream a row of data into your BigQuery table
	* Example:
	* ```
	* $data = [
	*     "field1" => "value1",
	*     "field2" => "value2",
	* ];
	* stream_row($projectId, $datasetId, $tableId, $data);
	* ```.
	*
	* @param string $projectId The Google project ID.
	* @param string $datasetId The BigQuery dataset ID.
	* @param string $tableId   The BigQuery table ID.
	* @param string $data      An associative array representing a row of data.
	* @param string $insertId  An optional unique ID to guarantee data consistency.
	*/
	function stream_row($projectId, $datasetId, $tableId, $data, $insertId = null)
	{
		$keyFile = ROOT . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'Cencosud-Chile-532be363cdb8.json';

		// instantiate the bigquery table service
		$bigQuery = new BigQueryClient([
		    'projectId' => $projectId,
		    'keyFile' => json_decode(file_get_contents($keyFile), true)
		]);

		$dataset = $bigQuery->dataset($datasetId);
		$table = $dataset->table($tableId);

		$insertResponse = $table->insertRows([
			['insertId' => $insertId, 'data' => $data],
		    // additional rows can go here
		]);
		if ($insertResponse->isSuccessful()) {
		    //print('Data streamed into BigQuery successfully' . PHP_EOL);
		    return true;
		} 
		else{
		    foreach ($insertResponse->failedRows() as $row){
		        foreach ($row['errors'] as $error) {
		            printf('%s: %s' . PHP_EOL, $error['reason'], $error['message']);
		        }
		    }
		    return false;
		}
	}

	function create_table($projectId, $datasetId, $tableId, $schema)
	{
		$keyFile = ROOT . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'Cencosud-Chile-532be363cdb8.json';

		// instantiate the bigquery table service
		$bigQuery = new BigQueryClient([
			'projectId' => $projectId,
			'keyFile' => json_decode(file_get_contents($keyFile), true)
		]);

		$dataset = $bigQuery->dataset($datasetId);
		$options = ['schema' => $schema];
		$table = $dataset->createTable($tableId, $options);
		return $table;
	}

	function testGmail(){
		// Get the API client and construct the service object.
		$client = $this->getClient();
		$service = new \Google_Service_Gmail($client);

		// Print the labels in the user's account.
		$user = 'me';
		$results = $service->users_labels->listUsersLabels($user);

		if (count($results->getLabels()) == 0) {
		  print "No labels found.\n";
		} else {
		  print "Labels:\n";
		  foreach ($results->getLabels() as $label) {
		    printf("- %s\n", $label->getName());
		  }
		}
	}

	function getClient()
	{
		$json_file = ROOT . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'client_secret.json';

	    $client = new \Google_Client();
	    $client->setApplicationName('Gmail API PHP Quickstart');
	    $client->setScopes(\Google_Service_Gmail::GMAIL_READONLY);
	    $client->setAuthConfig($json_file);
	    $client->setAccessType('offline');

	    // Load previously authorized credentials from a file.
	    $credentialsPath = $this->expandHomeDirectory('credentials.json');
	    if (file_exists($credentialsPath)) {
	        $accessToken = json_decode(file_get_contents($credentialsPath), true);
	    } else {
	        // Request authorization from the user.
	        $authUrl = $client->createAuthUrl();
	        printf("Open the following link in your browser:\n%s\n", $authUrl);
	        print 'Enter verification code: ';
	        $authCode = trim(fgets(STDIN));

	        // Exchange authorization code for an access token.
	        $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);

	        // Store the credentials to disk.
	        if (!file_exists(dirname($credentialsPath))) {
	            mkdir(dirname($credentialsPath), 0700, true);
	        }
	        file_put_contents($credentialsPath, json_encode($accessToken));
	        printf("Credentials saved to %s\n", $credentialsPath);
	    }
	    $client->setAccessToken($accessToken);

	    // Refresh the token if it's expired.
	    if ($client->isAccessTokenExpired()) {
	        $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
	        file_put_contents($credentialsPath, json_encode($client->getAccessToken()));
	    }
	    return $client;
	}

	function expandHomeDirectory($path) {
	 	$homeDirectory = getenv('HOME');
	  	if (empty($homeDirectory)) {
	   		$homeDirectory = getenv('HOMEDRIVE') . getenv('HOMEPATH');
	  	}
	  	return str_replace('~', realpath($homeDirectory), $path);
	}
}