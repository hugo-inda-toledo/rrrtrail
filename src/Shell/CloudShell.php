<?php

namespace App\Shell;

use Cake\Console\Shell;
use Cake\Event\Event;
use Cake\Network\Http\Client;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;
use Cake\Datasource\ConnectionManager;
use Google\Cloud\BigQuery\BigQueryClient;
use Google\Cloud\Core\ExponentialBackoff;
use Google\Cloud\Storage\StorageClient;
use Cake\Filesystem\Folder;
use Cake\Filesystem\File;

class CloudShell extends Shell
{

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Users');
    }


    public function startMachines(){

        $instances = [
            'zippedi-cronjobs' => 'zippedi-cronjobs',
            'zippedi-cronjobs-2' => 'zippedi-cronjobs-2',
            'zippedi-cronjobs-3' => 'zippedi-cronjobs-3'
        ];

        $client = new \Google_Client();
        $client->setApplicationName('Google-ComputeSample/0.1');
        $client->useApplicationDefaultCredentials();
        $client->addScope('https://www.googleapis.com/auth/cloud-platform');

        //$client = $this->getClient();

        $service = new \Google_Service_Compute($client);

        $optParams = [];

        $response = $service->instances->listInstances('cencosud-chile', 'us-east1-c', $optParams);

        foreach ($response['items'] as $instance) {

            if(isset($instances[$instance->name]) && $instance->status == 'TERMINATED'){

                $response = $service->instances->start('cencosud-chile', 'us-east1-c', $instance->name);
                $this->out(__('Starting Up Instance: {0}', [$instance->name]));
            }   
        }
    }

    public function stopMachines(){

        $instances = [
            'zippedi-cronjobs' => 'zippedi-cronjobs',
            'zippedi-cronjobs-2' => 'zippedi-cronjobs-2',
            'zippedi-cronjobs-3' => 'zippedi-cronjobs-3'
        ];

        $client = new \Google_Client();
        $client->setApplicationName('Google-ComputeSample/0.1');
        $client->useApplicationDefaultCredentials();
        $client->addScope('https://www.googleapis.com/auth/cloud-platform');


        //DELETE PDFS
        $pdf_dir = new Folder(WWW_ROOT . 'files/pdfs');
        $pdf_files = $pdf_dir->find('.*\.pdf', true);

        $this->out(__('Deleting pdf files'));
            
        foreach($pdf_files as $file){
            unlink(WWW_ROOT . 'files/pdfs/'.$file);
            $this->out(__('File: {0} [DELETED]', $file));
        }

        //DELETE EXCELS
        $excels_dir = new Folder(WWW_ROOT . 'files/excels');
        $excels_files = $excels_dir->find('.*\.xlsx', true);

        $this->out(__('Deleting excels files'));
            
        foreach($excels_files as $file){
            unlink(WWW_ROOT . 'files/excels/'.$file);
            $this->out(__('File: {0} [DELETED]', $file));
        }

        //DELETE LABELS
        $inv_dir = new Folder(WWW_ROOT . 'files/labels');
        $inv_files = $inv_dir->find('.*\.inv', true);

        $this->out(__('Deleting .inv files'));
            
        foreach($inv_files as $file){
            unlink(WWW_ROOT . 'files/labels/'.$file);
            $this->out(__('File: {0} [DELETED]', $file));
        }

        //$client = $this->getClient();

        $service = new \Google_Service_Compute($client);

        $optParams = [];

        $response = $service->instances->listInstances('cencosud-chile', 'us-east1-c', $optParams);

        foreach ($response['items'] as $instance) {

            if(isset($instances[$instance->name]) && $instance->status == 'RUNNING'){

                $response = $service->instances->stop('cencosud-chile', 'us-east1-c', $instance->name);
                $this->out(__('Shutting Down Instance: {0}', [$instance->name]));
            }   
        }
    }

    function getClient()
    {
        $json_file = ROOT . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'client_secret.json';

        $client = new \Google_Client();
        $client->setApplicationName('Compute Engine API PHP Quickstart');
        //$client->setScopes(\Google_Service_Gmail::GMAIL_READONLY);
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
            echo $credentialsPath;
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

        return ROOT . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . $path;
        //return str_replace('~', realpath($homeDirectory), $path);
    }

    public function importDatabase($table_name = null, $start = null, $end = null)
    {
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

        if($table_name != null ){
            $tables = [
                0 => $table_name
            ];
        }
        else{
            $tables = [
                0 => 'products',
                1 => 'products_stores',
                2 => 'companies',
                3 => 'stores',
                4 => 'sections',
                5 => 'categories',
                6 => 'sub_categories',
                7 => 'third_categories',
                8 => 'locations',
                9 => 'countries',
                10 => 'regions',
                11 => 'communes'
            ];
        }
        

        foreach($tables as $key => $table) {

            if($table == 'logs'){
                continue;
            }
            
            $dataset = $bigQuery->dataset($dataSet);
            $table_cloud = $dataset->table($table);

            if($table_cloud->exists() == false){
                //echo 'la tabla '.$table.' no existe<br>';
                $this->out('la tabla '.$table.' no existe');

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
                //echo 'la tabla '.$table.' ha sido creada<br>';

                $this->out('la tabla '.$table.' ha sido creada');
            }
            

            //Inserta todas las filas de todas las tablas

            $entityTable = TableRegistry::get($table);

            if($start != null && $end != null){
                $this->out(__('{0} table has limit between {1} and {2}', [$table, $start, $end]));
                $records = $entityTable->find('all')->where(['id >=' => $start, 'id <=' => $end])->toArray();
            }
            else{  
                $records = $entityTable->find('all')->toArray();
            }
            

            if(count($records) > 0){

                $this->out(__('La tabla {0} tiene {1} registros<br>', [$table, count($records)]));

                $fp = fopen($table.'_results.json', 'w');

                $data = [];

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

				    fwrite($fp, json_encode($data)."\r\n");
				}

				fclose($fp);

                $projectId = 'cencosud-chile';
                $datasetId = 'public';

                $this->import_from_file($projectId, $datasetId, $table, $table.'_results.json');

                unlink($table.'_results.json');
            }
            else{
                $this->out(__('La tabla {0} no tiene registros<br>', [$table]));
            }
        }

        $this->out(__('End task'));
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
                    $this->out(__('%s: %s' . PHP_EOL, [$error['reason'], $error['message']]));
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

    /*
    * @param string $projectId The Google project ID.
    * @param string $datasetId The BigQuery dataset ID.
    * @param string $tableId   The BigQuery table ID.
    * @param string $source    The path to the source file to import.
    */
    function import_from_file($projectId, $datasetId, $tableId, $source)
    {
        $keyFile = ROOT . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'Cencosud-Chile-532be363cdb8.json';
        // instantiate the bigquery table service
        $bigQuery = new BigQueryClient([
            'projectId' => $projectId,
            'keyFile' => json_decode(file_get_contents($keyFile), true)
        ]);

        $dataset = $bigQuery->dataset($datasetId);
        $table = $dataset->table($tableId);
        // create the import job
        $loadConfig = $table->load(fopen($source, 'r'));
        // determine the source format from the object name
        $pathInfo = pathinfo($source) + ['extension' => null];
        if ('csv' === $pathInfo['extension']) {
            $loadConfig->sourceFormat('CSV');
        } elseif ('json' === $pathInfo['extension']) {
            $loadConfig->sourceFormat('NEWLINE_DELIMITED_JSON');
        } else {
            throw new InvalidArgumentException('Source format unknown. Must be JSON or CSV');
        }
        $job = $table->runJob($loadConfig);
        // poll the job until it is complete
        $backoff = new ExponentialBackoff(10);
        $backoff->execute(function () use ($job) {
            printf('Waiting for job to complete' . PHP_EOL);
            $job->reload();
            if (!$job->isComplete()) {
                throw new Exception('Job has not yet completed', 500);
            }
        });
        // check if the job has errors
        if (isset($job->info()['status']['errorResult'])) {
            $error = $job->info()['status']['errorResult']['message'];
            printf('Error running job: %s' . PHP_EOL, $error);
        } else {
            print('Data imported successfully' . PHP_EOL);
        }
    }
}