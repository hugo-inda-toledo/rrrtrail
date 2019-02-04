<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Mailer\Email;
use Cake\I18n\Time;

class EmailsController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Users');
        $this->Auth->allow(['sendInitMasterProcessEmail', 'sendInvitation']);
    }

    function sendInitMasterProcessEmail($data = array())
    {
        if($data != null)
        {
            //$this->Users->getAdminSuscribeUsers;
            $users[] = [
                'name' => 'Hugo',
                'last_name' => 'Inda',
                'email' => 'hugo@zippedi.com'
            ];

            $users[] = [
                'name' => 'Juan Pablo',
                'last_name' => 'Valencia',
                'email' => 'juanpablo@zippedi.com'
            ];

            if(count($users) > 0){
                foreach($users as $user){
                    $email = new Email('default');
                    $email->template('init_process', 'admin_layout');
                    $email->emailFormat('html');
                    $email->subject(__('[{0}] {1} - {2}: Load Startup master cataloged for {3}', [$data['store']['store_code'], $data['company']['company_name'], $data['store']['store_name'], $data['master_date']->format('d-m-Y')]));
                    $email->viewVars(['user' => $user, 'data' => $data]);
                    $email->helpers(['Html', 'Form']);
                    $email->attachments([
                        'new_zippedi_logo_horizontal.png' => [
                            'file' => ROOT.'/webroot/img/new_zippedi_logo_horizontal.png',
                            'mimetype' => 'image/png',
                            'contentId' => 'logo-id'
                        ],
                        'database-info.png' => [
                            'file' => ROOT.'/webroot/img/emails/master_process/database-info.png',
                            'mimetype' => 'image/png',
                            'contentId' => 'database-info-id'
                        ]
                    ]);

                    $email->from (array('reports@zippedi.com' => 'Zippedi Reports'));
                    $email->to($user['email'], $user['name'].' '.$user['last_name']);

                    if($email->send())
                    {
                        return true;
                    }
                    else
                    {
                        return false;
                    }
                }
            }
            else{
                return false;
            }
        }
        else
        {
            return false;
        }
    }

    function sendFinishMasterProcessEmail($data = array(), $master_products_quantity = null)
    {
        if($data != null)
        {
            //$users = $this->Users->getAdminSuscribeUsers('master_process');
            $users[] = [
                'name' => 'Hugo',
                'last_name' => 'Inda',
                'email' => 'hugo@zippedi.com'
            ];

            if(count($users) > 0){
                foreach($users as $user){
                    $email = new Email('default');
                    $email->template('finish_process', 'admin_layout');
                    $email->emailFormat('html');
                    $email->subject(__('[{0}] {1} - {2}: Finish load master cataloged for {3}', [$data['store']['store_code'], $data['company']['company_name'], $data['store']['store_name'], $data['master_date']->format('d-m-Y')]));
                    $email->viewVars(['user' => $user, 'data' => $data, 'master_products_quantity' => $master_products_quantity]);
                    $email->helpers(['Html', 'Form']);
                    $email->attachments([
                        'new_zippedi_logo_horizontal.png' => [
                            'file' => ROOT.'/webroot/img/new_zippedi_logo_horizontal.png',
                            'mimetype' => 'image/png',
                            'contentId' => 'logo-id'
                        ],
                        'database-success.png' => [
                            'file' => ROOT.'/webroot/img/emails/master_process/database-success.png',
                            'mimetype' => 'image/png',
                            'contentId' => 'database-success-id'
                        ]
                    ]);

                    $email->from (array('reports@zippedi.com' => 'Zippedi Reports'));
                    $email->to($user['email'], $user['name'].' '.$user['last_name']);

                    if($email->send())
                    {
                        return true;
                    }
                    else
                    {
                        return false;
                    }
                }
            }
            else{
                return false;
            }
        }
        else
        {
            return false;
        }
    }


    function sendDetectionsProcessEmail($data = array()){

        /*$data = [
                    'store' => [
                        'store_name' => $store->store_name,
                        'store_code' => $store->store_name,
                    ],
                    'company' => [
                        'company_name' => $company->company_name,
                        'company_logo' => $company->company_logo
                    ],
                    'robot_session' => [
                        'session_code' => $robot_session->session_code,
                        'session_date' => $robot_session->session_date
                    ],
                    'products_quantity' => count($seen_labels)
                ];*/

        if($data != null)
        {
            //$users = $this->Users->getAdminSuscribeUsers('master_process');
            $users= [
                [
                    'name' => 'Hugo',
                    'last_name' => 'Inda',
                    'email' => 'hugo@zippedi.com'
                ],
                /*[
                    'name' => 'Ariel',
                    'last_name' => 'Schilkrut',
                    'email' => 'ariel@zippedi.com'
                ],*/
            ];

            if(count($users) > 0){
                foreach($users as $user){
                    $email = new Email('default');
                    $email->template('detections_init_process', 'admin_layout');
                    $email->emailFormat('html');
                    $email->subject(__('[Startup detections process] {0} - {1}({2}) / {3}', [$data['company']['company_name'], $data['store']['store_name'], $data['store']['store_code'], $data['robot_session']['session_date']->format('d-m-Y H:i')]));
                    $email->viewVars(['user' => $user, 'data' => $data]);
                    $email->helpers(['Html', 'Form']);
                    $email->attachments([
                        'new_zippedi_logo_horizontal.png' => [
                            'file' => ROOT.'/webroot/img/new_zippedi_logo_horizontal.png',
                            'mimetype' => 'image/png',
                            'contentId' => 'logo-id'
                        ],
                        'detections-init-success.png' => [
                            'file' => ROOT.'/webroot/img/emails/detections/detections-init-success.png',
                            'mimetype' => 'image/png',
                            'contentId' => 'detections-init-success-id'
                        ]
                    ]);

                    $email->from (array('reports@zippedi.com' => 'Zippedi Reports'));
                    $email->to($user['email'], $user['name'].' '.$user['last_name']);

                    if($email->send())
                    {
                        return true;
                    }
                    else
                    {
                        return false;
                    }
                }
            }
            else{
                return false;
            }
        }
        else
        {
            return false;
        }
    }

    function sendFinishDetectionsProcessEmail($data = array(), $files = array()){
        if($data != null)
        {
            $this->loadModel('RobotReports');
            $robot_report = $this->RobotReports->find('all')
                ->where([
                    'RobotReports.report_keyword' => $data['type_report']
                ])
                ->first();


            $users = $this->getAllRetailUsersForStore($robot_report->id, $data['robot_session']['store_id']);
            print_r($users);

            //Iteracion para nuevo modelo
            if(count($users) > 0){
                foreach($users as $user){
                    //$email = new Email('Sendgrid');
                    $email = new Email('default');
                    $email->template('detections_finish_process', 'modern');
                    $email->emailFormat('html');

                    $email->viewVars(['user' => $user, 'data' => $data]);
                    $email->helpers(['Html', 'Form']);
                

                    //Existen los 2 archivos
                    if(isset($files['price_difference']['file_path']) && file_exists($files['price_difference']['file_path']) && isset($files['stock_alert']['file_path']) && file_exists($files['stock_alert']['file_path'])){

                        if(isset($files['price_difference_inv']['file_path']) && file_exists($files['price_difference_inv']['file_path'])){

                            $email->attachments([
                                $files['price_difference_inv']['file_name'] => [
                                    'file' => $files['price_difference_inv']['file_path'],
                                ],
                                $files['price_difference']['file_name'] => [
                                    'file' => $files['price_difference']['file_path'],
                                ],
                                /*$files['price_difference_xlsx']['file_name'] => [
                                    'file' => $files['price_difference_xlsx']['file_path'],
                                ],*/
                                $files['stock_alert']['file_name'] => [
                                    'file' => $files['stock_alert']['file_path'],
                                ],
                                'onlyletters2.png' => [
                                    'file' => ROOT.'/webroot/img/onlyletters2.png',
                                    'mimetype' => 'image/png',
                                    'contentId' => 'logo-id'
                                ],
                                'new_zippedi_logo_vertical.png' => [
                                    'file' => ROOT.'/webroot/img/new_zippedi_logo_vertical.png',
                                    'mimetype' => 'image/png',
                                    'contentId' => 'logo-team-id'
                                ]
                            ]);
                        }
                        else{
                            $email->attachments([
                                $files['price_difference']['file_name'] => [
                                    'file' => $files['price_difference']['file_path'],
                                ],
                                /*$files['price_difference_xlsx']['file_name'] => [
                                    'file' => $files['price_difference_xlsx']['file_path'],
                                ],*/
                                $files['stock_alert']['file_name'] => [
                                    'file' => $files['stock_alert']['file_path'],
                                ],
                                'onlyletters2.png' => [
                                    'file' => ROOT.'/webroot/img/onlyletters2.png',
                                    'mimetype' => 'image/png',
                                    'contentId' => 'logo-id'
                                ],
                                'new_zippedi_logo_vertical.png' => [
                                    'file' => ROOT.'/webroot/img/new_zippedi_logo_vertical.png',
                                    'mimetype' => 'image/png',
                                    'contentId' => 'logo-team-id'
                                ]
                            ]);
                        }

                        $email->subject(__('Detections process completed: {0} - {1}({2}) / {3}', [$data['company']['company_name'], $data['store']['store_name'], $data['store']['store_code'], $data['robot_session']['session_date']->format('d-m-Y H:i')]));

                    }
                    else{


                        if(isset($files['stock_alert']['file_path']) && file_exists($files['stock_alert']['file_path'])){

                            $email->subject(__('{0} {1} Stock Alerts {2} {3}', [$data['store']['store_code'], $data['robot_session']['session_date']->format('d-m H:i'), $data['company']['company_name'], $data['store']['store_name']]));

                            $email->attachments([
                                $files['stock_alert']['file_name'] => [
                                    'file' => $files['stock_alert']['file_path'],
                                ],
                                'onlyletters2.png' => [
                                    'file' => ROOT.'/webroot/img/onlyletters2.png',
                                    'mimetype' => 'image/png',
                                    'contentId' => 'logo-id'
                                ],
                                'new_zippedi_logo_vertical.png' => [
                                    'file' => ROOT.'/webroot/img/new_zippedi_logo_vertical.png',
                                    'mimetype' => 'image/png',
                                    'contentId' => 'logo-team-id'
                                ]
                            ]);
                        }
                        else{

                            if(isset($files['price_difference']['file_path']) && file_exists($files['price_difference']['file_path'])){


                                if(isset($files['price_difference_inv']['file_path']) && file_exists($files['price_difference_inv']['file_path'])){

                                    $email->attachments([
                                        $files['price_difference_inv']['file_name'] => [
                                            'file' => $files['price_difference_inv']['file_path'],
                                        ],
                                        /*$files['price_difference_xlsx']['file_name'] => [
                                            'file' => $files['price_difference_xlsx']['file_path'],
                                        ],*/
                                        $files['price_difference']['file_name'] => [
                                            'file' => $files['price_difference']['file_path'],
                                        ],
                                        'onlyletters2.png' => [
                                            'file' => ROOT.'/webroot/img/onlyletters2.png',
                                            'mimetype' => 'image/png',
                                            'contentId' => 'logo-id'
                                        ],
                                        'new_zippedi_logo_vertical.png' => [
                                            'file' => ROOT.'/webroot/img/new_zippedi_logo_vertical.png',
                                            'mimetype' => 'image/png',
                                            'contentId' => 'logo-team-id'
                                        ]
                                    ]);
                                }
                                else{
                                    $email->attachments([
                                        $files['price_difference']['file_name'] => [
                                            'file' => $files['price_difference']['file_path'],
                                        ],
                                        /*$files['price_difference_xlsx']['file_name'] => [
                                            'file' => $files['price_difference_xlsx']['file_path'],
                                        ],*/
                                        'onlyletters2.png' => [
                                            'file' => ROOT.'/webroot/img/onlyletters2.png',
                                            'mimetype' => 'image/png',
                                            'contentId' => 'logo-id'
                                        ],
                                        'new_zippedi_logo_vertical.png' => [
                                            'file' => ROOT.'/webroot/img/new_zippedi_logo_vertical.png',
                                            'mimetype' => 'image/png',
                                            'contentId' => 'logo-team-id'
                                        ]
                                    ]);
                                }

                                $email->subject(__('{0} {1} Price Differences {2} {3}', [$data['store']['store_code'], $data['robot_session']['session_date']->format('d-m H:i'), $data['company']['company_name'], $data['store']['store_name']]));
                            }
                            else{
                                continue;
                            }
                        }
                    }
                    

                    $email->from (array('reports@zippedi.com' => 'Zippedi Reports'));
                    $email->to($user['email'], $user['name'].' '.$user['last_name']);

                    $email->send();
                    echo 'Mail enviado a '.$user['email'];
                }
            }
            else{
                echo 'No existen usuarios asignados para enviar reportes';
            }
        }
        else
        {
            echo 'No hay datos para enviar emails';
        }
    }

    function sendUpdatePricesProcessEmail($data = array()){
        if($data != null)
        {
            //$users = $this->Users->getAdminSuscribeUsers('master_process');
            $users[] = [
                'name' => 'Zippedi',
                'last_name' => 'Reports',
                'email' => 'reports@zippedi.com'
            ];

            if(count($users) > 0){
                foreach($users as $user){
                    $email = new Email('default');
                    $email->template('update_prices_done', 'admin_layout');
                    $email->emailFormat('html');
                    $email->subject(__('[{0}] {1} - {2}: Prices load finished / {3}', [$data['store']['store_code'], $data['company']['company_name'], $data['store']['store_name'], date('d-m-Y H:i')]));
                    $email->viewVars(['user' => $user, 'data' => $data]);
                    $email->helpers(['Html', 'Form']);
                    $email->attachments([
                        'new_zippedi_logo_horizontal.png' => [
                            'file' => ROOT.'/webroot/img/new_zippedi_logo_horizontal.png',
                            'mimetype' => 'image/png',
                            'contentId' => 'logo-id'
                        ],
                        'prices-done.png' => [
                            'file' => ROOT.'/webroot/img/emails/prices/prices-done.png',
                            'mimetype' => 'image/png',
                            'contentId' => 'prices-done-id'
                        ]
                    ]);

                    $email->from (array('reports@zippedi.com' => 'Zippedi Reports'));
                    $email->to($user['email'], $user['name'].' '.$user['last_name']);

                    if($email->send())
                    {
                        return true;
                    }
                    else
                    {
                        return false;
                    }
                }
            }
            else{
                return false;
            }
        }
        else
        {
            return false;
        }
    }


    function sendActiveAssortmentEmail($data = array(), $files = array())
    {
        if($data != null)
        {
            $this->loadModel('RobotReports');
            $robot_report = $this->RobotReports->find('all')
                ->where([
                    'RobotReports.report_keyword' => $data['type_report']
                ])
                ->first();

            $users = $this->getAllRetailUsersForStore($robot_report->id, $data['robot_session']['store_id']);
            print_r($users);

            //Iteracion para nuevo modelo
            if(count($users) > 0){
                foreach($users as $user){
                    $email = new Email('default');
                    $email->template('active_assortment', 'modern');
                    $email->emailFormat('html');
                    $email->subject(__('{0} {1} Active Assortment {2} {3}', [$data['store']['store_code'], $data['robot_session']['session_date']->format('d-m H:i'), $data['company']['company_name'], $data['store']['store_name']]));


                    $email->viewVars(['user' => $user, 'data' => $data, 'files' => $files]);
                    $email->helpers(['Html', 'Form']);
                    $email->attachments([
                        'onlyletters2.png' => [
                            'file' => ROOT.'/webroot/img/onlyletters2.png',
                            'mimetype' => 'image/png',
                            'contentId' => 'logo-id'
                        ],
                        'new_zippedi_logo_vertical.png' => [
                            'file' => ROOT.'/webroot/img/new_zippedi_logo_vertical.png',
                            'mimetype' => 'image/png',
                            'contentId' => 'logo-team-id'
                        ],
                        'excel.png' => [
                            'file' => ROOT.'/webroot/img/icons/excel.png',
                            'mimetype' => 'image/png',
                            'contentId' => 'excel-id'
                        ],
                        'pdf.png' => [
                            'file' => ROOT.'/webroot/img/icons/pdf.png',
                            'mimetype' => 'image/png',
                            'contentId' => 'pdf-id'
                        ],
                    ]);

                    $email->from (array('reports@zippedi.com' => 'Zippedi Reports'));
                    $email->to($user['email'], $user['name'].' '.$user['last_name']);

                    $email->send();
                    echo 'Mail enviado a '.$user['email'];
                }
            }
        }
    }

    function sendNegativeAssortmentEmail($data = array(), $files = array())
    {
        if($data != null)
        {
            
            //$users = $this->getAllRetailUsersForStore($data['robot_session']['store_id']);
            /*$users= [
                'zippedi' => [
                    [
                        'name' => 'Hugo',
                        'last_name' => 'Inda',
                        'email' => 'hugo@zippedi.com'
                    ],
                    [
                        'name' => 'Juan Pablo',
                        'last_name' => 'Valencia',
                        'email' => 'juanpablo@zippedi.com'
                    ],
                    /*[
                        'name' => 'Jose Manuel',
                        'last_name' => 'Díaz',
                        'email' => 'josemanuel@zippedi.com'
                    ],
                    [
                        'name' => 'Rodrigo',
                        'last_name' => 'Gonzalez',
                        'email' => 'rodrigo@zippedi.com'
                    ],
                    [
                        'name' => 'Álvaro',
                        'last_name' => 'Soto',
                        'email' => 'alvaro@zippedi.com'
                    ],
                    [
                        'name' => 'Ivan',
                        'last_name' => 'Soto',
                        'email' => 'ivan@zippedi.com'
                    ],
                ],
                'J502' => [
                    /*[
                        'name' => 'Fernando',
                        'last_name' => 'Gonzalez',
                        'email' => 'fernando.gonzalezarismendi@jumbo.cl'
                    ],
                    [
                        'name' => 'Reinaldo',
                        'last_name' => 'Lira',
                        'email' => 'reinaldo.lira@jumbo.cl'
                    ],
                    [
                        'name' => 'Ana',
                        'last_name' => 'Espinoza',
                        'email' => 'ana.espinoza@jumbo.cl'
                    ],
                    [
                        'name' => 'Victor',
                        'last_name' => 'Contreras',
                        'email' => 'victor.contrerasvasquez@jumbo.cl'
                    ],
                    [
                        'name' => 'Rodrigo',
                        'last_name' => 'Campillay',
                        'email' => 'rodrigo.campillay@jumbo.cl'
                    ],
                    [
                        'name' => 'Axel',
                        'last_name' => 'Carine',
                        'email' => 'axel.carinesepulveda@jumbo.cl'
                    ],
                    [
                        'name' => 'Cristian',
                        'last_name' => 'Rojas',
                        'email' => 'cristian.rojasordenes@jumbo.cl'
                    ],
                    [
                        'name' => 'Administracion',
                        'last_name' => 'J502',
                        'email' => 'J502_Administracion@cencosud.cl'
                    ]
                ],
                'J514' => [
                    /*[
                        'name' => 'Marcelo',
                        'last_name' => 'Barrientos',
                        'email' => 'marcelo.barrientosvergara@jumbo.cl'
                    ],
                    [
                        'name' => 'Carlos',
                        'last_name' => 'Hiriart',
                        'email' => 'carlos.hiriart@jumbo.cl'
                    ],
                    [
                        'name' => 'Marco',
                        'last_name' => 'Vistoso',
                        'email' => 'marco.vistoso@jumbo.cl'
                    ],
                    [
                        'name' => 'Juan',
                        'last_name' => 'Gomez',
                        'email' => 'juan.gomezninodezepeda@jumbo.cl'
                    ],
                    [
                        'name' => 'Luis',
                        'last_name' => 'Hernandez',
                        'email' => 'luis.hernandezlucero@jumbo.cl'
                    ],
                    [
                        'name' => 'Francisco',
                        'last_name' => 'Murillo',
                        'email' => 'francisco.murillo@jumbo.cl'
                    ],
                    [
                        'name' => 'Cristian',
                        'last_name' => 'Conoepan',
                        'email' => 'cristian.conoepanhuenchucoy@jumbo.cl'
                    ],
                    [
                        'name' => 'Administracion',
                        'last_name' => 'J514',
                        'email' => 'J514_Administracion@cencosud.cl'
                    ]
                ],
                'J510' => [
                    /*[
                        'name' => 'Alfredo',
                        'last_name' => 'Ruiz',
                        'email' => 'alfredo.ruiz@jumbo.cl'
                    ],
                    [
                        'name' => 'Jose',
                        'last_name' => 'Alvarado',
                        'email' => 'jose.alvarado@jumbo.cl'
                    ],
                    [
                        'name' => 'Luis',
                        'last_name' => 'Correa',
                        'email' => 'luis.correa@jumbo.cl'
                    ],
                    [
                        'name' => 'Juan Pablo',
                        'last_name' => 'Rodriguez',
                        'email' => 'juanpablo.rodriguezbadilla@jumbo.cl'
                    ],
                    [
                        'name' => 'Javier',
                        'last_name' => 'Gonzalez',
                        'email' => 'javier.gonzalezcastro@jumbo.cl'
                    ],
                    [
                        'name' => 'Administracion',
                        'last_name' => 'J510',
                        'email' => 'J510_Administracion@cencosud.cl'
                    ]
                ],
                'J501' => [
                    /*[
                        'name' => 'Claudio',
                        'last_name' => 'Rosas',
                        'email' => 'claudio.rosascavieres@jumbo.cl'
                    ],
                    [
                        'name' => 'Oscar',
                        'last_name' => 'Martinez',
                        'email' => 'oscar.martinezveliz@jumbo.cl'
                    ],
                    [
                        'name' => 'Luis',
                        'last_name' => 'Velasquez',
                        'email' => 'luis.velasquez@jumbo.cl'
                    ],
                    [
                        'name' => 'Pablo',
                        'last_name' => 'Cancino',
                        'email' => 'Pablo.cancino@jumbo.cl'
                    ],
                    [
                        'name' => 'Rodrigo',
                        'last_name' => 'Arriagada',
                        'email' => 'rodrigo.arriagadafaundez@jumbo.cl'
                    ],
                    [
                        'name' => 'Juan',
                        'last_name' => 'Campos',
                        'email' => 'juan.campos.lopez@cencosud.cl'
                    ],
                    [
                        'name' => 'Andres',
                        'last_name' => 'Navarro',
                        'email' => 'andres.navarro@jumbo.cl'
                    ],
                    [
                        'name' => 'Mariana',
                        'last_name' => 'Vargas',
                        'email' => 'mariana.vargas.reyes@jumbo.cl'
                    ],
                    [
                        'name' => 'Marisol',
                        'last_name' => 'Llanquileo',
                        'email' => 'marisol.llanquileo@jumbo.cl'
                    ],
                    [
                        'name' => 'Sandra',
                        'last_name' => 'Lunatiznado',
                        'email' => 'sandra.lunatiznado@jumbo.cl'
                    ],
                    [
                        'name' => 'Administracion',
                        'last_name' => 'J501',
                        'email' => 'J501_Administracion@cencosud.cl'
                    ]
                ],
                'J511' => [
                    /*[
                        'name' => 'Carlos',
                        'last_name' => 'Ruiz',
                        'email' => 'Carlos.ruizgeywitz@jumbo.cl'
                    ],
                    [
                        'name' => 'Sandra',
                        'last_name' => 'Cavada',
                        'email' => 'sandra.cavada@jumbo.cl'
                    ],
                    [
                        'name' => 'Luis',
                        'last_name' => 'Morales',
                        'email' => 'luis.moralesramos@cencosud.cl'
                    ],
                    [
                        'name' => 'Luis',
                        'last_name' => 'Parra',
                        'email' => 'luis.parra@jumbo.cl'
                    ],
                    [
                        'name' => 'Cristian',
                        'last_name' => 'Ayala',
                        'email' => 'cristian.ayala@jumbo.cl'
                    ],
                    [
                        'name' => 'Alex',
                        'last_name' => 'Salgado',
                        'email' => 'alex.salgado@jumbo.cl'
                    ],
                    [
                        'name' => 'Javier',
                        'last_name' => 'Plaza',
                        'email' => 'javier.plaza@jumbo.cl'
                    ],
                    [
                        'name' => 'Miriam',
                        'last_name' => 'Salvo',
                        'email' => 'miriam.salvo@jumbo.cl'
                    ],
                    [
                        'name' => 'Felipe',
                        'last_name' => 'Martinez',
                        'email' => 'felipe.martinezleiva@jumbo.cl'
                    ],
                    [
                        'name' => 'Danitza',
                        'last_name' => 'Gomez',
                        'email' => 'danitza.gomezzamorano@jumbo.cl'
                    ],
                    [
                        'name' => 'Raul',
                        'last_name' => 'Álvarez',
                        'email' => 'raul.alvarezcano@jumbo.cl'
                    ],
                ],
                'J512' => [
                    /*[
                        'name' => 'Jose',
                        'last_name' => 'Garrido',
                        'email' => 'jose.garrido@jumbo.cl'
                    ],
                    [
                        'name' => 'Jennifer',
                        'last_name' => 'Venegas',
                        'email' => 'jennifer.venegas@jumbo.cl'
                    ],
                    [
                        'name' => 'Jorge',
                        'last_name' => 'Contreras',
                        'email' => 'jorge.contrerasespinoza@jumbo.cl'
                    ],
                    [
                        'name' => 'Alexis',
                        'last_name' => 'Arriagada',
                        'email' => 'alexis.arriagada@jumbo.cl'
                    ],
                    [
                        'name' => 'Naxaly',
                        'last_name' => 'Espinoza',
                        'email' => 'naxily.espinozapinilla@jumbo.cl'
                    ],
                    [
                        'name' => 'Julio',
                        'last_name' => 'Perez',
                        'email' => 'julio.perezsepulveda@jumbo.cl'
                    ],
                    [
                        'name' => 'Daniel',
                        'last_name' => 'Aviles',
                        'email' => 'daniel.aviles@jumbo.cl'
                    ],
                    [
                        'name' => 'Administracion',
                        'last_name' => 'J512',
                        'email' => 'J512_Administracion@cencosud.cl'
                    ]
                ],
                'J519' => [
                    /*[
                        'name' => 'Eugenio',
                        'last_name' => 'Ubilla',
                        'email' => 'eugenio.ubilla@jumbo.cl'
                    ],
                    [
                        'name' => 'Juan Ignacio',
                        'last_name' => 'Caceres',
                        'email' => 'juanignacio.caceres@jumbo.cl'
                    ],
                    [
                        'name' => 'Rodrigo',
                        'last_name' => 'Cortez',
                        'email' => 'rodrigo.cortez@jumbo.cl'
                    ],
                    [
                        'name' => 'Rodrigo',
                        'last_name' => 'Trujillo',
                        'email' => 'rodrigo.trujillovilla@jumbo.cl'
                    ],
                    [
                        'name' => 'David',
                        'last_name' => 'Sepulveda',
                        'email' => 'david.sepulveda@jumbo.cl'
                    ],
                    [
                        'name' => 'Juan Ignacio',
                        'last_name' => 'San Martín',
                        'email' => 'juanignacio.sanmartinsepulveda@jumbo.cl'
                    ],
                ],
                'jumbo' => [
                    /*[
                        'name' => 'Pablo',
                        'last_name' => 'Pinela',
                        'email' => 'pablo.pinela@cencosud.cl'
                    ],
                    [
                        'name' => 'Fabian',
                        'last_name' => 'Leiva',
                        'email' => 'fabian.leiva@cencosud.cl'
                    ],
                    [
                        'name' => 'Edgardo',
                        'last_name' => 'Gutierrez',
                        'email' => 'edgardo.gutierrezs@cencosud.cl'
                    ],
                    [
                        'name' => 'Ricardo',
                        'last_name' => 'Otaegui',
                        'email' => 'ricardo.otaegui@jumbo.cl'
                    ],
                ],
            ];*/

            $users = [
                [
                    'name' => 'Hugo',
                    'last_name' => 'Inda',
                    'email' => 'hugo@zippedi.com'
                ],
                [
                    'name' => 'Juan Pablo',
                    'last_name' => 'Valencia',
                    'email' => 'juanpablo@zippedi.com'
                ]
            ];

            if(count($users) > 0){
                foreach($users as $user){
                    $email = new Email('default');
                    $email->template('negative_assortment', 'modern');
                    $email->emailFormat('html');
                    $email->subject(__('{0} {1} Negative Assortment {2} {3}', [$data['store']['store_code'], $data['robot_session']['session_date']->format('d-m H:i'), $data['company']['company_name'], $data['store']['store_name']]));


                    $email->viewVars(['user' => $user, 'data' => $data, 'files' => $files]);
                    $email->helpers(['Html', 'Form']);
                    $email->attachments([
                        'onlyletters2.png' => [
                            'file' => ROOT.'/webroot/img/onlyletters2.png',
                            'mimetype' => 'image/png',
                            'contentId' => 'logo-id'
                        ],
                        'new_zippedi_logo_vertical.png' => [
                            'file' => ROOT.'/webroot/img/new_zippedi_logo_vertical.png',
                            'mimetype' => 'image/png',
                            'contentId' => 'logo-team-id'
                        ],
                        'excel.png' => [
                            'file' => ROOT.'/webroot/img/icons/excel.png',
                            'mimetype' => 'image/png',
                            'contentId' => 'excel-id'
                        ],
                        'pdf.png' => [
                            'file' => ROOT.'/webroot/img/icons/pdf.png',
                            'mimetype' => 'image/png',
                            'contentId' => 'pdf-id'
                        ],
                    ]);

                    $email->from (array('reports@zippedi.com' => 'Zippedi Reports'));
                    $email->to($user['email'], $user['name'].' '.$user['last_name']);

                    $email->send();
                    echo 'Mail enviado a '.$user['email'];
                }
            }

            /*if(isset($users[$data['store']['store_code']]) /*&& count($users[$data['store']['store_code']]) > 0){
                foreach($users[$data['store']['store_code']] as $user){
                    $email = new Email('default');
                    $email->template('negative_assortment', 'modern');
                    $email->emailFormat('html');
                    $email->subject(__('{0} {1} Negative Assortment {2} {3}', [$data['store']['store_code'], $data['robot_session']['session_date']->format('d-m H:i'), $data['company']['company_name'], $data['store']['store_name']]));


                    $email->viewVars(['user' => $user, 'data' => $data, 'files' => $files]);
                    $email->helpers(['Html', 'Form']);
                    $email->attachments([
                        'onlyletters2.png' => [
                            'file' => ROOT.'/webroot/img/onlyletters2.png',
                            'mimetype' => 'image/png',
                            'contentId' => 'logo-id'
                        ],
                        'new_zippedi_logo_vertical.png' => [
                            'file' => ROOT.'/webroot/img/new_zippedi_logo_vertical.png',
                            'mimetype' => 'image/png',
                            'contentId' => 'logo-team-id'
                        ],
                        'excel.png' => [
                            'file' => ROOT.'/webroot/img/icons/excel.png',
                            'mimetype' => 'image/png',
                            'contentId' => 'excel-id'
                        ],
                        'pdf.png' => [
                            'file' => ROOT.'/webroot/img/icons/pdf.png',
                            'mimetype' => 'image/png',
                            'contentId' => 'pdf-id'
                        ],
                    ]);

                    $email->from (array('reports@zippedi.com' => 'Zippedi Reports'));
                    $email->to($user['email'], $user['name'].' '.$user['last_name']);

                    $email->send();
                    echo 'Mail enviado a '.$user['email'];
                }
            }

            if(isset($users[$data['company']['company_keyword']]) && count($users[$data['company']['company_keyword']]) > 0){
                foreach($users[$data['company']['company_keyword']] as $user){
                    $email = new Email('default');
                    $email->template('negative_assortment', 'modern');
                    $email->emailFormat('html');
                    $email->subject(__('{0} {1} Negative Assortment {2} {3}', [$data['store']['store_code'], $data['robot_session']['session_date']->format('d-m H:i'), $data['company']['company_name'], $data['store']['store_name']]));


                    $email->viewVars(['user' => $user, 'data' => $data, 'files' => $files]);
                    $email->helpers(['Html', 'Form']);
                    $email->attachments([
                        'onlyletters2.png' => [
                            'file' => ROOT.'/webroot/img/onlyletters2.png',
                            'mimetype' => 'image/png',
                            'contentId' => 'logo-id'
                        ],
                        'new_zippedi_logo_vertical.png' => [
                            'file' => ROOT.'/webroot/img/new_zippedi_logo_vertical.png',
                            'mimetype' => 'image/png',
                            'contentId' => 'logo-team-id'
                        ],
                        'excel.png' => [
                            'file' => ROOT.'/webroot/img/icons/excel.png',
                            'mimetype' => 'image/png',
                            'contentId' => 'excel-id'
                        ],
                        'pdf.png' => [
                            'file' => ROOT.'/webroot/img/icons/pdf.png',
                            'mimetype' => 'image/png',
                            'contentId' => 'pdf-id'
                        ],
                    ]);

                    $email->from (array('reports@zippedi.com' => 'Zippedi Reports'));
                    $email->to($user['email'], $user['name'].' '.$user['last_name']);

                    $email->send();
                    echo 'Mail enviado a '.$user['email'];
                }
            }*/
        }
    }

    function sendBlockedAssortmentEmail($data = array(), $files = array())
    {
        if($data != null)
        {
            //$this->Users->getAdminSuscribeUsers;
            $users= [
                'zippedi' => [
                    [
                        'name' => 'Hugo',
                        'last_name' => 'Inda',
                        'email' => 'hugo@zippedi.com'
                    ],
                    /*[
                        'name' => 'Juan Pablo',
                        'last_name' => 'Valencia',
                        'email' => 'juanpablo@zippedi.com'
                    ],
                    [
                        'name' => 'Jose Manuel',
                        'last_name' => 'Díaz',
                        'email' => 'josemanuel@zippedi.com'
                    ],
                    [
                        'name' => 'Rodrigo',
                        'last_name' => 'Gonzalez',
                        'email' => 'rodrigo@zippedi.com'
                    ],
                    [
                        'name' => 'Álvaro',
                        'last_name' => 'Soto',
                        'email' => 'alvaro@zippedi.com'
                    ],
                    [
                        'name' => 'Ivan',
                        'last_name' => 'Soto',
                        'email' => 'ivan@zippedi.com'
                    ],*/
                ],
                'J502' => [
                    /*[
                        'name' => 'Fernando',
                        'last_name' => 'Gonzalez',
                        'email' => 'fernando.gonzalezarismendi@jumbo.cl'
                    ],
                    [
                        'name' => 'Reinaldo',
                        'last_name' => 'Lira',
                        'email' => 'reinaldo.lira@jumbo.cl'
                    ],
                    [
                        'name' => 'Ana',
                        'last_name' => 'Espinoza',
                        'email' => 'ana.espinoza@jumbo.cl'
                    ],
                    [
                        'name' => 'Victor',
                        'last_name' => 'Contreras',
                        'email' => 'victor.contrerasvasquez@jumbo.cl'
                    ],
                    [
                        'name' => 'Rodrigo',
                        'last_name' => 'Campillay',
                        'email' => 'rodrigo.campillay@jumbo.cl'
                    ],
                    [
                        'name' => 'Axel',
                        'last_name' => 'Carine',
                        'email' => 'axel.carinesepulveda@jumbo.cl'
                    ],
                    [
                        'name' => 'Cristian',
                        'last_name' => 'Rojas',
                        'email' => 'cristian.rojasordenes@jumbo.cl'
                    ],
                    [
                        'name' => 'Administracion',
                        'last_name' => 'J502',
                        'email' => 'J502_Administracion@cencosud.cl'
                    ]*/
                ],
                'J514' => [
                    /*[
                        'name' => 'Marcelo',
                        'last_name' => 'Barrientos',
                        'email' => 'marcelo.barrientosvergara@jumbo.cl'
                    ],
                    [
                        'name' => 'Carlos',
                        'last_name' => 'Hiriart',
                        'email' => 'carlos.hiriart@jumbo.cl'
                    ],
                    [
                        'name' => 'Marco',
                        'last_name' => 'Vistoso',
                        'email' => 'marco.vistoso@jumbo.cl'
                    ],
                    [
                        'name' => 'Juan',
                        'last_name' => 'Gomez',
                        'email' => 'juan.gomezninodezepeda@jumbo.cl'
                    ],
                    [
                        'name' => 'Luis',
                        'last_name' => 'Hernandez',
                        'email' => 'luis.hernandezlucero@jumbo.cl'
                    ],
                    [
                        'name' => 'Francisco',
                        'last_name' => 'Murillo',
                        'email' => 'francisco.murillo@jumbo.cl'
                    ],
                    [
                        'name' => 'Cristian',
                        'last_name' => 'Conoepan',
                        'email' => 'cristian.conoepanhuenchucoy@jumbo.cl'
                    ],
                    [
                        'name' => 'Administracion',
                        'last_name' => 'J514',
                        'email' => 'J514_Administracion@cencosud.cl'
                    ]*/
                ],
                'J510' => [
                    /*[
                        'name' => 'Alfredo',
                        'last_name' => 'Ruiz',
                        'email' => 'alfredo.ruiz@jumbo.cl'
                    ],
                    [
                        'name' => 'Jose',
                        'last_name' => 'Alvarado',
                        'email' => 'jose.alvarado@jumbo.cl'
                    ],
                    [
                        'name' => 'Luis',
                        'last_name' => 'Correa',
                        'email' => 'luis.correa@jumbo.cl'
                    ],
                    [
                        'name' => 'Juan Pablo',
                        'last_name' => 'Rodriguez',
                        'email' => 'juanpablo.rodriguezbadilla@jumbo.cl'
                    ],
                    [
                        'name' => 'Javier',
                        'last_name' => 'Gonzalez',
                        'email' => 'javier.gonzalezcastro@jumbo.cl'
                    ],
                    [
                        'name' => 'Administracion',
                        'last_name' => 'J510',
                        'email' => 'J510_Administracion@cencosud.cl'
                    ]*/
                ],
                'J501' => [
                    /*[
                        'name' => 'Claudio',
                        'last_name' => 'Rosas',
                        'email' => 'claudio.rosascavieres@jumbo.cl'
                    ],
                    [
                        'name' => 'Oscar',
                        'last_name' => 'Martinez',
                        'email' => 'oscar.martinezveliz@jumbo.cl'
                    ],
                    [
                        'name' => 'Luis',
                        'last_name' => 'Velasquez',
                        'email' => 'luis.velasquez@jumbo.cl'
                    ],
                    [
                        'name' => 'Pablo',
                        'last_name' => 'Cancino',
                        'email' => 'Pablo.cancino@jumbo.cl'
                    ],
                    [
                        'name' => 'Rodrigo',
                        'last_name' => 'Arriagada',
                        'email' => 'rodrigo.arriagadafaundez@jumbo.cl'
                    ],
                    [
                        'name' => 'Juan',
                        'last_name' => 'Campos',
                        'email' => 'juan.campos.lopez@cencosud.cl'
                    ],
                    [
                        'name' => 'Andres',
                        'last_name' => 'Navarro',
                        'email' => 'andres.navarro@jumbo.cl'
                    ],
                    [
                        'name' => 'Mariana',
                        'last_name' => 'Vargas',
                        'email' => 'mariana.vargas.reyes@jumbo.cl'
                    ],
                    [
                        'name' => 'Marisol',
                        'last_name' => 'Llanquileo',
                        'email' => 'marisol.llanquileo@jumbo.cl'
                    ],
                    [
                        'name' => 'Sandra',
                        'last_name' => 'Lunatiznado',
                        'email' => 'sandra.lunatiznado@jumbo.cl'
                    ],
                    [
                        'name' => 'Administracion',
                        'last_name' => 'J501',
                        'email' => 'J501_Administracion@cencosud.cl'
                    ]*/
                ],
                'J511' => [
                    /*[
                        'name' => 'Carlos',
                        'last_name' => 'Ruiz',
                        'email' => 'Carlos.ruizgeywitz@jumbo.cl'
                    ],
                    [
                        'name' => 'Sandra',
                        'last_name' => 'Cavada',
                        'email' => 'sandra.cavada@jumbo.cl'
                    ],
                    [
                        'name' => 'Luis',
                        'last_name' => 'Morales',
                        'email' => 'luis.moralesramos@cencosud.cl'
                    ],
                    [
                        'name' => 'Luis',
                        'last_name' => 'Parra',
                        'email' => 'luis.parra@jumbo.cl'
                    ],
                    [
                        'name' => 'Cristian',
                        'last_name' => 'Ayala',
                        'email' => 'cristian.ayala@jumbo.cl'
                    ],
                    [
                        'name' => 'Alex',
                        'last_name' => 'Salgado',
                        'email' => 'alex.salgado@jumbo.cl'
                    ],
                    [
                        'name' => 'Javier',
                        'last_name' => 'Plaza',
                        'email' => 'javier.plaza@jumbo.cl'
                    ],
                    [
                        'name' => 'Miriam',
                        'last_name' => 'Salvo',
                        'email' => 'miriam.salvo@jumbo.cl'
                    ],
                    [
                        'name' => 'Felipe',
                        'last_name' => 'Martinez',
                        'email' => 'felipe.martinezleiva@jumbo.cl'
                    ],
                    [
                        'name' => 'Danitza',
                        'last_name' => 'Gomez',
                        'email' => 'danitza.gomezzamorano@jumbo.cl'
                    ],
                    [
                        'name' => 'Raul',
                        'last_name' => 'Álvarez',
                        'email' => 'raul.alvarezcano@jumbo.cl'
                    ],*/
                ],
                'J512' => [
                    /*[
                        'name' => 'Jose',
                        'last_name' => 'Garrido',
                        'email' => 'jose.garrido@jumbo.cl'
                    ],
                    [
                        'name' => 'Jennifer',
                        'last_name' => 'Venegas',
                        'email' => 'jennifer.venegas@jumbo.cl'
                    ],
                    [
                        'name' => 'Jorge',
                        'last_name' => 'Contreras',
                        'email' => 'jorge.contrerasespinoza@jumbo.cl'
                    ],
                    [
                        'name' => 'Alexis',
                        'last_name' => 'Arriagada',
                        'email' => 'alexis.arriagada@jumbo.cl'
                    ],
                    [
                        'name' => 'Naxaly',
                        'last_name' => 'Espinoza',
                        'email' => 'naxily.espinozapinilla@jumbo.cl'
                    ],
                    [
                        'name' => 'Julio',
                        'last_name' => 'Perez',
                        'email' => 'julio.perezsepulveda@jumbo.cl'
                    ],
                    [
                        'name' => 'Daniel',
                        'last_name' => 'Aviles',
                        'email' => 'daniel.aviles@jumbo.cl'
                    ],
                    [
                        'name' => 'Administracion',
                        'last_name' => 'J512',
                        'email' => 'J512_Administracion@cencosud.cl'
                    ]*/
                ],
                'J519' => [
                    /*[
                        'name' => 'Eugenio',
                        'last_name' => 'Ubilla',
                        'email' => 'eugenio.ubilla@jumbo.cl'
                    ],
                    [
                        'name' => 'Juan Ignacio',
                        'last_name' => 'Caceres',
                        'email' => 'juanignacio.caceres@jumbo.cl'
                    ],
                    [
                        'name' => 'Rodrigo',
                        'last_name' => 'Cortez',
                        'email' => 'rodrigo.cortez@jumbo.cl'
                    ],
                    [
                        'name' => 'Rodrigo',
                        'last_name' => 'Trujillo',
                        'email' => 'rodrigo.trujillovilla@jumbo.cl'
                    ],
                    [
                        'name' => 'David',
                        'last_name' => 'Sepulveda',
                        'email' => 'david.sepulveda@jumbo.cl'
                    ],
                    [
                        'name' => 'Juan Ignacio',
                        'last_name' => 'San Martín',
                        'email' => 'juanignacio.sanmartinsepulveda@jumbo.cl'
                    ],*/
                ],
                'jumbo' => [
                    /*[
                        'name' => 'Pablo',
                        'last_name' => 'Pinela',
                        'email' => 'pablo.pinela@cencosud.cl'
                    ],
                    [
                        'name' => 'Fabian',
                        'last_name' => 'Leiva',
                        'email' => 'fabian.leiva@cencosud.cl'
                    ],
                    [
                        'name' => 'Edgardo',
                        'last_name' => 'Gutierrez',
                        'email' => 'edgardo.gutierrezs@cencosud.cl'
                    ],
                    [
                        'name' => 'Ricardo',
                        'last_name' => 'Otaegui',
                        'email' => 'ricardo.otaegui@jumbo.cl'
                    ],*/
                ],
            ];



            if(count($users['zippedi']) > 0){
                foreach($users['zippedi'] as $user){
                    $email = new Email('default');
                    $email->template('blocked_assortment', 'modern');
                    $email->emailFormat('html');
                    $email->subject(__('{0} {1} Blocked Assortment {2} {3}', [$data['store']['store_code'], $data['robot_session']['session_date']->format('d-m H:i'), $data['company']['company_name'], $data['store']['store_name']]));


                    $email->viewVars(['user' => $user, 'data' => $data, 'files' => $files]);
                    $email->helpers(['Html', 'Form']);
                    $email->attachments([
                        'onlyletters2.png' => [
                            'file' => ROOT.'/webroot/img/onlyletters2.png',
                            'mimetype' => 'image/png',
                            'contentId' => 'logo-id'
                        ],
                        'new_zippedi_logo_vertical.png' => [
                            'file' => ROOT.'/webroot/img/new_zippedi_logo_vertical.png',
                            'mimetype' => 'image/png',
                            'contentId' => 'logo-team-id'
                        ],
                        'excel.png' => [
                            'file' => ROOT.'/webroot/img/icons/excel.png',
                            'mimetype' => 'image/png',
                            'contentId' => 'excel-id'
                        ],
                        'pdf.png' => [
                            'file' => ROOT.'/webroot/img/icons/pdf.png',
                            'mimetype' => 'image/png',
                            'contentId' => 'pdf-id'
                        ],
                    ]);

                    $email->from (array('reports@zippedi.com' => 'Zippedi Reports'));
                    $email->to($user['email'], $user['name'].' '.$user['last_name']);

                    $email->send();
                    echo 'Mail enviado a '.$user['email'];
                }
            }

            if(isset($users[$data['store']['store_code']]) /*&& count($users[$data['store']['store_code']]) > 0*/){
                foreach($users[$data['store']['store_code']] as $user){
                    $email = new Email('default');
                    $email->template('blocked_assortment', 'modern');
                    $email->emailFormat('html');
                    $email->subject(__('{0} {1} Blocked Assortment {2} {3}', [$data['store']['store_code'], $data['robot_session']['session_date']->format('d-m H:i'), $data['company']['company_name'], $data['store']['store_name']]));


                    $email->viewVars(['user' => $user, 'data' => $data, 'files' => $files]);
                    $email->helpers(['Html', 'Form']);
                    $email->attachments([
                        'onlyletters2.png' => [
                            'file' => ROOT.'/webroot/img/onlyletters2.png',
                            'mimetype' => 'image/png',
                            'contentId' => 'logo-id'
                        ],
                        'new_zippedi_logo_vertical.png' => [
                            'file' => ROOT.'/webroot/img/new_zippedi_logo_vertical.png',
                            'mimetype' => 'image/png',
                            'contentId' => 'logo-team-id'
                        ],
                        'excel.png' => [
                            'file' => ROOT.'/webroot/img/icons/excel.png',
                            'mimetype' => 'image/png',
                            'contentId' => 'excel-id'
                        ],
                        'pdf.png' => [
                            'file' => ROOT.'/webroot/img/icons/pdf.png',
                            'mimetype' => 'image/png',
                            'contentId' => 'pdf-id'
                        ],
                    ]);

                    $email->from (array('reports@zippedi.com' => 'Zippedi Reports'));
                    $email->to($user['email'], $user['name'].' '.$user['last_name']);

                    $email->send();
                    echo 'Mail enviado a '.$user['email'];
                }
            }

            if(isset($users[$data['company']['company_keyword']]) && count($users[$data['company']['company_keyword']]) > 0){
                foreach($users[$data['company']['company_keyword']] as $user){
                    $email = new Email('default');
                    $email->template('blocked_assortment', 'modern');
                    $email->emailFormat('html');
                    $email->subject(__('{0} {1} Blocked Assortment {2} {3}', [$data['store']['store_code'], $data['robot_session']['session_date']->format('d-m H:i'), $data['company']['company_name'], $data['store']['store_name']]));


                    $email->viewVars(['user' => $user, 'data' => $data, 'files' => $files]);
                    $email->helpers(['Html', 'Form']);
                    $email->attachments([
                        'onlyletters2.png' => [
                            'file' => ROOT.'/webroot/img/onlyletters2.png',
                            'mimetype' => 'image/png',
                            'contentId' => 'logo-id'
                        ],
                        'new_zippedi_logo_vertical.png' => [
                            'file' => ROOT.'/webroot/img/new_zippedi_logo_vertical.png',
                            'mimetype' => 'image/png',
                            'contentId' => 'logo-team-id'
                        ],
                        'excel.png' => [
                            'file' => ROOT.'/webroot/img/icons/excel.png',
                            'mimetype' => 'image/png',
                            'contentId' => 'excel-id'
                        ],
                        'pdf.png' => [
                            'file' => ROOT.'/webroot/img/icons/pdf.png',
                            'mimetype' => 'image/png',
                            'contentId' => 'pdf-id'
                        ],
                    ]);

                    $email->from (array('reports@zippedi.com' => 'Zippedi Reports'));
                    $email->to($user['email'], $user['name'].' '.$user['last_name']);

                    $email->send();
                    echo 'Mail enviado a '.$user['email'];
                }
            }
        }
    }

    function sendInvitation($invitation = null)
    {
        if($invitation != null)
        {
            $email = new Email('default');
            $email->template('invitation', 'modern');
            $email->emailFormat('html');
            $email->subject(__('{0}, you have been invited to my.zippedi.com', $invitation->name).'!');
            $email->viewVars(['invitation' => $invitation]);
            $email->helpers(['Html', 'Form']);
            $email->attachments([
                'onlyletters2.png' => [
                    'file' => ROOT.'/webroot/img/onlyletters2.png',
                    'mimetype' => 'image/png',
                    'contentId' => 'logo-id'
                ],
                'new_zippedi_logo_vertical.png' => [
                    'file' => ROOT.'/webroot/img/new_zippedi_logo_vertical.png',
                    'mimetype' => 'image/png',
                    'contentId' => 'logo-team-id'
                ]
            ]);

            $email->from (array('reports@zippedi.com' => 'Zippedi'));
            $email->to($invitation->email, $invitation->name.' '.$invitation->last_name);
            if($email->send())
            {
                return true;
            }
            else
            {
                return false;
            }
        }
    }


    function sendSubmittedInvitation($invitation = null, $payment = null)
    {
        if($invitation != null && $payment != null)
        {
            $admin_users = $this->Users->find()
                ->where([
                    'Users.admin_user' => 1
                ])
                ->select([
                    'Users.id'
                ])
                ->toArray();

            if(count($admin_users) > 0)
            {
                foreach($admin_users as $user)
                {
                    $user_data = $this->Users->basicInformation($user->id);

                    $email = new Email('default');
                    $email->template('submitted_invitation', 'public_layout');
                    $email->emailFormat('html');
                    $email->subject(__('An invitation confirmation has been received').'!');
                    $email->viewVars(['invitation' => $invitation]);
                    $email->helpers(['Html', 'Form']);

                    if($payment->document_path != '')
                    {
                        $file_array = explode('files/payments/documents/', $payment->document_path);

                        $email->attachments([
                            'logo_smov_new.png' => [
                                'file' => 'img/logo_smov_new.png',
                                'mimetype' => 'image/png',
                                'contentId' => 'logo-id'
                            ],
                            'send-money.png' => [
                                'file' => 'img/emails/send-money.png',
                                'mimetype' => 'image/png',
                                'contentId' => 'send-money-id'
                            ],
                            $file_array[1] => [
                                 'file' => $payment->document_path,
                            ]
                        ]);
                    }
                    else
                    {
                        $email->attachments([
                            'logo_smov_new.png' => [
                                'file' => 'img/logo_smov_new.png',
                                'mimetype' => 'image/png',
                                'contentId' => 'logo-id'
                            ],
                            'send-money.png' => [
                                'file' => 'img/emails/send-money.png',
                                'mimetype' => 'image/png',
                                'contentId' => 'send-money-id'
                            ]
                        ]);
                    }
                    

                    $email->from (array('contact@atlocals.com' => 'AtLocals'));
                    $email->to($user_data['UserInformation']['email'], ($user_data['UserInformation']['display_name'] != '') ? $user_data['UserInformation']['display_name'] : '');
                    if($email->send())
                    {
                        return true;
                    }
                    else
                    {
                        return false;
                    }
                }
            }
            else
            {
                return true;
            }
        }
    }

    function sendConfirmedPayment($reservation = null, $payment = null)
    {
        if($reservation != null && $payment != null)
        {
            $user_data = null;

            if($reservation->user != null)
            {
                $user_data = $this->Users->basicInformation($reservation->user->id);
            }

            $host_data = $this->Users->basicInformation($reservation->event->user_id);

            $email = new Email('default');
            //$email->template('confirmed_payment', 'public_layout');
            $email->template('confirmed_payment', 'info_layout');
            $email->emailFormat('html');
            $email->subject(__('{0} Your payment has been confirmed', ($user_data != null) ? $user_data['UserInformation']['name'] : $reservation->invitation->name.'!'));
            $email->viewVars(['payment' => $payment, 'reservation' => $reservation, 'user_data' => $user_data, 'host_data' => $host_data]);
            $email->helpers(['Html', 'Form', 'Time']);

            if($reservation->event->location->location_static_map_path == null)
            {
                $map_static = $this->Locations->getStaticMap($reservation->event->location);
            }
            else
            {
                $map_static = $reservation->event->location->location_static_map_path;
            }

            $email->attachments([
                'logo_smov_new.png' => [
                    'file' => 'img/logo_smov_new.png',
                    'mimetype' => 'image/png',
                    'contentId' => 'logo-id'
                ],
                'logo_smov_new_2.png' => [
                    'file' => 'img/logo_smov_new_2.png',
                    'mimetype' => 'image/png',
                    'contentId' => 'logo-team-id'
                ],
                'facebook.png' => [
                    'file' => 'img/emails/facebook.png',
                    'mimetype' => 'image/png',
                    'contentId' => 'facebook-id'
                ],
                'twitter.png' => [
                    'file' => 'img/emails/twitter.png',
                    'mimetype' => 'image/png',
                    'contentId' => 'twitter-id'
                ],
                'linkedin.png' => [
                    'file' => 'img/emails/linkedin.png',
                    'mimetype' => 'image/png',
                    'contentId' => 'linkedin-id'
                ],
                'host' => [
                    'file' => 'img/application/user-avatars/default-user.png',
                    'mimetype' => 'image/png',
                    'contentId' => 'host-avatar-id'
                ],
                'event' => [
                    'file' => 'img/application/events/'.$reservation->event->photos[0]->file_name,
                    'mimetype' => $reservation->event->photos[0]->mimetype_name,
                    'contentId' => 'event-cover-id'
                ],
                'map' => [
                    'file' => $map_static,
                    'mimetype' => 'image/png',
                    'contentId' => 'map-id'
                ]

            ]);
            

            $email->from (array('contact@atlocals.com' => 'AtLocals'));
            $email->to(($user_data != null) ? $user_data['UserInformation']['email'] : $reservation->invitation->email, ($user_data != null) ? $user_data['UserInformation']['display_name'] : ucwords($reservation->invitation->name).' '.ucwords($reservation->invitation->last_name));

            if($email->send())
            {
                return true;
            }
            else
            {
                return false;
            }
        }
    }

    function sendGiftcard($giftcard = null, $email_sender =null){
        if($giftcard != null && $email_sender != null)
        {
            $email = new Email('default');
            //$email->template('confirmed_payment', 'public_layout');
            $email->template('send_giftcard', 'info_layout');
            $email->emailFormat('html');
            $email->subject(__('Today is your lucky day, we have given you a gift card'));
            $email->viewVars(['giftcard' => $giftcard, 'email' => $email]);
            $email->helpers(['Html', 'Form', 'Time']);

            $email->attachments([
                'logo_smov_new.png' => [
                    'file' => 'img/logo_smov_new.png',
                    'mimetype' => 'image/png',
                    'contentId' => 'logo-id'
                ],
                'logo_smov_new_2.png' => [
                    'file' => 'img/logo_smov_new_2.png',
                    'mimetype' => 'image/png',
                    'contentId' => 'logo-team-id'
                ],
                'facebook.png' => [
                    'file' => 'img/emails/facebook.png',
                    'mimetype' => 'image/png',
                    'contentId' => 'facebook-id'
                ],
                'twitter.png' => [
                    'file' => 'img/emails/twitter.png',
                    'mimetype' => 'image/png',
                    'contentId' => 'twitter-id'
                ],
                'linkedin.png' => [
                    'file' => 'img/emails/linkedin.png',
                    'mimetype' => 'image/png',
                    'contentId' => 'linkedin-id'
                ],
                'giftcard' => [
                    'file' => 'img/emails/gift-card.png',
                    'mimetype' => 'image/png',
                    'contentId' => 'giftcard-id'
                ]
            ]);
            

            $email->from (array('contact@atlocals.com' => 'AtLocals'));
            $email->to($email_sender, $email_sender);

            if($email->send())
            {
                return true;
            }
            else
            {
                return false;
            }
        }

    }


    function getAllRetailUsersForStore($robot_report_id = null, $store_id = null, $section_id = 'null', $show = false){

        $users = [];

        if($store_id != null && $robot_report_id != null){

            $conditions = [];

            $this->loadModel('UsersCompanies');

            $conditions['UsersCompanies.store_id'] = $store_id;

            if($section_id != 'null'){
                $conditions['UsersCompanies.section_id'] = $section_id;
            }

            $retailers = $this->UsersCompanies->find('all')
                ->contain([
                    'Users', 
                    'UsersCompaniesRobotReports' => [
                        'queryBuilder' => function (\Cake\ORM\Query $query) use($robot_report_id) {
                            return $query->where(['UsersCompaniesRobotReports.robot_report_id' => $robot_report_id]);
                        },
                        'RobotReports'
                    ]
                ])
                ->matching('UsersCompaniesRobotReports', function ($q) use($robot_report_id){
                    return $q
                        ->where([
                            'UsersCompaniesRobotReports.robot_report_id' => $robot_report_id,
                        ]);
                })
                ->where($conditions)
                ->toArray();

            

            if(count($retailers) > 0){

                foreach($retailers as $retailer){
                    $users[$retailer->user->id]['name'] = $retailer->user->name;
                    $users[$retailer->user->id]['last_name'] = $retailer->user->last_name;
                    $users[$retailer->user->id]['email'] = $retailer->user->email;
                }
            }
        }

        if($show == true){
            echo '<pre>';
            print_r($users);
            echo '</pre>';

            die('end');
        }

        return $users;
    }

    function getAllSupplierUsersForStore($robot_report_id = null, $store_id = null, $supplier_id = null, $section_id = 'null', $show = false){

        $users = [];

        if($store_id != null && $robot_report_id != null && $supplier_id != null){

            $conditions = [];

            $this->loadModel('UsersSuppliers');

            $conditions['UsersSuppliers.store_id'] = $store_id;

            if($section_id != 'null'){
                $conditions['UsersSuppliers.section_id'] = $section_id;
            }

            $suppliers = $this->UsersSuppliers->find('all')
                ->contain([
                    'Users', 
                    'UsersSuppliersRobotReports' => [
                        'queryBuilder' => function (\Cake\ORM\Query $query) use($robot_report_id) {
                            return $query->where(['UsersSuppliersRobotReports.robot_report_id' => $robot_report_id]);
                        },
                        'RobotReports'
                    ]
                ])
                ->matching('UsersSuppliersRobotReports', function ($q) use($robot_report_id){
                    return $q
                        ->where([
                            'UsersSuppliersRobotReports.robot_report_id' => $robot_report_id,
                        ]);
                })
                ->where($conditions)
                ->toArray();

            

            if(count($suppliers) > 0){

                foreach($suppliers as $supplier){
                    $users[$supplier->user->id]['name'] = $supplier->user->name;
                    $users[$supplier->user->id]['last_name'] = $supplier->user->last_name;
                    $users[$supplier->user->id]['email'] = $supplier->user->email;
                }
            }
        }

        if($show == true){
            echo '<pre>';
            print_r($users);
            echo '</pre>';

            die('end');
        }

        return $users;
    }
}
