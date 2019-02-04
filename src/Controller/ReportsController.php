<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Cache\Cache;
use Cake\I18n\Time;
use Cake\Network\Http\Client;
use App\Controller\RobotReportsController as RobotReportsMain;
use Picqer\Barcode\BarcodeGeneratorPNG;
use App\View\Helper\EanHelper;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Cake\View\Helper\UrlHelper;
use Cake\View\Helper\NumberHelper;
use Cake\Console\Shell;
use App\View\Helper\SlackHelper;

/**
 * Regions Controller
 *
 * @property \App\Model\Table\RegionsTable $Regions
 *
 * @method \App\Model\Entity\Region[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ReportsController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow(['activeAssortmentReport', 'negativeAssortmentReport', 'blockedAssortmentReport']);
    }

    public function activeAssortmentReport($method = 'download', $type = 'list', $robot_session_id = null, $section_id = 'all', $category_id = 'all'){

        $this->loadModel('CatalogUpdates');

        $robot_session = $this->CatalogUpdates->ProductsStores->Detections->RobotSessions->find('all')
            ->contain([
                'Stores' => [
                    'Companies'
                ]
            ])
            ->where([
                'RobotSessions.id' => $robot_session_id
            ])
            ->first();

        if($robot_session == null){
            die(__('No exist session'));
        }




        $store_id = $robot_session->store->id;
        $session_date_query = $robot_session->calendar_date->format('Y-m-d');


        $sections_cond_arr = [];
        $categories_cond_arr = [];
        $products_cond_arr = [];

        //$section_id = null;
        $section = null;
        if($section_id != 'all'){
            $products_cond_arr['ProductsStores.section_id'] = $section_id;
            $sections_cond_arr['Sections.id'] = $section_id;

            //$section_id = $this->request->data('section_id');
            $section = $this->CatalogUpdates->ProductsStores->Sections->get($section_id);
        }

        //$category_id = null;
        $category = null;
        if($category_id != 'all'){
            $products_cond_arr['ProductsStores.category_id'] = $category_id;
            $categories_cond_arr['Categories.id'] = $category_id;

            //$category_id = $this->request->data('category_id');
            $category = $this->CatalogUpdates->ProductsStores->Sections->Categories->get($category_id);
        }

        if($robot_session->store->company->company_keyword == 'jumbo'){
            $catalogs = $this->CatalogUpdates->find('all')
                ->contain([
                    'ProductsStores' => [
                        'queryBuilder' => function (\Cake\ORM\Query $query) use($products_cond_arr, $store_id, $session_date_query) {
                            return $query
                                ->select(['ProductsStores.id', 'ProductsStores.company_id', 'ProductsStores.section_id', 'ProductsStores.category_id', 'ProductsStores.description', 'ProductsStores.ean13', 'ProductsStores.internal_code'])
                                ->where([$products_cond_arr])
                                /*->matching('StockUpdates', function ($q) use($store_id, $session_date_query){
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
                                            'DATE(StockUpdates.stock_updated) <=' => $session_date_query,
                                            'StockUpdates.current_stock >' => 0
                                        ])                             
                                        ->order(['StockUpdates.stock_updated' => 'DESC']);
                                        //->limit(1);
                                })*/
                                ->matching('Sections', function ($q){
                                    return $q
                                        ->where([
                                            'Sections.enabled' => 1
                                        ]);
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
                    'DATE(CatalogUpdates.catalog_date)' => $session_date_query,
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
            $catalogs = $this->CatalogUpdates->find('all')
                ->contain([
                    'ProductsStores' => [
                        'queryBuilder' => function (\Cake\ORM\Query $query) use($products_cond_arr, $store_id, $session_date_query) {
                            return $query
                                ->select(['ProductsStores.id', 'ProductsStores.company_id', 'ProductsStores.section_id', 'ProductsStores.category_id', 'ProductsStores.description', 'ProductsStores.ean13', 'ProductsStores.internal_code'])
                                ->where([$products_cond_arr])
                                ->matching('Sections', function ($q){
                                    return $q
                                        ->where([
                                            'Sections.enabled' => 1
                                        ]);
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
                    'DATE(CatalogUpdates.catalog_date)' => $session_date_query,
                    'CatalogUpdates.store_id' => $store_id,
                    'CatalogUpdates.seen' => 0,
                    'CatalogUpdates.enabled' => 1,
                    'CatalogUpdates.cataloged' => 1,
                ])
                ->group('CatalogUpdates.product_store_id')
                ->order([
                    'CatalogUpdates.catalog_date' => 'DESC'
                ])
                //->limit(1000)
                ->toArray();
                //->cache('assortment_query_'.$dates['global']['end_date']['master_date']->format('Y-m-d').'_'.$store->store_code.'_'.$this->request->data('section_id').($this->request->data('category_id') != '') ? '_'.$this->request->data('category_id') : '', 'config_cache_query');
        }


        if(count($catalogs) > 0){

            //echo 'Hay '.count($catalogs);

            /*echo '<pre>';
            print_r($catalogs);
            echo '</pre>';

            die();*/
            
            $inv_path = ROOT . DIRECTORY_SEPARATOR . 'webroot'. DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . 'labels' .  DIRECTORY_SEPARATOR . $robot_session->store->store_code.'-'.$robot_session->calendar_date->format('Ymd').(($section_id != 'all') ? '-'.$section_id : '-all').(($category_id != '') ? '-'.$category_id : '').'-assortment.inv';

            $eanHelper = new EanHelper(new \Cake\View\View());
            $fp = fopen($inv_path, 'w');
            $x = 0;

            $dates = [];
            $not_readed_products = [];

            foreach($catalogs as $catalog){

                if($robot_session->store->company->company_keyword == 'jumbo'){
                    if($catalog->stock <= 0){
                        continue;
                    }
                }
                

                if($x == 0){
                    $not_readed_products[$x] = [
                        'EAN', __('Description'), __('Int. Code'), __('Section'), __('Category'), __('Stock')
                    ];
                    $x++;
                }


                $code_length = strlen($x);
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

                //To excel generate
                $not_readed_products[$catalog->products_store->internal_code] = [
                    $catalog->products_store->ean13,
                    $catalog->products_store->description,
                    $catalog->products_store->internal_code,
                    ($catalog->products_store->section != null) ? $catalog->products_store->section->section_code.' '.$catalog->products_store->section->section_name : __('Unknown section'),
                    ($catalog->products_store->category != null) ? $catalog->products_store->category->category_code.' '.$catalog->products_store->category->category_name : __('Unknown category'),
                    ($catalog->stock != null) ? $catalog->stock : null
                ]; 

                //To report list
                $dates['global']['products'][($catalog->products_store->section != null) ? $catalog->products_store->section->section_name : __('Unknown section')][($catalog->products_store->category != null) ? $catalog->products_store->category->category_name : __('Unknown category')]['data'][$catalog->products_store->internal_code]['id'] = $catalog->products_store->id;



                
                $dates['global']['products'][($catalog->products_store->section != null) ? $catalog->products_store->section->section_name : __('Unknown section')][($catalog->products_store->category != null) ? $catalog->products_store->category->category_name : __('Unknown category')]['data'][$catalog->products_store->internal_code]['ean13'] = $catalog->products_store->ean13;
                $dates['global']['products'][($catalog->products_store->section != null) ? $catalog->products_store->section->section_name : __('Unknown section')][($catalog->products_store->category != null) ? $catalog->products_store->category->category_name : __('Unknown category')]['data'][$catalog->products_store->internal_code]['description'] = $catalog->products_store->description;
                $dates['global']['products'][($catalog->products_store->section != null) ? $catalog->products_store->section->section_name : __('Unknown section')][($catalog->products_store->category != null) ? $catalog->products_store->category->category_name : __('Unknown category')]['data'][$catalog->products_store->internal_code]['internal_code'] = $catalog->products_store->internal_code;

                $dates['global']['products'][($catalog->products_store->section != null) ? $catalog->products_store->section->section_name : __('Unknown section')][($catalog->products_store->category != null) ? $catalog->products_store->category->category_name : __('Unknown category')]['data'][$catalog->products_store->internal_code]['stock'] = ($catalog->stock != null) ? $catalog->stock : null;
                

                
                $x++;
            }

            switch ($method) {
                case 'cron':

                    $data_arr = [];
                    /** Start PDF Generate **/

                    $pdf_path = ROOT . DIRECTORY_SEPARATOR . 'webroot'. DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . 'pdfs' .  DIRECTORY_SEPARATOR . 'assortment-'.$robot_session->store->company->company_keyword.'-'.$robot_session->store->store_code.(($section_id != 'all') ? '-'.$section_id : '-all').(($category_id != '') ? '-'.$category_id : '-').$robot_session->calendar_date->format('d-m-Y').'.pdf';

                    if(file_exists($pdf_path)){
                        unlink($pdf_path);
                    }

                    if(!file_exists($pdf_path) && count($not_readed_products) > 0){

                        $product_states = [];
                        $barcode = new BarcodeGeneratorPNG();

                        $CakePdf = new \CakePdf\Pdf\CakePdf();
                        $CakePdf->template('active_assortment', 'default');
                        $CakePdf->viewVars(['barcode' => $barcode, 'section' => $section, 'category' => $category, 'dates' => $dates, 'product_states' => $product_states, 'robot_session' => $robot_session, 'type' => 'list']);
                        // Get the PDF string returned
                        //$pdf = $CakePdf->output();
                        // Or write it to file directly
                        $pdf = $CakePdf->write($pdf_path); 

                        $data_arr['pdf'] = [
                            'file_path' => $pdf_path,
                            'file_name' => 'assortment-'.$robot_session->store->company->company_keyword.'-'.$robot_session->store->store_code.(($section_id != 'all') ? '-'.$section_id : '-all').(($category_id != '') ? '-'.$category_id : '-').$robot_session->calendar_date->format('d-m-Y').'.pdf'
                        ];
                    }

                    /** End PDF Generate **/


                    /* Excel generate */
                    $excel_path = ROOT . DIRECTORY_SEPARATOR . 'webroot'. DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . 'excels' .  DIRECTORY_SEPARATOR . 'assortment-'.$robot_session->store->company->company_keyword.'-'.$robot_session->store->id.'-'.(($section_id != 'all') ? '-'.$section_id : '-all').(($category_id != '') ? '-'.$category_id : '').$robot_session->calendar_date->format('dmY').'.xlsx';

                    if(file_exists($excel_path)){
                        unlink($excel_path);
                    }

                    if(!file_exists($excel_path)){
                        
                        $spreadsheet = new Spreadsheet();
                        //$sheet = $spreadsheet->getActiveSheet();
                        //$sheet->setCellValue('A1', 'Hello World !');
                        $spreadsheet->getActiveSheet()->fromArray($not_readed_products, NULL);
                        $writer = new Xlsx($spreadsheet);

                        // We'll be outputting an excel file
                        //header('Content-type: application/vnd.ms-excel');

                        // It will be called file.xls
                        //header('Content-Disposition: attachment; filename="assortment-'.$robot_session->store->company->company_keyword.'-'.$robot_session->store->id.'-'.(($section_id != 'all') ? '-'.$section_id : '-all').(($category_id != '') ? '-'.$category_id : '').$robot_session->calendar_date->format('dmY').'.xlsx"');

                        // Write file to the browser
                        $writer->save($excel_path);

                        $data_arr['xlsx'] = [
                            'file_path' => $excel_path,
                            'file_name' => 'assortment-'.$robot_session->store->company->company_keyword.'-'.$robot_session->store->id.'-'.(($section_id != 'all') ? '-'.$section_id : '-all').(($category_id != '') ? '-'.$category_id : '').$robot_session->calendar_date->format('dmY').'.xlsx'
                        ];

                    }
                    /* End Excel generate */

                    return $data_arr;
                    break;

                case 'download':

                    switch ($type) {
                        case 'xlsx':
                            /* Excel generate */
                            $excel_path = ROOT . DIRECTORY_SEPARATOR . 'webroot'. DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . 'excels' .  DIRECTORY_SEPARATOR . 'assortment-'.$robot_session->store->company->company_keyword.'-'.$robot_session->store->id.'-'.(($section_id != 'all') ? '-'.$section_id : '-all').(($category_id != '') ? '-'.$category_id : '').$robot_session->calendar_date->format('dmY').'.xlsx';

                            if(file_exists($excel_path)){
                                unlink($excel_path);
                            }

                            if(!file_exists($excel_path)){
                                
                                $spreadsheet = new Spreadsheet();
                                //$sheet = $spreadsheet->getActiveSheet();
                                //$sheet->setCellValue('A1', 'Hello World !');
                                $spreadsheet->getActiveSheet()->fromArray($not_readed_products, NULL);
                                $writer = new Xlsx($spreadsheet);

                                // Write file to the browser
                                $writer->save($excel_path);

                                $urlHelper = new UrlHelper(new \Cake\View\View());

                                $url = $urlHelper->build('/files/excels/'.'assortment-'.$robot_session->store->company->company_keyword.'-'.$robot_session->store->id.'-'.(($section_id != 'all') ? '-'.$section_id : '-all').(($category_id != '') ? '-'.$category_id : '').$robot_session->calendar_date->format('dmY').'.xlsx', true);;

                                //header("Location: $url");

                                header('Content-Description: File Transfer');
                                header('Content-Type: application/octet-stream');
                                header('Content-Disposition: attachment; filename="'.basename($excel_path).'"');
                                header('Expires: 0');
                                header('Cache-Control: must-revalidate');
                                header('Pragma: public');
                                header('Content-Length: ' . filesize($excel_path));
                                flush(); // Flush system output buffer
                                readfile($excel_path);
                                exit;
                            }

                            /* End Excel generate */
                            break;

                        case 'pdf':
                            /** Start PDF Generate **/

                            $pdf_path = ROOT . DIRECTORY_SEPARATOR . 'webroot'. DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . 'pdfs' .  DIRECTORY_SEPARATOR . 'assortment-'.$robot_session->store->company->company_keyword.'-'.$robot_session->store->store_code.(($section_id != 'all') ? '-'.$section_id : '-all').(($category_id != '') ? '-'.$category_id : '-').$robot_session->calendar_date->format('d-m-Y').'.pdf';

                            if(file_exists($pdf_path)){
                                unlink($pdf_path);
                            }

                            if(!file_exists($pdf_path) && count($not_readed_products) > 0){

                                $product_states = [];
                                $barcode = new BarcodeGeneratorPNG();

                                $CakePdf = new \CakePdf\Pdf\CakePdf();
                                $CakePdf->template('active_assortment', 'default');
                                $CakePdf->viewVars(['barcode' => $barcode, 'section' => $section, 'category' => $category, 'dates' => $dates, 'product_states' => $product_states, 'robot_session' => $robot_session, 'type' => 'list']);
                                // Get the PDF string returned
                                //$pdf = $CakePdf->output();
                                // Or write it to file directly
                                $pdf = $CakePdf->write($pdf_path); 

                                $urlHelper = new UrlHelper(new \Cake\View\View());

                                $url = '/files/pdfs/'.'assortment-'.$robot_session->store->company->company_keyword.'-'.$robot_session->store->store_code.(($section_id != 'all') ? '-'.$section_id : '-all').(($category_id != '') ? '-'.$category_id : '-').$robot_session->calendar_date->format('d-m-Y').'.pdf';


                                // no redirect
                                //header( "Location: $url" );
                                
                                return $this->redirect($url);
                                /*header('Content-Description: File Transfer');
                                header('Content-Type: application/octet-stream');
                                header('Content-Disposition: attachment; filename="'.basename('assortment-'.$robot_session->store->company->company_keyword.'-'.$robot_session->store->store_code.(($section_id != 'all') ? '-'.$section_id : '-all').(($category_id != '') ? '-'.$category_id : '-').$robot_session->calendar_date->format('d-m-Y').'.pdf').'"');
                                header('Expires: 0');
                                header('Cache-Control: must-revalidate');
                                header('Pragma: public');
                                header('Content-Length: ' . filesize($pdf_path));
                                flush(); // Flush system output buffer
                                readfile($pdf_path);*/
                                exit;
                            }

                            break;
                        
                        default:
                            # code...
                            break;
                    }

                    break;
                
                default:
                    $this->viewBuilder()->setClassName('CakePdf.Pdf');
                    $this->viewBuilder()->options([
                        'pdfConfig' => [
                            'orientation' => 'portrait',
                            'filename' => ($section_id != null) ? 'price_differences_' .$store_data->store_code.'_'.$section_id.'_'.$robot_session->session_code.'.pdf' : 'price_differences_' .$store_data->store_code.'_'.$robot_session->session_code.'.pdf'
                        ]
                    ]);

                    $barcode = new BarcodeGeneratorPNG();

                    $this->set('barcode', $barcode);
                    $this->set('store_data', $store_data);
                    $this->set('data', $data);
                    $this->set('session_date', $robot_session->session_date);
                    $this->set('robot_session', $robot_session);
                    $this->set('type', $type);
                    break;
            }

            //return true;
        }
        else{
            //return false;  
        }
    }

    public function negativeAssortmentReport($method = 'download', $type = 'list', $robot_session_id = null, $section_id = 'all', $category_id = 'all'){

        $this->loadModel('CatalogUpdates');

        $robot_session = $this->CatalogUpdates->ProductsStores->Detections->RobotSessions->find('all')
            ->contain([
                'Stores' => [
                    'Companies'
                ]
            ])
            ->where([
                'RobotSessions.id' => $robot_session_id
            ])
            ->first();

        if($robot_session == null){
            die(__('No exist session'));
        }


        $store_id = $robot_session->store->id;
        $session_date_query = $robot_session->calendar_date->format('Y-m-d');


        $sections_cond_arr = [];
        $categories_cond_arr = [];
        $products_cond_arr = [];

        //$section_id = null;
        $section = null;
        if($section_id != 'all'){
            $products_cond_arr['ProductsStores.section_id'] = $section_id;
            $sections_cond_arr['Sections.id'] = $section_id;

            //$section_id = $this->request->data('section_id');
            $section = $this->CatalogUpdates->ProductsStores->Sections->get($section_id);
        }

        //$category_id = null;
        $category = null;
        if($category_id != 'all'){
            $products_cond_arr['ProductsStores.category_id'] = $category_id;
            $categories_cond_arr['Categories.id'] = $category_id;

            //$category_id = $this->request->data('category_id');
            $category = $this->CatalogUpdates->ProductsStores->Sections->Categories->get($category_id);
        }

        $catalogs = $this->CatalogUpdates->find('all')
            ->contain([
                'ProductsStores' => [
                    'queryBuilder' => function (\Cake\ORM\Query $query) use($products_cond_arr, $store_id, $session_date_query) {
                        return $query
                            ->select(['ProductsStores.id', 'ProductsStores.company_id', 'ProductsStores.section_id', 'ProductsStores.category_id', 'ProductsStores.description', 'ProductsStores.ean13', 'ProductsStores.internal_code'])
                            ->where([$products_cond_arr])
                            ->matching('StockUpdates', function ($q) use($store_id, $session_date_query){
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
                                        'DATE(StockUpdates.stock_updated) <=' => $session_date_query,
                                        'StockUpdates.current_stock <=' => 0
                                    ])                             
                                    ->order(['StockUpdates.stock_updated' => 'DESC']);
                                    //->limit(1);
                            })
                            ->matching('Sections', function ($q){
                                return $q
                                    ->where([
                                        'Sections.enabled' => 1
                                    ]);
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
                    'StockUpdates' => [
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
                        }*/
                    ]
                ]
            ])
            ->select([
                'CatalogUpdates.id', 'CatalogUpdates.product_store_id', 'CatalogUpdates.store_id', 'CatalogUpdates.enabled', 'CatalogUpdates.cataloged', 'CatalogUpdates.catalog_date', 'CatalogUpdates.seen'
            ])
            ->where([
                'DATE(CatalogUpdates.catalog_date)' => $session_date_query,
                'CatalogUpdates.store_id' => $store_id,
                'CatalogUpdates.seen' => 0,
                //'CatalogUpdates.enabled' => 0,
                //'CatalogUpdates.enabled' => 1,
                'CatalogUpdates.cataloged' => 1
            ])
            ->group('CatalogUpdates.product_store_id')
            ->order([
                'CatalogUpdates.catalog_date' => 'DESC'
            ])
            //->limit(1000)
            ->toArray();
            //->cache('assortment_query_'.$dates['global']['end_date']['master_date']->format('Y-m-d').'_'.$store->store_code.'_'.$this->request->data('section_id').($this->request->data('category_id') != '') ? '_'.$this->request->data('category_id') : '', 'config_cache_query');


        if(count($catalogs) > 0){

            //echo 'Hay '.count($catalogs);

            $x = 0;

            $dates = [];
            $not_readed_products = [];

            foreach($catalogs as $catalog){

                if(count($catalog->products_store->stock_updates) > 0){

                    if($catalog->products_store->stock_updates[0]->current_stock > 0){
                        continue;
                    }
                }

                if($x == 0){
                    $not_readed_products[$x] = [
                        'EAN', __('Description'), __('Int. Code'), __('Section'), __('Category'), __('Stock'), __('Enabled')
                    ];
                    $x++;
                }

                //To excel generate
                $not_readed_products[$catalog->products_store->internal_code] = [
                    $catalog->products_store->ean13,
                    $catalog->products_store->description,
                    $catalog->products_store->internal_code,
                    ($catalog->products_store->section != null) ? $catalog->products_store->section->section_code.' '.$catalog->products_store->section->section_name : __('Unknown section'),
                    ($catalog->products_store->category != null) ? $catalog->products_store->category->category_code.' '.$catalog->products_store->category->category_name : __('Unknown section'),
                    (count($catalog->products_store->stock_updates) > 0) ? $catalog->products_store->stock_updates[0]->current_stock : null,
                    ($catalog->enabled == 1) ? __('Yes') : __('No')
                ]; 

                //To report list
                $dates['global']['products'][($catalog->products_store->section != null) ? $catalog->products_store->section->section_name : __('Unknown section')][($catalog->products_store->category != null) ? $catalog->products_store->category->category_name : __('Unknown category')]['data'][$catalog->products_store->internal_code]['id'] = $catalog->products_store->id;



                
                $dates['global']['products'][($catalog->products_store->section != null) ? $catalog->products_store->section->section_name : __('Unknown section')][($catalog->products_store->category != null) ? $catalog->products_store->category->category_name : __('Unknown category')]['data'][$catalog->products_store->internal_code]['ean13'] = $catalog->products_store->ean13;
                $dates['global']['products'][($catalog->products_store->section != null) ? $catalog->products_store->section->section_name : __('Unknown section')][($catalog->products_store->category != null) ? $catalog->products_store->category->category_name : __('Unknown category')]['data'][$catalog->products_store->internal_code]['description'] = $catalog->products_store->description;
                $dates['global']['products'][($catalog->products_store->section != null) ? $catalog->products_store->section->section_name : __('Unknown section')][($catalog->products_store->category != null) ? $catalog->products_store->category->category_name : __('Unknown category')]['data'][$catalog->products_store->internal_code]['internal_code'] = $catalog->products_store->internal_code;

                $dates['global']['products'][($catalog->products_store->section != null) ? $catalog->products_store->section->section_name : __('Unknown section')][($catalog->products_store->category != null) ? $catalog->products_store->category->category_name : __('Unknown category')]['data'][$catalog->products_store->internal_code]['enabled'] = $catalog->enabled;

                $dates['global']['products'][($catalog->products_store->section != null) ? $catalog->products_store->section->section_name : __('Unknown section')][($catalog->products_store->category != null) ? $catalog->products_store->category->category_name : __('Unknown category')]['data'][$catalog->products_store->internal_code]['stock'] = (count($catalog->products_store->stock_updates) > 0) ? $catalog->products_store->stock_updates[0]->current_stock : null;
                    
            }


            switch ($method) {
                case 'cron':

                    $data_arr = [];
                    /** Start PDF Generate **/

                    $pdf_path = ROOT . DIRECTORY_SEPARATOR . 'webroot'. DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . 'pdfs' .  DIRECTORY_SEPARATOR . 'negative-assortment-'.$robot_session->store->company->company_keyword.'-'.$robot_session->store->store_code.(($section_id != 'all') ? '-'.$section_id : '-all').(($category_id != '') ? '-'.$category_id : '-').$robot_session->calendar_date->format('d-m-Y').'.pdf';

                    if(file_exists($pdf_path)){
                        unlink($pdf_path);
                    }

                    if(!file_exists($pdf_path) && count($not_readed_products) > 0){

                        $product_states = [];
                        $barcode = new BarcodeGeneratorPNG();

                        $CakePdf = new \CakePdf\Pdf\CakePdf();
                        $CakePdf->template('negative_assortment', 'default');
                        $CakePdf->viewVars(['barcode' => $barcode, 'section' => $section, 'category' => $category, 'dates' => $dates, 'product_states' => $product_states, 'robot_session' => $robot_session, 'type' => 'list']);
                        // Get the PDF string returned
                        //$pdf = $CakePdf->output();
                        // Or write it to file directly
                        $pdf = $CakePdf->write($pdf_path); 

                        $data_arr['pdf'] = [
                            'file_path' => $pdf_path,
                            'file_name' => 'negative-assortment-'.$robot_session->store->company->company_keyword.'-'.$robot_session->store->store_code.(($section_id != 'all') ? '-'.$section_id : '-all').(($category_id != '') ? '-'.$category_id : '-').$robot_session->calendar_date->format('d-m-Y').'.pdf'
                        ];
                    }

                    /** End PDF Generate **/


                    /* Excel generate */
                    $excel_path = ROOT . DIRECTORY_SEPARATOR . 'webroot'. DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . 'excels' .  DIRECTORY_SEPARATOR . 'negative-assortment-'.$robot_session->store->company->company_keyword.'-'.$robot_session->store->id.'-'.(($section_id != 'all') ? '-'.$section_id : '-all').(($category_id != '') ? '-'.$category_id : '').$robot_session->calendar_date->format('dmY').'.xlsx';

                    if(file_exists($excel_path)){
                        unlink($excel_path);
                    }

                    if(!file_exists($excel_path)){
                        
                        $spreadsheet = new Spreadsheet();
                        //$sheet = $spreadsheet->getActiveSheet();
                        //$sheet->setCellValue('A1', 'Hello World !');
                        $spreadsheet->getActiveSheet()->fromArray($not_readed_products, NULL);
                        $writer = new Xlsx($spreadsheet);

                        // We'll be outputting an excel file
                        //header('Content-type: application/vnd.ms-excel');

                        // It will be called file.xls
                        //header('Content-Disposition: attachment; filename="assortment-'.$robot_session->store->company->company_keyword.'-'.$robot_session->store->id.'-'.(($section_id != 'all') ? '-'.$section_id : '-all').(($category_id != '') ? '-'.$category_id : '').$robot_session->calendar_date->format('dmY').'.xlsx"');

                        // Write file to the browser
                        $writer->save($excel_path);

                        $data_arr['xlsx'] = [
                            'file_path' => $excel_path,
                            'file_name' => 'negative-assortment-'.$robot_session->store->company->company_keyword.'-'.$robot_session->store->id.'-'.(($section_id != 'all') ? '-'.$section_id : '-all').(($category_id != '') ? '-'.$category_id : '').$robot_session->calendar_date->format('dmY').'.xlsx'
                        ];

                    }
                    /* End Excel generate */

                    return $data_arr;
                    break;

                case 'download':

                    switch ($type) {
                        case 'xlsx':
                            /* Excel generate */
                            $excel_path = ROOT . DIRECTORY_SEPARATOR . 'webroot'. DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . 'excels' .  DIRECTORY_SEPARATOR . 'negative-assortment-'.$robot_session->store->company->company_keyword.'-'.$robot_session->store->id.'-'.(($section_id != 'all') ? '-'.$section_id : '-all').(($category_id != '') ? '-'.$category_id : '').$robot_session->calendar_date->format('dmY').'.xlsx';

                            if(file_exists($excel_path)){
                                unlink($excel_path);
                            }

                            if(!file_exists($excel_path)){
                                
                                $spreadsheet = new Spreadsheet();
                                //$sheet = $spreadsheet->getActiveSheet();
                                //$sheet->setCellValue('A1', 'Hello World !');
                                $spreadsheet->getActiveSheet()->fromArray($not_readed_products, NULL);
                                $writer = new Xlsx($spreadsheet);

                                // Write file to the browser
                                $writer->save($excel_path);

                                $urlHelper = new UrlHelper(new \Cake\View\View());

                                $url = $urlHelper->build('/files/excels/'.'negative-assortment-'.$robot_session->store->company->company_keyword.'-'.$robot_session->store->id.'-'.(($section_id != 'all') ? '-'.$section_id : '-all').(($category_id != '') ? '-'.$category_id : '').$robot_session->calendar_date->format('dmY').'.xlsx', true);;

                                //header("Location: $url");

                                header('Content-Description: File Transfer');
                                header('Content-Type: application/octet-stream');
                                header('Content-Disposition: attachment; filename="'.basename($excel_path).'"');
                                header('Expires: 0');
                                header('Cache-Control: must-revalidate');
                                header('Pragma: public');
                                header('Content-Length: ' . filesize($excel_path));
                                flush(); // Flush system output buffer
                                readfile($excel_path);
                                exit;
                            }

                            /* End Excel generate */
                            break;

                        case 'pdf':
                            /** Start PDF Generate **/

                            $pdf_path = ROOT . DIRECTORY_SEPARATOR . 'webroot'. DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . 'pdfs' .  DIRECTORY_SEPARATOR . 'negative-assortment-'.$robot_session->store->company->company_keyword.'-'.$robot_session->store->store_code.(($section_id != 'all') ? '-'.$section_id : '-all').(($category_id != '') ? '-'.$category_id : '-').$robot_session->calendar_date->format('d-m-Y').'.pdf';

                            if(file_exists($pdf_path)){
                                unlink($pdf_path);
                            }

                            if(!file_exists($pdf_path) && count($not_readed_products) > 0){

                                $product_states = [];
                                $barcode = new BarcodeGeneratorPNG();

                                $CakePdf = new \CakePdf\Pdf\CakePdf();
                                $CakePdf->template('negative_assortment', 'default');
                                $CakePdf->viewVars(['barcode' => $barcode, 'section' => $section, 'category' => $category, 'dates' => $dates, 'product_states' => $product_states, 'robot_session' => $robot_session, 'type' => 'list']);
                                // Get the PDF string returned
                                //$pdf = $CakePdf->output();
                                // Or write it to file directly
                                $pdf = $CakePdf->write($pdf_path); 

                                $urlHelper = new UrlHelper(new \Cake\View\View());

                                $url = '/files/pdfs/'.'negative-assortment-'.$robot_session->store->company->company_keyword.'-'.$robot_session->store->store_code.(($section_id != 'all') ? '-'.$section_id : '-all').(($category_id != '') ? '-'.$category_id : '-').$robot_session->calendar_date->format('d-m-Y').'.pdf';


                                // no redirect
                                //header( "Location: $url" );
                                
                                return $this->redirect($url);
                                /*header('Content-Description: File Transfer');
                                header('Content-Type: application/octet-stream');
                                header('Content-Disposition: attachment; filename="'.basename('assortment-'.$robot_session->store->company->company_keyword.'-'.$robot_session->store->store_code.(($section_id != 'all') ? '-'.$section_id : '-all').(($category_id != '') ? '-'.$category_id : '-').$robot_session->calendar_date->format('d-m-Y').'.pdf').'"');
                                header('Expires: 0');
                                header('Cache-Control: must-revalidate');
                                header('Pragma: public');
                                header('Content-Length: ' . filesize($pdf_path));
                                flush(); // Flush system output buffer
                                readfile($pdf_path);*/
                                exit;
                            }

                            break;
                        
                        default:
                            # code...
                            break;
                    }

                    break;
                
                default:

                    break;
            }
        }

        //return false;
        /*echo '<pre>';
        print_r($dates);
        echo '</pre>';

        die('fin');*/
        

        
    }

    public function blockedAssortmentReport($method = 'download', $type = 'list', $robot_session_id = null, $section_id = 'all', $category_id = 'all'){

        $this->loadModel('CatalogUpdates');

        $robot_session = $this->CatalogUpdates->ProductsStores->Detections->RobotSessions->find('all')
            ->contain([
                'Stores' => [
                    'Companies'
                ]
            ])
            ->where([
                'RobotSessions.id' => $robot_session_id
            ])
            ->first();

        if($robot_session == null){
            die(__('No exist session'));
        }


        $store_id = $robot_session->store->id;
        $session_date_query = $robot_session->session_date->format('Y-m-d');


        $sections_cond_arr = [];
        $categories_cond_arr = [];
        $products_cond_arr = [];

        //$section_id = null;
        $section = null;
        if($section_id != 'all'){
            $products_cond_arr['ProductsStores.section_id'] = $section_id;
            $sections_cond_arr['Sections.id'] = $section_id;

            //$section_id = $this->request->data('section_id');
            $section = $this->CatalogUpdates->ProductsStores->Sections->get($section_id);
        }

        //$category_id = null;
        $category = null;
        if($category_id != 'all'){
            $products_cond_arr['ProductsStores.category_id'] = $category_id;
            $categories_cond_arr['Categories.id'] = $category_id;

            //$category_id = $this->request->data('category_id');
            $category = $this->CatalogUpdates->ProductsStores->Sections->Categories->get($category_id);
        }

        $catalogs = $this->CatalogUpdates->find('all')
            ->contain([
                'ProductsStores' => [
                    'queryBuilder' => function (\Cake\ORM\Query $query) use($products_cond_arr, $store_id, $session_date_query) {
                        return $query
                            ->select(['ProductsStores.id', 'ProductsStores.company_id', 'ProductsStores.section_id', 'ProductsStores.category_id', 'ProductsStores.description', 'ProductsStores.ean13', 'ProductsStores.internal_code'])
                            ->where([$products_cond_arr])
                            ->matching('StockUpdates', function ($q) use($store_id, $session_date_query){
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
                                        'DATE(StockUpdates.stock_updated) <=' => $session_date_query,
                                        'StockUpdates.current_stock >=' => 0
                                    ])                             
                                    ->order(['StockUpdates.stock_updated' => 'DESC']);
                                    //->limit(1);
                            })
                            ->matching('Sections', function ($q){
                                return $q
                                    ->where([
                                        'Sections.enabled' => 1
                                    ]);
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
                    'StockUpdates' => [
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
                        }*/
                    ]
                ]
            ])
            ->select([
                'CatalogUpdates.id', 'CatalogUpdates.product_store_id', 'CatalogUpdates.store_id', 'CatalogUpdates.enabled', 'CatalogUpdates.cataloged', 'CatalogUpdates.catalog_date', 'CatalogUpdates.seen'
            ])
            ->where([
                'DATE(CatalogUpdates.catalog_date)' => $session_date_query,
                'CatalogUpdates.store_id' => $store_id,
                'CatalogUpdates.seen' => 0,
                'CatalogUpdates.enabled' => 0,
                'CatalogUpdates.cataloged' => 1
            ])
            ->group('CatalogUpdates.product_store_id')
            ->order([
                'CatalogUpdates.catalog_date' => 'DESC'
            ])
            //->limit(1000)
            ->toArray();
            //->cache('assortment_query_'.$dates['global']['end_date']['master_date']->format('Y-m-d').'_'.$store->store_code.'_'.$this->request->data('section_id').($this->request->data('category_id') != '') ? '_'.$this->request->data('category_id') : '', 'config_cache_query');


        if(count($catalogs) > 0){

            //echo 'Hay '.count($catalogs);

            $x = 0;

            $dates = [];
            $not_readed_products = [];

            foreach($catalogs as $catalog){

                if(count($catalog->products_store->stock_updates) > 0){

                    if($catalog->products_store->stock_updates[0]->current_stock <= 0){
                        continue;
                    }
                }
                
                if($x == 0){
                    $not_readed_products[$x] = [
                        'EAN', __('Description'), __('Int. Code'), __('Section'), __('Category'), __('Stock')
                    ];
                    $x++;
                }

                //To excel generate
                $not_readed_products[$catalog->products_store->internal_code] = [
                    $catalog->products_store->ean13,
                    $catalog->products_store->description,
                    $catalog->products_store->internal_code,
                    ($catalog->products_store->section != null) ? $catalog->products_store->section->section_code.' '.$catalog->products_store->section->section_name : __('Unknown section'),
                    ($catalog->products_store->category != null) ? $catalog->products_store->category->category_code.' '.$catalog->products_store->category->category_name : __('Unknown section'),
                    (count($catalog->products_store->stock_updates) > 0) ? $catalog->products_store->stock_updates[0]->current_stock : null
                ]; 

                //To report list
                $dates['global']['products'][($catalog->products_store->section != null) ? $catalog->products_store->section->section_name : __('Unknown section')][($catalog->products_store->category != null) ? $catalog->products_store->category->category_name : __('Unknown category')]['data'][$catalog->products_store->internal_code]['id'] = $catalog->products_store->id;



                
                $dates['global']['products'][($catalog->products_store->section != null) ? $catalog->products_store->section->section_name : __('Unknown section')][($catalog->products_store->category != null) ? $catalog->products_store->category->category_name : __('Unknown category')]['data'][$catalog->products_store->internal_code]['ean13'] = $catalog->products_store->ean13;
                $dates['global']['products'][($catalog->products_store->section != null) ? $catalog->products_store->section->section_name : __('Unknown section')][($catalog->products_store->category != null) ? $catalog->products_store->category->category_name : __('Unknown category')]['data'][$catalog->products_store->internal_code]['description'] = $catalog->products_store->description;
                $dates['global']['products'][($catalog->products_store->section != null) ? $catalog->products_store->section->section_name : __('Unknown section')][($catalog->products_store->category != null) ? $catalog->products_store->category->category_name : __('Unknown category')]['data'][$catalog->products_store->internal_code]['internal_code'] = $catalog->products_store->internal_code;

                $dates['global']['products'][($catalog->products_store->section != null) ? $catalog->products_store->section->section_name : __('Unknown section')][($catalog->products_store->category != null) ? $catalog->products_store->category->category_name : __('Unknown category')]['data'][$catalog->products_store->internal_code]['stock'] = (count($catalog->products_store->stock_updates) > 0) ? $catalog->products_store->stock_updates[0]->current_stock : null;
                    
            }


            switch ($method) {
                case 'cron':

                    $data_arr = [];
                    /** Start PDF Generate **/

                    $pdf_path = ROOT . DIRECTORY_SEPARATOR . 'webroot'. DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . 'pdfs' .  DIRECTORY_SEPARATOR . 'blocked-assortment-'.$robot_session->store->company->company_keyword.'-'.$robot_session->store->store_code.(($section_id != 'all') ? '-'.$section_id : '-all').(($category_id != '') ? '-'.$category_id : '-').$robot_session->calendar_date->format('d-m-Y').'.pdf';

                    if(file_exists($pdf_path)){
                        unlink($pdf_path);
                    }

                    if(!file_exists($pdf_path) && count($not_readed_products) > 0){

                        $product_states = [];
                        $barcode = new BarcodeGeneratorPNG();

                        $CakePdf = new \CakePdf\Pdf\CakePdf();
                        $CakePdf->template('blocked_assortment', 'default');
                        $CakePdf->viewVars(['barcode' => $barcode, 'section' => $section, 'category' => $category, 'dates' => $dates, 'product_states' => $product_states, 'robot_session' => $robot_session, 'type' => 'list']);
                        // Get the PDF string returned
                        //$pdf = $CakePdf->output();
                        // Or write it to file directly
                        $pdf = $CakePdf->write($pdf_path); 

                        $data_arr['pdf'] = [
                            'file_path' => $pdf_path,
                            'file_name' => 'blocked-assortment-'.$robot_session->store->company->company_keyword.'-'.$robot_session->store->store_code.(($section_id != 'all') ? '-'.$section_id : '-all').(($category_id != '') ? '-'.$category_id : '-').$robot_session->calendar_date->format('d-m-Y').'.pdf'
                        ];
                    }

                    /** End PDF Generate **/


                    /* Excel generate */
                    $excel_path = ROOT . DIRECTORY_SEPARATOR . 'webroot'. DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . 'excels' .  DIRECTORY_SEPARATOR . 'blocked-assortment-'.$robot_session->store->company->company_keyword.'-'.$robot_session->store->id.'-'.(($section_id != 'all') ? '-'.$section_id : '-all').(($category_id != '') ? '-'.$category_id : '').$robot_session->calendar_date->format('dmY').'.xlsx';

                    if(file_exists($excel_path)){
                        unlink($excel_path);
                    }

                    if(!file_exists($excel_path)){
                        
                        $spreadsheet = new Spreadsheet();
                        //$sheet = $spreadsheet->getActiveSheet();
                        //$sheet->setCellValue('A1', 'Hello World !');
                        $spreadsheet->getActiveSheet()->fromArray($not_readed_products, NULL);
                        $writer = new Xlsx($spreadsheet);

                        // We'll be outputting an excel file
                        //header('Content-type: application/vnd.ms-excel');

                        // It will be called file.xls
                        //header('Content-Disposition: attachment; filename="assortment-'.$robot_session->store->company->company_keyword.'-'.$robot_session->store->id.'-'.(($section_id != 'all') ? '-'.$section_id : '-all').(($category_id != '') ? '-'.$category_id : '').$robot_session->calendar_date->format('dmY').'.xlsx"');

                        // Write file to the browser
                        $writer->save($excel_path);

                        $data_arr['xlsx'] = [
                            'file_path' => $excel_path,
                            'file_name' => 'blocked-assortment-'.$robot_session->store->company->company_keyword.'-'.$robot_session->store->id.'-'.(($section_id != 'all') ? '-'.$section_id : '-all').(($category_id != '') ? '-'.$category_id : '').$robot_session->calendar_date->format('dmY').'.xlsx'
                        ];

                    }
                    /* End Excel generate */

                    return $data_arr;
                    break;

                case 'download':

                    switch ($type) {
                        case 'xlsx':
                            /* Excel generate */
                            $excel_path = ROOT . DIRECTORY_SEPARATOR . 'webroot'. DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . 'excels' .  DIRECTORY_SEPARATOR . 'blocked-assortment-'.$robot_session->store->company->company_keyword.'-'.$robot_session->store->id.'-'.(($section_id != 'all') ? '-'.$section_id : '-all').(($category_id != '') ? '-'.$category_id : '').$robot_session->calendar_date->format('dmY').'.xlsx';

                            if(file_exists($excel_path)){
                                unlink($excel_path);
                            }

                            if(!file_exists($excel_path)){
                                
                                $spreadsheet = new Spreadsheet();
                                //$sheet = $spreadsheet->getActiveSheet();
                                //$sheet->setCellValue('A1', 'Hello World !');
                                $spreadsheet->getActiveSheet()->fromArray($not_readed_products, NULL);
                                $writer = new Xlsx($spreadsheet);

                                // Write file to the browser
                                $writer->save($excel_path);

                                $urlHelper = new UrlHelper(new \Cake\View\View());

                                $url = $urlHelper->build('/files/excels/'.'blocked-assortment-'.$robot_session->store->company->company_keyword.'-'.$robot_session->store->id.'-'.(($section_id != 'all') ? '-'.$section_id : '-all').(($category_id != '') ? '-'.$category_id : '').$robot_session->calendar_date->format('dmY').'.xlsx', true);;

                                //header("Location: $url");

                                header('Content-Description: File Transfer');
                                header('Content-Type: application/octet-stream');
                                header('Content-Disposition: attachment; filename="'.basename($excel_path).'"');
                                header('Expires: 0');
                                header('Cache-Control: must-revalidate');
                                header('Pragma: public');
                                header('Content-Length: ' . filesize($excel_path));
                                flush(); // Flush system output buffer
                                readfile($excel_path);
                                exit;
                            }

                            /* End Excel generate */
                            break;

                        case 'pdf':
                            /** Start PDF Generate **/

                            $pdf_path = ROOT . DIRECTORY_SEPARATOR . 'webroot'. DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . 'pdfs' .  DIRECTORY_SEPARATOR . 'blocked-assortment-'.$robot_session->store->company->company_keyword.'-'.$robot_session->store->store_code.(($section_id != 'all') ? '-'.$section_id : '-all').(($category_id != '') ? '-'.$category_id : '-').$robot_session->calendar_date->format('d-m-Y').'.pdf';

                            if(file_exists($pdf_path)){
                                unlink($pdf_path);
                            }

                            if(!file_exists($pdf_path) && count($not_readed_products) > 0){

                                $product_states = [];
                                $barcode = new BarcodeGeneratorPNG();

                                $CakePdf = new \CakePdf\Pdf\CakePdf();
                                $CakePdf->template('blocked_assortment', 'default');
                                $CakePdf->viewVars(['barcode' => $barcode, 'section' => $section, 'category' => $category, 'dates' => $dates, 'product_states' => $product_states, 'robot_session' => $robot_session, 'type' => 'list']);
                                // Get the PDF string returned
                                //$pdf = $CakePdf->output();
                                // Or write it to file directly
                                $pdf = $CakePdf->write($pdf_path); 

                                $urlHelper = new UrlHelper(new \Cake\View\View());

                                $url = '/files/pdfs/'.'blocked-assortment-'.$robot_session->store->company->company_keyword.'-'.$robot_session->store->store_code.(($section_id != 'all') ? '-'.$section_id : '-all').(($category_id != '') ? '-'.$category_id : '-').$robot_session->calendar_date->format('d-m-Y').'.pdf';


                                // no redirect
                                //header( "Location: $url" );
                                
                                return $this->redirect($url);
                                /*header('Content-Description: File Transfer');
                                header('Content-Type: application/octet-stream');
                                header('Content-Disposition: attachment; filename="'.basename('assortment-'.$robot_session->store->company->company_keyword.'-'.$robot_session->store->store_code.(($section_id != 'all') ? '-'.$section_id : '-all').(($category_id != '') ? '-'.$category_id : '-').$robot_session->calendar_date->format('d-m-Y').'.pdf').'"');
                                header('Expires: 0');
                                header('Cache-Control: must-revalidate');
                                header('Pragma: public');
                                header('Content-Length: ' . filesize($pdf_path));
                                flush(); // Flush system output buffer
                                readfile($pdf_path);*/
                                exit;
                            }

                            break;
                        
                        default:
                            # code...
                            break;
                    }

                    break;
                
                default:

                    break;
            }
        }
    }

    public function priceDifference($method = 'download', $type = 'list', $robot_session_id = null, $section_id = null){
        $this->loadModel('RobotSessions');
        //$store_data = $this->Stores->get($store_id);


        $robot_session_data = $this->RobotSessions->get($robot_session_id);
        $session_date = $robot_session_data->session_date;
        $store_id = $robot_session_data->store_id;
        $store_data = $this->RobotSessions->Stores->get($store_id, ['contain' =>['Companies']]);

        $products_cond_arr = [];
        if($section_id != null){
            $products_cond_arr['ProductsStores.section_id'] = $section_id;
        }

        $robot_session = $this->RobotSessions->find('all')
            ->contain([
                'Detections' => [
                    'queryBuilder' => function (\Cake\ORM\Query $query) {
                        return $query
                            ->where(['Detections.price_difference_alert' => 1])
                            ->order(['Detections.location_x' => 'ASC']);
                            //->select(['ProductsStores.id', 'ProductsStores.section_id', 'ProductsStores.category_id', 'ProductsStores.product_state_id', 'ProductsStores.description', 'ProductsStores.ean13', 'ProductsStores.internal_code'])
                            //->where(['Detections.label_price <>' => null]);
                    },
                    'ProductsStores' => [
                        'queryBuilder' => function (\Cake\ORM\Query $query) use($products_cond_arr){
                            return $query
                                ->select(['ProductsStores.id', 'ProductsStores.section_id', 'ProductsStores.category_id', 'ProductsStores.description', 'ProductsStores.ean13', 'ProductsStores.internal_code'])
                                ->where([$products_cond_arr]);
                        },
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
                                    //->first();
                                    
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
                            }
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
                    ],
                    'Aisles'
                ]
            ])
            ->where([
                'RobotSessions.id' => $robot_session_id,
                'RobotSessions.includes_qa' => 1,
                'RobotSessions.price_differences_labels_finished' => 1,
                'RobotSessions.price_differences_labels_processing' => 0
            ])
            ->first();

        if(isset($robot_session->detections) && count($robot_session->detections) > 0){

            $data = [
                'stats' => [
                    'total_detections' => 0,
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


            $last_30_days_date = New Time($session_date);
            $last_30_days_date->modify('-30 days');

            foreach($robot_session->detections as $detection){

                if(count($detection->products_store->deal_updates) == 0){

                    $has_offer = 0;

                    if($store_data->company->company_keyword == 'jumbo'){
                        //Query para corroborar y buscar todos los productos x sap y verificar que no tengan oferta
                        $sap_products = $this->RobotSessions->Detections->ProductsStores->find()
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

                        $robot_sessions_list = $this->RobotSessions->find('list', [
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
                            $quantity_alerts = $this->RobotSessions->Detections->find('all')
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
                                    'section_code' => $detection->products_store->section->section_code
                                ];
                            }

                            if(!isset($data['products'][$detection->products_store->section_id]['data'][$detection->aisle->aisle_number][$detection->products_store->internal_code])){
                                $data['products'][$detection->products_store->section_id]['data'][$detection->aisle->aisle_number][$detection->products_store->internal_code] = [
                                    'ean13' => $detection->products_store->ean13,
                                    'internal_code' => $detection->products_store->internal_code,
                                    'description' => $detection->products_store->description,
                                ];

                                $data['stats']['total_products'] = $data['stats']['total_products'] + 1;
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
            }


            ksort($data['products']);


            //print_r($data['products']);

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


            if($robot_session->total_price_difference_detections == null || $robot_session->total_price_difference_detections != $data['stats']['total_detections_differences']){
                $robot_session->total_price_difference_detections = $data['stats']['total_detections_differences'];
                $this->RobotSessions->save($robot_session);
            }

            if($robot_session->total_price_difference_products == null || $robot_session->total_price_difference_products != $data['stats']['total_products']){
                $robot_session->total_price_difference_products = $data['stats']['total_products'];
                $this->RobotSessions->save($robot_session);
            }

            if(count($data['products']) > 0 && $store_data->company->company_keyword == 'jumbo'){

                //Abrir archivo .inv
                $inv_path = ROOT . DIRECTORY_SEPARATOR . 'webroot'. DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . 'labels' .  DIRECTORY_SEPARATOR . $store_data->store_code.'-'.$robot_session->session_code.'-price_difference.inv';

                $eanHelper = new EanHelper(new \Cake\View\View());
                $fp = fopen($inv_path, 'w');

                $x=0;

                foreach($data['products'] as $section_id => $info){

                    ksort($data['products'][$section_id]['data']);

                    $total_products = 0;
                    $total_detections = 0;

                    foreach($info['data'] as $aisle_number => $products){
                        
                        $total_products += count($products);

                        foreach($products as $internal_code => $product){
                            $total_detections += count($product['detections']);

                            //Archivo inv
                            //$code_length = strlen($x);
                            
                            /* primer codigo */
                            $code_id = '';

                            for($z=0; $z < (6 - strlen($x)); $z++){
                                $code_id .= '0';
                            }
                            $code_id .= $x;
                            /* fin primer codigo */

                            $ean_full = $eanHelper->format($product['ean13']);

                            /* cantidad de flejes */
                            $cant_flejes = '';

                            for($z=0; $z < (5 - strlen(count($product['detections']))); $z++){
                                $cant_flejes .= '0';
                            }
                            $cant_flejes .= count($product['detections']);
                            /* fin cantidad de flejes */


                            /* ubicacion */
                            $ubicacion = '';

                            for($z=0; $z < (6 - strlen($aisle_number)); $z++){
                                $ubicacion .= '0';
                            }
                            $ubicacion .= $aisle_number;
                            /* fin ubicacion */

                            //$ubicacion = "000000";

                            $tipo_fleje = "00911";
                            

                            $full_code = $code_id."0".$ean_full."0".$cant_flejes."0".$tipo_fleje."0".$ubicacion."001S\r\n";

                            fwrite($fp, $full_code);

                            $x++;
                        }
                    }

                    $data['products'][$section_id]['section']['count_products'] = $total_products;
                    $data['products'][$section_id]['section']['count_labels'] = $total_detections;

                    
                }

                fclose($fp);

                $data_arr['price_difference_inv'] = [
                    'file_path' => $inv_path,
                    'file_name' => $store_data->store_code.'-'.$robot_session->session_code.'-price_difference.inv'
                ];
            }

            $barcode = new BarcodeGeneratorPNG();

            switch ($method) {
                case 'cron':
                    $price_difference_pdf_path = ROOT . DIRECTORY_SEPARATOR . 'webroot'. DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . 'pdfs' .  DIRECTORY_SEPARATOR . 'price_difference_'.$store_data->company_id.$store_data->id.$robot_session->session_code.'.pdf';

                    //$this->out(__('<question>Creating Price Differences PDF</question>'));
                    $CakePdf = new \CakePdf\Pdf\CakePdf();
                    $CakePdf->template('price_difference', 'bootstrap_layout');
                    $CakePdf->viewVars(['barcode' => $barcode, 'store_data' => $store_data, 'data' => $data, 'robot_session' => $robot_session, 'type' => $type, 'session_date' => $robot_session->session_date]);
                    $pdf = $CakePdf->write($price_difference_pdf_path);

                    $data_arr['price_difference'] = [
                        'file_path' => $price_difference_pdf_path,
                        'file_name' => 'price_difference_'.$store_data->company_id.$store_data->id.$robot_session->session_code.'.pdf'
                    ];



                    /* Excel generate */
                    /*$excel_path = ROOT . DIRECTORY_SEPARATOR . 'webroot'. DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . 'excels' .  DIRECTORY_SEPARATOR . 'price_difference_'.$store_data->company_id.$store_data->id.$robot_session->session_code.'.xlsx';

                    if(file_exists($excel_path)){
                        unlink($excel_path);
                    }

                    if(!file_exists($excel_path)){

                        // Inicio Armar arreglo con diferencias de precio por fleje
                        $differences_products_excel = [];

                        if(count($data['products']) > 0){

                            $x = 0;
                            $numberHelper = new NumberHelper(new \Cake\View\View());

                            foreach($data['products'] as $id_section => $data_array){
                                if(count($data_array['data']) > 0){
                                    foreach($data_array['data'] as $aisle_code => $products){
                                        if(count($products) > 0){
                                            foreach($products as $sku_code => $product){

                                                if(count($product['detections']) > 0){
                                                    foreach($product['detections'] as $detection){
                                                        
                                                        if($x == 0){
                                                            $differences_products_excel[$x] = [
                                                                'EAN', __('Int. Code'), __('Description'), __('Detected price'), __('Master price'), __('Section'), __('Aisle'), __('Lineal meter'), __('Height tray'), __('Last price change'), __('Days with difference')
                                                            ];

                                                            $x++;
                                                        }

                                                        $differences_products_excel[$x] = [
                                                            $product['ean13'],
                                                            $product['internal_code'],
                                                            $product['description'],
                                                            $detection['label_price'],
                                                            $product['price_update']['price'],
                                                            $data_array['section']['section_name'],
                                                            $aisle_code,
                                                            $numberHelper->precision($detection['location_x'], 2).' mts',
                                                            $numberHelper->precision($detection['location_z'], 2).' mts',
                                                            $product['price_update']['company_updated']->format('Y-m-d- H:i:s'),
                                                            $product['price_update']['days_with_difference']
                                                        ]; 

                                                        $x++;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        //Fin Armar arreglo con diferencias de precio por fleje

                        if(count($differences_products_excel) > 0){

                            $spreadsheet = new Spreadsheet();
                            $spreadsheet->getActiveSheet()->fromArray($differences_products_excel, NULL);
                            $writer = new Xlsx($spreadsheet);

                            // Write file to the browser
                            $writer->save($excel_path);

                            $data_arr['price_difference_xlsx'] = [
                                'file_path' => $excel_path,
                                'file_name' => 'price_difference_'.$store_data->company_id.$store_data->id.$robot_session->session_code.'.xlsx'
                            ];
                        }
                    }*/

                    /* End Excel generate */

                    // Homecenter api send to print
                    //Sodimac API de impresión de flejes
                    if($store_data->company->company_keyword == 'homecenter'){

                        /*echo '<pre>';
                        print_r($data['stats']);
                        echo '</pre>';*/

                        if(count($data['products']) > 0){
                            $x = 0;
                            $y = 1;
                            $count_labels = 0;
                            $sku_string = '';

                            $store_code = trim($store_data->store_code, 'HC');

                            $shell = new \Cake\Console\Shell;



                            $http = new Client();
                            $url ='https://apiapp.pechera.p.azurewebsites.net:443/v1/Productos/CL/'.$store_code.'/[LABELS]/Imprimir/Flejes';

                            foreach($data['products'] as $id_section => $data_array){
                                if(count($data_array['data']) > 0){

                                    foreach($data_array['data'] as $aisle_code => $products){

                                        if(count($products) > 0){

                                            foreach($products as $sku_code => $product){

                                                if(count($product['detections']) > 0){
                                                    foreach($product['detections'] as $detection){

                                                        if($x > 2){ 

                                                            $sku_string = substr($sku_string, 0, -3);
                                                            $sku_string = $this->limpia_espacios($sku_string);
                                                            
                                                            //Lanzar flejes a API
                                                            $current_url = $url;
                                                            $current_url = str_replace('[LABELS]', $sku_string, $current_url);

                                                            $response = $http->get($current_url);

                                                            if($response->getStatusCode() != 204){

                                                                $shell->out('Error '.$response->code.': '.$current_url);
                                                                $count_labels = $count_labels - 3;
                                                                $slackHelper = new SlackHelper(new \Cake\View\View());
                                                                $slack_response = $slackHelper->message(__('[Price Differences] {0} {1}: Error to send labels to Sodimac API (URL: {2})', [$store_data->store_code, $robot_session->session_date->format('d-m'), $current_url]));
                                                                sleep(3);
                                                            }
                                                            else{
                                                                $shell->out('Send 3 labels to api. Url: '.$current_url);

                                                                sleep(3);
                                                            }

                                                            $y++;
                                                            $x = 1;
                                                            $sku_string = $product['internal_code'].'%2C';
                                                            $count_labels++;
                                                        }
                                                        else{
                                                            $sku_string = $product['internal_code'].'%2C'.$sku_string;
                                                            $count_labels++;
                                                            $x++;
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }

                            if($sku_string != ''){
                                $sku_string = substr($sku_string, 0, -3);
                                $sku_string = $this->limpia_espacios($sku_string);

                                $current_url = $url;
                                $current_url = str_replace('[LABELS]', $sku_string, $current_url);

                                $response = $http->get($current_url);

                                if($response->getStatusCode() != 204){

                                    $shell->out('Error '.$response->code.': '.$current_url);
                                    $count_labels = $count_labels - 3;

                                    $slackHelper = new SlackHelper(new \Cake\View\View());
                                    $slack_response = $slackHelper->message(__('[Price Differences] {0} {1}: Error to send labels to Sodimac API (URL: {2})', [$store_data->store_code, $robot_session->session_date->format('d-m'), $current_url]));
                                    sleep(3);
                                }
                                else{
                                    $shell->out('Send 3 labels to api. Url: '.$current_url);
                                    sleep(3);
                                }
                            }

                            $shell->out('Se han enviado '.$count_labels.' flejes a la cola de impresión');

                            $slackHelper = new SlackHelper(new \Cake\View\View());
                            $slack_response = $slackHelper->message(__('[Price Differences] {0} {1}: {2} / {3} labels sent to the Sodimac API', [$store_data->store_code, $robot_session->session_date->format('d-m'), $count_labels, $data['stats']['total_detections_differences']]));
                        }
                    }

                    return $data_arr;
                    break;

                case 'download':


                    switch ($type) {
                        case 'xlsx':

                            /* Excel generate */
                            $excel_path = ROOT . DIRECTORY_SEPARATOR . 'webroot'. DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . 'excels' .  DIRECTORY_SEPARATOR . 'price_difference_'.$store_data->company_id.$store_data->id.$robot_session->session_code.'.xlsx';

                            if(file_exists($excel_path)){
                                unlink($excel_path);
                            }

                            if(!file_exists($excel_path)){

                                // Inicio Armar arreglo con diferencias de precio por fleje
                                $differences_products_excel = [];

                                if(count($data['products']) > 0){

                                    $x = 0;
                                    $numberHelper = new NumberHelper(new \Cake\View\View());

                                    foreach($data['products'] as $id_section => $data_arr){
                                        if(count($data_arr['data']) > 0){
                                            foreach($data_arr['data'] as $aisle_code => $products){
                                                if(count($products) > 0){
                                                    foreach($products as $sku_code => $product){

                                                        if(count($product['detections']) > 0){
                                                            foreach($product['detections'] as $detection){
                                                                
                                                                if($x == 0){
                                                                    $differences_products_excel[$x] = [
                                                                        'EAN', __('Int. Code'), __('Description'), __('Detected price'), __('Master price'), __('Section'), __('Aisle'), __('Lineal meter'), __('Height tray'), __('Last price change'), __('Days with difference'), __('Alerts in last 30 days')
                                                                    ];

                                                                    $x++;
                                                                }

                                                                $differences_products_excel[$x] = [
                                                                    $product['ean13'],
                                                                    $product['internal_code'],
                                                                    $product['description'],
                                                                    $detection['label_price'],
                                                                    $product['price_update']['price'],
                                                                    $data_arr['section']['section_name'],
                                                                    $aisle_code,
                                                                    $numberHelper->precision($detection['location_x'], 2).' mts',
                                                                    $numberHelper->precision($detection['location_z'], 2).' mts',
                                                                    $product['price_update']['company_updated']->format('Y-m-d- H:i:s'),
                                                                    $product['price_update']['days_with_difference'],
                                                                    $product['price_update']['30_days_alerts']
                                                                ]; 

                                                                $x++;
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                                //Fin Armar arreglo con diferencias de precio por fleje

                                if(count($differences_products_excel) > 0){

                                    $spreadsheet = new Spreadsheet();
                                    $spreadsheet->getActiveSheet()->fromArray($differences_products_excel, NULL);
                                    $writer = new Xlsx($spreadsheet);

                                    // Write file to the browser
                                    $writer->save($excel_path);

                                    $urlHelper = new UrlHelper(new \Cake\View\View());

                                    $url = $urlHelper->build('/files/excels/'.'price_difference_'.$store_data->company_id.$store_data->id.$robot_session->session_code.'.xlsx', true);


                                    header('Content-Description: File Transfer');
                                    header('Content-Type: application/octet-stream');
                                    header('Content-Disposition: attachment; filename="'.basename($excel_path).'"');
                                    header('Expires: 0');
                                    header('Cache-Control: must-revalidate');
                                    header('Pragma: public');
                                    header('Content-Length: ' . filesize($excel_path));
                                    flush(); // Flush system output buffer
                                    readfile($excel_path);
                                    exit;
                                }
                            }

                            /* End Excel generate */
                            break;

                        case 'pdf':

                            /** Start PDF Generate **/
                            $price_difference_pdf_path = ROOT . DIRECTORY_SEPARATOR . 'webroot'. DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . 'pdfs' .  DIRECTORY_SEPARATOR . 'price_difference_'.$store_data->company_id.$store_data->id.$robot_session->session_code.'.pdf';

                            $product_states = [];

                            //$this->out(__('<question>Creating Price Differences PDF</question>'));
                            $CakePdf = new \CakePdf\Pdf\CakePdf();
                            $CakePdf->template('price_difference', 'bootstrap_layout');
                            $CakePdf->viewVars(['barcode' => $barcode, 'store_data' => $store_data, 'data' => $data, 'robot_session' => $robot_session, 'type' => 'list', 'session_date' => $robot_session->session_date]);
                            $pdf = $CakePdf->write($price_difference_pdf_path);

                            $urlHelper = new UrlHelper(new \Cake\View\View());

                            $url = '/files/pdfs/'.'price_difference_'.$store_data->company_id.$store_data->id.$robot_session->session_code.'.pdf';
                            
                            return $this->redirect($url);

                            break;
                        
                        default:
                            # code...
                            break;
                    }

                    break;
                
                default:
                    $this->viewBuilder()->setClassName('CakePdf.Pdf');
                    $this->viewBuilder()->options([
                        'pdfConfig' => [
                            'orientation' => 'portrait',
                            'filename' => ($section_id != null) ? 'price_differences_' .$store_data->store_code.'_'.$section_id.'_'.$robot_session->session_code.'.pdf' : 'price_differences_' .$store_data->store_code.'_'.$robot_session->session_code.'.pdf'
                        ]
                    ]);

                    $barcode = new BarcodeGeneratorPNG();

                    $this->set('barcode', $barcode);
                    $this->set('store_data', $store_data);
                    $this->set('data', $data);
                    $this->set('session_date', $robot_session->session_date);
                    $this->set('robot_session', $robot_session);
                    $this->set('type', $type);
                    break;
            }
        }
        else{
            echo 'no existen detecciones';
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

    function limpia_espacios($cadena){
        $cadena = str_replace(' ', '', $cadena);
        return $cadena;
    }

    public function stockAlert($method = 'download', $type = 'list', $robot_session_id = null, $section_id = null){

        $this->loadModel('RobotSessions');
        //$store_data = $this->Stores->get($store_id);

        $robot_session_data = $this->RobotSessions->get($robot_session_id);
        $session_date = $robot_session_data->session_date;
        $store_id = $robot_session_data->store_id;
        $store_data = $this->RobotSessions->Stores->get($store_id, ['contain' =>['Companies']]);

        $products_cond_arr = [];
        if($section_id != null){
            $products_cond_arr['ProductsStores.section_id'] = $section_id;
        }

        $last_30_days_date = New Time($session_date);
        $catalog_date = New Time($session_date);
        $catalog_date->modify('-1 days');
        $last_30_days_date->modify('-30 days');

        $catalog_date_query = $catalog_date->format('Y-m-d');



        $robot_session = $this->RobotSessions->find('all')
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
                        'queryBuilder' => function (\Cake\ORM\Query $query) use($products_cond_arr){
                            return $query
                                ->select(['ProductsStores.id', 'ProductsStores.section_id', 'ProductsStores.category_id', 'ProductsStores.description', 'ProductsStores.ean13', 'ProductsStores.internal_code'])
                                ->where([$products_cond_arr]);
                        },
                        'StockUpdates' => [
                            'fields' => [
                                'StockUpdates.id',
                                'StockUpdates.product_store_id',
                                'StockUpdates.current_stock',
                                'StockUpdates.last_stock',
                                'StockUpdates.stock_updated',
                            ],
                            'conditions' => [
                                'StockUpdates.stock_updated <=' => $session_date,
                                'StockUpdates.store_id' => $store_id,                            
                            ],
                            'sort' => ['StockUpdates.stock_updated' => 'DESC']
                        ],
                        'CatalogUpdates' => [
                            'fields' => [
                                'CatalogUpdates.id',
                                'CatalogUpdates.product_store_id',
                                'CatalogUpdates.enabled',
                            ],
                            'conditions' => [
                                'DATE(CatalogUpdates.catalog_date)' => $catalog_date_query,
                                'CatalogUpdates.store_id' => $store_id,                            
                            ],
                            'sort' => ['CatalogUpdates.catalog_date' => 'DESC']
                            /*'queryBuilder' => function (\Cake\ORM\Query $query) use($store_id, $session_date){
                                return $query
                                    //->limit(2)
                                    ->where([
                                        'PriceUpdates.company_updated <=' => $session_date,
                                        'PriceUpdates.store_id' => $store_id
                                    ])
                                    ->order([
                                        'PriceUpdates.company_updated' => 'DESC'
                                    ]);
                                    //->first();
                                    
                            }*/
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
                    ],
                    'Aisles'
                ]
            ])
            ->where([
                'RobotSessions.id' => $robot_session_id,
                'RobotSessions.store_id' => $store_id,
                'RobotSessions.includes_qa' => 1,
                'RobotSessions.includes_facing' => 1,
                'RobotSessions.facing_labels_processing' => 0,
                'RobotSessions.facing_labels_finished' => 1,
            ])
            ->first();
            //->cache('price_difference_query_'.$this->request->data('session_date').'_'.$store_data->store_code, 'config_cache_query');

        $data = [
            'stats' => [
                'total_products_in_alerts' => 0,
                'total_detections' => 0
            ],
            'products' => [

            ]
        ];

        if(isset($robot_session->detections) && count($robot_session->detections) > 0){

            
            foreach($robot_session->detections as $detection){


                if(!isset($data['products'][$detection->products_store->section_id]['section'])){
                    $data['products'][$detection->products_store->section_id]['section'] = [
                        'id' => $detection->products_store->section_id,
                        'section_name' => (isset($detection->products_store->section)) ? $detection->products_store->section->section_name : __('Unknown section'),
                        'section_code' => (isset($detection->products_store->section)) ? $detection->products_store->section->section_code : 'U/C'
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

                $robot_sessions_list = $this->RobotSessions->find('list', [
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

                if(count($robot_sessions_list) > 0){
                    $quantity_alerts = $this->RobotSessions->Detections->find('all')
                        ->where([
                            'Detections.stock_alert' => 1,
                            'Detections.product_store_id' => $detection->products_store->id,
                            'Detections.robot_session_id IN' => $robot_sessions_list
                        ])
                        ->count();
                }
                else{
                    $quantity_alerts = 0;
                }

                $data['products'][$detection->products_store->section_id]['data'][$detection->aisle->aisle_number][$detection->products_store->internal_code]['stock_update'] = [
                    /*'stock' => $detection->products_store->stock_updates[0]->current_stock,
                    'last_stock' => $detection->products_store->stock_updates[0]->last_stock,
                    'stock_warehouse' => __('No available'),
                    'stock_in_transit' => __('No available'),
                    'company_updated' => $detection->products_store->stock_updates[0]->stock_updated*/


                    'stock' => $detection->stock_on_hand,//$detection->products_store->last_stock->current_stock,
                    'last_stock' => null,//$detection->products_store->last_stock->last_stock,
                    'stock_warehouse' => $detection->stock_in_warehouse,
                    'stock_in_transit' => $detection->stock_in_transit,
                    'company_updated' => null,//$detection->products_store->last_stock->stock_updated,
                    '30_days_alerts' => $quantity_alerts
                ]; 

                $data['products'][$detection->products_store->section_id]['data'][$detection->aisle->aisle_number][$detection->products_store->internal_code]['detections'][] = [
                    'detection_code' => $detection->detection_code,
                    'label_price' => $detection->label_price,
                    'location_x' => $detection->location_x,
                    'location_y' => $detection->location_y,
                    'location_z' => $detection->location_z,
                    'aisle' => $detection->aisle->aisle_number
                ];

                if(count($detection->products_store->catalog_updates) > 0){
                    $data['products'][$detection->products_store->section_id]['data'][$detection->aisle->aisle_number][$detection->products_store->internal_code]['catalog_update'] = [
                        'enabled' => ($detection->products_store->catalog_updates[0]->enabled == 1) ? 1 : 0
                    ]; 
                }
                

                $data['stats']['total_detections'] = $data['stats']['total_detections'] + 1;
            }

            ksort($data['products']);


            /*echo '<pre>';
            print_r($data['products']);
            echo '</pre>';

            die();*/


            if($robot_session->total_stock_alert_products == null || $robot_session->total_stock_alert_products != $data['stats']['total_products_in_alerts']){
                $robot_session->total_stock_alert_products = $data['stats']['total_products_in_alerts'];
                $this->RobotSessions->save($robot_session);
            }

            if($robot_session->total_stock_alert_detections == null || $robot_session->total_stock_alert_detections != $data['stats']['total_detections']){
                $robot_session->total_stock_alert_detections = $data['stats']['total_detections'];
                $this->RobotSessions->save($robot_session);
            }

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

            $barcode = new BarcodeGeneratorPNG();


            switch ($method) {
                case 'cron':
                    $stock_alert_pdf_path = ROOT . DIRECTORY_SEPARATOR . 'webroot'. DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . 'pdfs' .  DIRECTORY_SEPARATOR . 'stock_alert_'.$store_data->company_id.$store_data->id.$robot_session->session_code.'.pdf';

                    //$this->out(__('<question>Creating Price Differences PDF</question>'));
                    $CakePdf = new \CakePdf\Pdf\CakePdf();
                    $CakePdf->template('stock_alert', 'bootstrap_layout');
                    $CakePdf->viewVars(['barcode' => $barcode, 'store_data' => $store_data, 'data' => $data, 'robot_session' => $robot_session, 'type' => $type, 'session_date' => $robot_session->session_date, 'robot_session_id' => $robot_session_data->id]);
                    $pdf = $CakePdf->write($stock_alert_pdf_path);

                    $data_arr = [
                        'file_path' => $stock_alert_pdf_path,
                        'file_name' => 'stock_alert_' .$store_data->store_code.'_'.$robot_session_data->session_code.'.pdf'
                    ];

                    return $data_arr;
                    break;

                case 'download':

                    switch ($type) {
                        case 'xlsx':

                            /* Excel generate */
                            $excel_path = ROOT . DIRECTORY_SEPARATOR . 'webroot'. DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . 'excels' .  DIRECTORY_SEPARATOR . 'stock_alert_'.$store_data->company_id.$store_data->id.$robot_session->session_code.'.xlsx';

                            if(file_exists($excel_path)){
                                unlink($excel_path);
                            }

                            if(!file_exists($excel_path)){

                                // Inicio Armar arreglo con diferencias de precio por fleje
                                $stock_products_excel = [];

                                if(count($data['products']) > 0){

                                    $x = 0;
                                    $numberHelper = new NumberHelper(new \Cake\View\View());

                                    foreach($data['products'] as $id_section => $data_arr){
                                        if(count($data_arr['data']) > 0){
                                            foreach($data_arr['data'] as $aisle_code => $products){
                                                if(count($products) > 0){
                                                    foreach($products as $sku_code => $product){

                                                        if(count($product['detections']) > 0){
                                                            foreach($product['detections'] as $detection){
                                                                
                                                                if($x == 0){
                                                                    $stock_products_excel[$x] = [
                                                                        'EAN', __('Int. Code'), __('Description'), /*__('Detected price'),*/ __('Section'), __('Aisle'), __('Lineal meter'), __('Height tray'), __('Stock')
                                                                    ];

                                                                    $x++;
                                                                }

                                                                $stock_products_excel[$x] = [
                                                                    $product['ean13'],
                                                                    $product['internal_code'],
                                                                    $product['description'],
                                                                    //$detection['label_price'],
                                                                    $data_arr['section']['section_name'],
                                                                    $aisle_code,
                                                                    $numberHelper->precision($detection['location_x'], 2).' mts',
                                                                    $numberHelper->precision($detection['location_z'], 2).' mts',
                                                                    (isset($product['stock_update'])) ? $product['stock_update']['stock'] : __('No data')
                                                                ]; 

                                                                $x++;
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                                //Fin Armar arreglo con diferencias de precio por fleje

                                if(count($stock_products_excel) > 0){

                                    $spreadsheet = new Spreadsheet();
                                    $spreadsheet->getActiveSheet()->fromArray($stock_products_excel, NULL);
                                    $writer = new Xlsx($spreadsheet);

                                    // Write file to the browser
                                    $writer->save($excel_path);

                                    $urlHelper = new UrlHelper(new \Cake\View\View());

                                    $url = $urlHelper->build('/files/excels/'.'stock_alert_'.$store_data->company_id.$store_data->id.$robot_session->session_code.'.xlsx', true);


                                    header('Content-Description: File Transfer');
                                    header('Content-Type: application/octet-stream');
                                    header('Content-Disposition: attachment; filename="'.basename($excel_path).'"');
                                    header('Expires: 0');
                                    header('Cache-Control: must-revalidate');
                                    header('Pragma: public');
                                    header('Content-Length: ' . filesize($excel_path));
                                    flush(); // Flush system output buffer
                                    readfile($excel_path);
                                    exit;
                                }
                            }

                            /* End Excel generate */
                            break;

                        case 'pdf':

                            /** Start PDF Generate **/
                            $stock_alert_pdf_path = ROOT . DIRECTORY_SEPARATOR . 'webroot'. DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . 'pdfs' .  DIRECTORY_SEPARATOR . 'stock_alert_'.$store_data->company_id.$store_data->id.$robot_session->session_code.'.pdf';

                            //$this->out(__('<question>Creating Price Differences PDF</question>'));
                            $CakePdf = new \CakePdf\Pdf\CakePdf();
                            $CakePdf->template('stock_alert', 'bootstrap_layout');
                            $CakePdf->viewVars(['barcode' => $barcode, 'store_data' => $store_data, 'data' => $data, 'robot_session' => $robot_session, 'type' => 'list', 'session_date' => $robot_session->session_date, 'robot_session_id' => $robot_session_data->id]);
                            $pdf = $CakePdf->write($stock_alert_pdf_path);

                            $urlHelper = new UrlHelper(new \Cake\View\View());

                            $url = '/files/pdfs/'.'stock_alert_'.$store_data->company_id.$store_data->id.$robot_session->session_code.'.pdf';
                            
                            return $this->redirect($url);

                            break;
                        
                        default:
                            # code...
                            break;
                    }

                    /*$this->viewBuilder()->setClassName('CakePdf.Pdf');
                    $this->viewBuilder()->options([
                        'pdfConfig' => [
                            'orientation' => 'portrait',
                            'filename' => ($section_id != null) ? 'stock_alert_' .$store_data->store_code.'_'.$section_id.'_'.$robot_session->session_code.'.pdf' : 'stock_alert_' .$store_data->store_code.'_'.$robot_session_data->session_code.'.pdf'
                        ]
                    ]);

                    $this->set('barcode', $barcode);
                    $this->set('robot_session_id', $robot_session_data->id);    

                    $this->set('data', $data);
                    $this->set('store_data', $store_data);
                    $this->set('session_code', $robot_session_data->session_code);
                    $this->set('robot_session_id', $robot_session_data->id);
                    $this->set('robot_session', $robot_session_data);
                    $this->set('session_date', $session_date);
                    $this->set('type', $type);*/

                    break;
                
                default:
                    $this->viewBuilder()->setClassName('CakePdf.Pdf');
                    $this->viewBuilder()->options([
                        'pdfConfig' => [
                            'orientation' => 'portrait',
                            'filename' => ($section_id != null) ? 'stock_alert_' .$store_data->store_code.'_'.$section_id.'_'.$robot_session->session_code.'.pdf' : 'stock_alert_' .$store_data->store_code.'_'.$robot_session_data->session_code.'.pdf'
                        ]
                    ]);

                    $this->set('barcode', $barcode);
                    $this->set('robot_session_id', $robot_session_data->id);    

                    $this->set('data', $data);
                    $this->set('store_data', $store_data);
                    $this->set('session_code', $robot_session_data->session_code);
                    $this->set('robot_session_id', $robot_session_data->id);
                    $this->set('robot_session', $robot_session_data);
                    $this->set('session_date', $session_date);
                    $this->set('type', $type);
                    break;
            }
        }
        else{
            echo 'nada';
            die();
        }
    }
}