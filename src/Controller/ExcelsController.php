<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Network\Http\Client;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;
use Cake\Datasource\ConnectionManager;
use Google\Cloud\BigQuery\BigQueryClient;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExcelsController extends AppController
{
  	public function initialize(){
        
        parent::initialize();
        $this->loadModel('ProductsStores');
    }

  	function assortmentReport(){

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
            $report_date = new Time($this->request->data('end_date'));
            $not_readed_products = [];

            $dates['global']['start_date']['report_date'] = new Time($this->request->data('end_date'));
            $dates['global']['start_date']['master_date'] = new Time($this->request->data('end_date'));
            $dates['global']['start_date']['master_date']->modify('-1 days');

            for($x=0; $x < 7; $x++){
                                
                $master_date->modify('-1 days');

                if($x != 0){
                   $report_date->modify('-1 days'); 
                }
            }

            $dates['global']['end_date']['report_date'] = $report_date;
            $dates['global']['end_date']['master_date'] = $master_date;

            $section_cond_arr = [];

            if($this->request->data('section_id') != 'all'){
                $section_cond_arr['ProductsStores.section_id'] = $this->request->data('section_id');
            }

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
                ->contain('Sections', function ($q) {
                    return $q
                        ->select(['Sections.id', 'Sections.section_code']);
                })
                ->select([
                    'ProductsStores.company_internal_code',
                    'ProductsStores.cataloged',
                    'ProductsStores.enabled',
                    'ProductsStores.category_id',
                    'ProductsStores.section_id',
                    'ProductsStores.stock_up_to_date',
                ])
                ->where([
                    'ProductsStores.company_id' => $this->request->data('company_id'),
                    'ProductsStores.store_id' => $this->request->data('store_id'),
                    $section_cond_arr,
                    'DATE(ProductsStores.master_catalog_date) >=' => $dates['global']['end_date']['master_date']->format('Y-m-d'),
                    'DATE(ProductsStores.master_catalog_date) <=' => $dates['global']['start_date']['master_date']->format('Y-m-d'),
                ])
                ->order([
                    /*'ProductsStores.section_id' => 'ASC',
                    'ProductsStores.category_id' => 'ASC',
                    'ProductsStores.sub_category_id' => 'ASC',*/
                    'ProductsStores.master_catalog_date' => 'DESC',
                    'ProductsStores.stock_up_to_date' => 'DESC',
                ])
                //->group('ProductsStores.product_id')
                ->group('ProductsStores.company_internal_code')
                ->toArray();

            if(count($products_stores) > 0){

                $not_readed_products[] = [
                	'EAN', __('Description'), __('Int. Code'), __('Section'), __('Stock')
                ];

                foreach($products_stores as $product_store){

                    /**** Global Data ****/
                    if($product_store->analyzed_product == null){
                        $not_readed_products[] = [
                        	$product_store->product->ean13.$product_store->product->ean13_digit,
                        	$product_store->product->product_description,
                        	$product_store->company_internal_code,
                        	$product_store->section->section_code,
                            $product_store->stock_up_to_date
                        ];
                    }
                    /**** End Global Data ****/
                }

                //Liberar memoria
                unset($products_stores);

                $spreadsheet = new Spreadsheet();
				//$sheet = $spreadsheet->getActiveSheet();
				//$sheet->setCellValue('A1', 'Hello World !');
                $spreadsheet->getActiveSheet()->fromArray($not_readed_products, NULL);
				$writer = new Xlsx($spreadsheet);

				// We'll be outputting an excel file
				header('Content-type: application/vnd.ms-excel');

				// It will be called file.xls
				header('Content-Disposition: attachment; filename="assortment_report_'.$this->request->data('end_date').'_'.$this->request->data('company_id').$this->request->data('store_id').$this->request->data('section_id').'.xlsx"');

				// Write file to the browser
				$writer->save('php://output');
            }
        }
  	}

  	
}