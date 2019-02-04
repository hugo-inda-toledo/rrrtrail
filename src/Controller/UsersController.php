<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;
use Cake\Utility\Security;
use Cake\Mailer\Email;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 *
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        // Allow users to register and logout.
        // You should not add the "login" action to allow list. Doing so would
        // cause problems with normal functioning of AuthComponent.
        $this->Auth->allow(['logout', 'confirmInvitation', 'recoverPassword', 'resetPasswordToken']);
        $this->loadModel('UsersCompaniesRobotReports');
    }

    public function login()
    {
        if($this->request->session()->read('Auth.User') != null){
            return $this->redirect(['controller' => 'Content', 'action' => 'verifyRootRedirect', 'plugin' => false]);
        }

        $this->viewBuilder()->setLayout('new_login_layout');

        if ($this->request->is('post')) {
            $user = $this->Auth->identify();
            if ($user) {

                $this->Auth->setUser($user);
                
                $this->loadModel('UsersCompanies');
                $this->loadModel('UsersSuppliers');

                $companies = $this->UsersCompanies->find('all')
                    ->contain([
                        'Companies',
                        'Stores'
                    ])
                    ->where([
                        'UsersCompanies.user_id' => $this->request->session()->read('Auth.User.id'),
                        'UsersCompanies.enabled' => 1
                    ])
                    ->toArray();

                $array_companies = (array) $companies;
                $array_suppliers = $this->orderArraySuppliers();
                $this->request->session()->write('Auth.Companies', $array_companies);
                $this->request->session()->write('Auth.Suppliers', $array_suppliers);


                if(count($array_companies) > 0){
                    return $this->redirect(['controller' => 'Stores', 'action' => 'map', 'plugin' => 'Retailers']);
                }
                else{

                    if(count($array_suppliers) > 0){
                        return $this->redirect(['controller' => 'Stores', 'action' => 'map', 'plugin' => 'Suppliers']);
                    } 
                }     
            }

            $this->Flash->error(__('Invalid username or password, try again'));
        }
    }

    public function logout()
    {
        return $this->redirect($this->Auth->logout());
    }

    public function settings(){

        $this->viewBuilder()->setLayout('new_default');

        $user = $this->Users->find('all')
            ->contain([
                'Companies'
            ])
            ->where([
                'Users.id' => $this->request->session()->read('Auth.User.id')
            ])
            ->first();

        if(empty($this->request->data)){

            $users_companies = $this->Users->UsersCompanies->find('all', ['contain' => ['UsersCompaniesRobotReports' => ['RobotReports'], 'Companies', 'Stores' => ['Locations' => ['Countries', 'Regions', 'Communes']], 'Sections'], 'conditions' => ['UsersCompanies.user_id' => $this->request->session()->read('Auth.User.id')]])->order(['UsersCompanies.company_id' => 'ASC', 'UsersCompanies.store_id' => 'ASC', 'UsersCompanies.section_id' => 'ASC'])->toArray();

            $robot_reports = $this->Users->RobotReports->find('all', [])->toArray();

            $this->set('user', $user);
            $this->set('users_companies', $users_companies);
            $this->set('robot_reports', $robot_reports);
        }
        else{

            /*echo '<pre>';
            print_r($this->request->data);
            echo '</pre>';

            die();*/

            $newsletter_data_response = [
                'status' => 'none',
                'error_message' => ''
            ];

            if(isset($this->request->data['UsersCompaniesRobotReports'])){

                //Iterar Newsletter
                if(array_key_exists('UsersCompaniesRobotReports', $this->request->data) && count($this->request->data['UsersCompaniesRobotReports']) > 0){


                    for($x=0; $x < count($this->request->data['UsersCompaniesRobotReports']); $x++){

                        //Existe el registro
                        if(isset($this->request->data['UsersCompaniesRobotReports'][$x]['id'])){

                            $user_company_robot_report = $this->UsersCompaniesRobotReports->get($this->request->data['UsersCompaniesRobotReports'][$x]['id'], ['contain' => ['RobotReports']]);

                            if($user_company_robot_report != null && !isset($this->request->data['UsersCompaniesRobotReports'][$x][$user_company_robot_report->robot_report->report_keyword])){

                                $this->UsersCompaniesRobotReports->delete($user_company_robot_report);
                                $newsletter_data_response['status'] = 'ok';
                            }   
                        }
                        else{
                            //No existe el registro

                            $this->loadModel('RobotReports');

                            $robot_report = $this->RobotReports->find()
                                ->where([
                                    'RobotReports.id' => $this->request->data['UsersCompaniesRobotReports'][$x]['robot_report_id']
                                ])
                                ->first();

                            if($robot_report != null && isset($this->request->data['UsersCompaniesRobotReports'][$x][$robot_report->report_keyword])){
                                $user_company_robot_report = $this->UsersCompaniesRobotReports->newEntity();
                                $user_company_robot_report->user_company_id = $this->request->data['UsersCompaniesRobotReports'][$x]['user_company_id'];
                                $user_company_robot_report->robot_report_id = $robot_report->id;
                                $user_company_robot_report->enabled = 1;

                                $this->UsersCompaniesRobotReports->save($user_company_robot_report);
                                $newsletter_data_response['status'] = 'ok';
                            }
                        }
                    }
                }
            }            
            

            /*** Start password save process ***/
            $password_data_response = [
                'status' => 'none',
                'error_message' => ''
            ];

            if($this->request->data['Users']['password'] != '' && $this->request->data['Users']['retype_password'] != ''){

                if($this->request->data['Users']['password'] == $this->request->data['Users']['retype_password']){


                    if(strlen($this->request->data['Users']['password']) <= 5){
                        $password_data_response['error_message'] = __('Password must be at least 6 characters');
                    }
                    else{
                        if(1 === preg_match('~[0-9]~', $this->request->data['Users']['password'])){

                            if(preg_match('/[A-Z]/', $this->request->data['Users']['password'])){

                                $user->password = $this->request->data['Users']['password'];
                                
                                if($this->Users->save($user)){
                                    $password_data_response['status'] = 'ok';
                                }
                                else{
                                    $password_data_response['status'] = 'error';
                                }
                            }
                            else{
                                $password_data_response['error_message'] = __('Password must have at least one upper case letter');
                                $password_data_response['status'] = 'error';
                            }
                        }
                        else{
                            $password_data_response['error_message'] = __('Password must be at least one number');
                            $password_data_response['status'] = 'error';
                        }
                    }
                }
                else{
                    $password_data_response['error_message'] = __('Both passwords must be equals');
                    $password_data_response['status'] = 'error';
                }
            }
            /*** End password save process ***/

            /*print_r($newsletter_data_response);
            print_r($password_data_response);*/

            switch ($newsletter_data_response['status']) {
                case 'none':
                    switch ($password_data_response['status']) {
                        case 'none':
                            $this->Flash->info(__('No changes'));
                            break;

                        case 'error':
                            $this->Flash->error(__('Error to update password : {0}', [$password_data_response['error_message']]));
                            break;

                        case 'ok':
                            $this->Flash->success(__('Password updated successfull'));
                            break;
                        
                        default:
                            # code...
                            break;
                    }
                    break;

                case 'ok':
                    
                    switch ($password_data_response['status']) {
                        case 'none':
                            $this->Flash->success(__('Information updated successfull'));
                            break;
                        case 'ok':
                            $this->Flash->success(__('Password and information updated successfull'));
                            break;

                        case 'error':
                            $this->Flash->warning(__('Information updated successfull. Error on update password: {0}', $password_data_response['error_message']));
                            break;

                        default:
                            # code...
                            break;
                    }

                    break;

                case 'error':
                    switch ($password_data_response['status']) {
                        case 'none':
                            $this->Flash->error(__('Error to update information: {0}', [$newsletter_data_response['error_message']]));
                            break;

                        case 'ok':
                            $this->Flash->warning(__('Password changed successfull. Error on update information: {0}', $newsletter_data_response['error_message']));
                            break;

                        case 'error':
                            $this->Flash->error(__('Error to update information: {0}. Error to update password: {1}', [$newsletter_data_response['error_message'], $password_data_response['error_message']]));
                            break;
                        
                        default:
                            # code...
                            break;
                    }
                    break;
                
                default:
                    # code...
                    break;
            }

            return $this->redirect($this->referer());
        }

        

    }

    function confirmInvitation($hash = null){

        $this->viewBuilder()->setLayout('new_login_layout');
        $this->loadModel('Invitations');

        if(empty($this->request->data)) 
        {
            $invitation = $this->Invitations->find('all')
                ->where([
                    'Invitations.hash_code' => $hash,
                    'Invitations.active' => 1,
                    'Invitations.submitted' => 0,
                ])
                ->first();

            if($invitation != null)
            {
                $now = New Time();
                if($invitation->expired_date > $now)
                {
                    $user = $this->Users->find()
                        //->contain(['UsersCompanies' => ['Stores', 'Companies'], 'UsersSuppliers' => ['Stores', 'Companies', 'Sections']])
                        ->where([
                            'Users.id' => $invitation->user_id
                        ])
                        ->first();

                    $stores = $this->getAllUserStores(null, $user->id);


                    $this->set('invitation', $invitation);
                    $this->set('user', $user);
                    $this->set('stores', $stores);
                }
                else
                {
                    $this->Flash->error(__('The token has expired, please login to re-send a new token to your email').'.');
                    return $this->redirect('/');
                }
            }
            else
            {
                $this->Flash->error(__('No valid token').'.');
                return $this->redirect('/');
            }
        }
        else
        {

            if($this->request->data['password'] != $this->request->data['retype_password'])
            {
                $this->Flash->error(__('Both passwords must be equals'));
                $this->redirect(array('action' => 'confirmInvitation', $this->request->data['hash_code']));
            }
            else
            {
                if(strlen($this->request->data['password']) <= 5){
                    $this->Flash->error(__('Password must be at least 6 characters'));
                    $this->redirect(array('action' => 'confirmInvitation', $this->request->data['hash_code']));
                }
                else{
                    if(1 === preg_match('~[0-9]~', $this->request->data['password'])){

                        if(preg_match('/[A-Z]/', $this->request->data['password'])){

                            $invitation = $this->Users->Invitations->find('All')->where(['Invitations.hash_code' => $this->request->data['hash_code']])->first();
                            $user = $this->Users->find('All')->where(['Users.id' => $invitation->user_id])->first();

                            
                            //$user->password = Security::hash($this->request->data['Users']['password']);
                            $user->password = $this->request->data['password'];
                            $user->active = 1;

                            $user->password_changed = 1;

                            $invitation->submitted = 1;
                            
                            if($this->Users->save($user) && $this->Users->Invitations->save($invitation)) 
                            {
                                $this->Flash->success(__("Password changed successfully. Please, login now"));
                                $this->redirect('/');
                            }
                        }
                        else{
                            $this->Flash->error(__('Password must have at least one upper case letter'));
                            $this->redirect(array('action' => 'confirmInvitation', $this->request->data['hash_code']));
                        }
                    }
                    else{
                        $this->Flash->error(__('Password must be at least one number'));
                        $this->redirect(array('action' => 'confirmInvitation', $this->request->data['hash_code']));
                    }
                }
            }
        }
    }


    function passwordChange($password_token = null){

        $this->viewBuilder()->setLayout('new_login_layout');

        if(empty($this->request->data)) 
        {
            $user = $this->Users->find('all')
                ->where([
                    'Users.password_token' => $password_token,
                ])
                ->first();

            if($user != null)
            {
                $stores = $this->getAllUserStores(null, $user->id);
                $this->set('user', $user);
                $this->set('stores', $stores);
            }
            else
            {
                return $this->redirect('/');
            }
        }
        else
        {

            if($this->request->data['password'] != $this->request->data['retype_password'])
            {
                $this->Flash->error(__('Both passwords must be equals'));
                $this->redirect(array('action' => 'passwordChange', $this->request->data['password_token']));
            }
            else
            {
                if(strlen($this->request->data['password']) <= 5){
                    $this->Flash->error(__('Password must be at least 6 characters'));
                    $this->redirect(array('action' => 'passwordChange', $this->request->data['password_token']));
                }
                else{
                    if(1 === preg_match('~[0-9]~', $this->request->data['password'])){

                        if(preg_match('/[A-Z]/', $this->request->data['password'])){

                            $user = $this->Users->find('All')->where(['Users.password_token' => $this->request->data['password_token']])->first();

                            
                            //$user->password = Security::hash($this->request->data['Users']['password']);
                            $user->password = $this->request->data['password'];
                            $user->active = 1;
                            $user->password_token = null;

                            $user->password_changed = 1;
                            
                            if($this->Users->save($user)) 
                            {
                                $this->request->session()->write('Auth.User.password_changed', 1);

                                $this->Flash->success(__("Password changed successfull"));
                                $this->redirect('/');
                            }
                        }
                        else{
                            $this->Flash->error(__('Password must have at least one upper case letter'));
                            $this->redirect(array('action' => 'passwordChange', $this->request->data['password_token']));
                        }
                    }
                    else{
                        $this->Flash->error(__('Password must be at least one number'));
                        $this->redirect(array('action' => 'passwordChange', $this->request->data['password_token']));
                    }
                }
            }
        }
    }

    function recoverPassword(){

        if(!empty($this->request->data)){

            $user = $this->Users->find('all')
                    ->where(['Users.email' => $this->request->data['recover_email']])
                    ->first();

            if($user != null)
            {
                if($user->active != 1)
                {
                    $this->Flash->warning(__('The account for the email {0} is disabled of the platform', $this->request->data['recover_email']));
                    return $this->redirect($this->referer());
                }
                else
                {
                    $user = $this->__generateRecoveryToken($user);

                    if ($this->Users->save($user) && $this->__sendForgotPasswordEmail($user->id) == true) 
                    {
                        $this->Flash->success(__('The instructions to change the password would be send to your email. You have 60 minutos to complete the process'));
                        return $this->redirect($this->referer());
                    }
                }
            }
            else
            {
                $this->Flash->error(__('Sorry, the email not exist in our registers'));
                return $this->redirect($this->referer());
            }
        }
    }

    function orderArraySuppliers($users_suppliers = null){

        $this->loadModel('UsersSuppliers');

        $users_suppliers = $this->UsersSuppliers->find('all')
            ->contain([
                'Companies' => [
                    'Stores',
                    'Sections'
                ],
                'Stores',
                'Suppliers',
                'Sections'
            ])
            ->where([
                'UsersSuppliers.user_id' => $this->request->session()->read('Auth.User.id'),
                'UsersSuppliers.enabled' => 1
            ])
            ->toArray();

        $new_array = [];
        
        if(count($users_suppliers) > 0 ){
            foreach($users_suppliers as $user_supplier){
                if(count($user_supplier->company->stores) > 0){

                    foreach($user_supplier->company->stores as $store){
                        if($store->id == $user_supplier->store->id){
                            if(count($user_supplier->company->sections) > 0){

                                foreach($user_supplier->company->sections as $section){
                                    if($section->id == $user_supplier->section->id && $section->company_id = $user_supplier->section->company_id){

                                        $new_array[$user_supplier->supplier->supplier_name]['companies'][$user_supplier->company->company_name]['stores'][$user_supplier->store->store_name]['sections'][$user_supplier->section->section_name] = $user_supplier->section;

                                        $new_array[$user_supplier->supplier->supplier_name]['companies'][$user_supplier->company->company_name]['company_keyword'] = $user_supplier->company->company_keyword;

                                        $new_array[$user_supplier->supplier->supplier_name]['companies'][$user_supplier->company->company_name]['stores'][$user_supplier->store->store_name]['store_code'] = $user_supplier->store->store_code;

                                        $new_array[$user_supplier->supplier->supplier_name]['supplier_id'] = $user_supplier->supplier->id;

                                        
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return $new_array;
    }

    function getAllUserStores($type = null, $user_id = null){
        $this->loadModel('UsersSuppliers');
        $this->loadModel('UsersCompanies');

        $stores_data = array();

        $user_id_query = null;

        if($user_id != null){
            $user_id_query = $user_id;
        }
        else{
            $user_id_query = $this->request->session()->read('Auth.User.id');
        }

        $users_suppliers_data = $this->UsersSuppliers->find('all')
            ->contain([
                'Stores' => [
                    /*'RobotSessions' => [
                        'conditions' => [
                            //'PriceUpdates.company_updated <=' => $session_date,
                            //'RobotSessions.finished' => 1,                            
                            //'RobotSessions.processing' => 0,  
                        ],
                        'sort' => ['RobotSessions.session_date' => 'DESC']
                    ], */
                    'Locations' => [
                        'Countries', 'Regions', 'Communes'
                    ]
                ], 
                'Suppliers', 
                'Companies', 
                'Sections'
            ])
            ->where(['UsersSuppliers.user_id' => $user_id_query])
            ->toArray();

        $users_companies_data = $this->UsersCompanies->find('all')->contain(['Stores' => ['RobotSessions', 'Locations' => ['Countries', 'Regions', 'Communes']], 'Companies', 'Sections'])->where(['UsersCompanies.user_id' => $user_id_query])->toArray();

        if(count($users_suppliers_data) > 0){
            foreach($users_suppliers_data as $user_supplier_data){

                switch ($type) {
                    case 'list':
                        $stores_data[$user_supplier_data->store->id] = $user_supplier_data->company->company_name.' '.$user_supplier_data->store->store_name;
                        $stores_data[$user_supplier_data->store->id] = $user_supplier_data->company->company_name.' '.$user_supplier_data->store->store_name;
                        break;

                    case 'map':
                        $stores_data[$user_supplier_data->company->company_name.' '.$user_supplier_data->store->store_name] = $user_supplier_data->store;
                        $stores_data[$user_supplier_data->company->company_name.' '.$user_supplier_data->store->store_name]->company = $user_supplier_data->company;
                        break;

                    case 'array_list_id':
                        $stores_data[$user_supplier_data->store->id] = $user_supplier_data->company->company_name.' '.$user_supplier_data->store->store_name;
                        break;

                    case 'array_list_code':
                        $stores_data[$user_supplier_data->store->store_code] = $user_supplier_data->company->company_name.' '.$user_supplier_data->store->store_name;
                        break;

                    
                    default:
                        $stores_data[$user_supplier_data->store->id]['store'] = $user_supplier_data->store;
                        $stores_data[$user_supplier_data->store->id]['company'] = $user_supplier_data->company;
                        break;
                }
                
            }
        }
        
        if(count($users_companies_data) > 0){
            foreach($users_companies_data as $user_company_data){
                switch ($type) {
                    case 'list':
                        $stores_data[$user_company_data->store->id] = $user_company_data->company->company_name.' '.$user_company_data->store->store_name;
                        $stores_data[$user_company_data->store->id] = $user_company_data->company->company_name.' '.$user_company_data->store->store_name;
                        break;

                    case 'map':
                        $stores_data[$user_company_data->company->company_name.' '.$user_company_data->store->store_name] = $user_company_data->store;
                        $stores_data[$user_company_data->company->company_name.' '.$user_company_data->store->store_name]->company = $user_company_data->company;
                        break;

                    case 'array_list_id':
                        $stores_data[$user_company_data->store->id] = $user_company_data->company->company_name.' '.$user_company_data->store->store_name;
                        break;

                    case 'array_list_code':
                        $stores_data[$user_company_data->store->store_code] = $user_company_data->company->company_name.' '.$user_company_data->store->store_name;
                        break;

                    default:
                        $stores_data[$user_company_data->store->id]['store'] = $user_company_data->store;
                        $stores_data[$user_company_data->store->id]['company'] = $user_company_data->company;
                        break;
                }
            }
        }
        
        return $stores_data;   
    }


    function __generateRecoveryToken($user) 
    {
        if (empty($user)) 
        {
            return null;
        }
        // Generate a random string 100 chars in length.
        $token = "";
        for ($i = 0; $i < 100; $i++) 
        {
            $d = rand(1, 100000) % 2;
            $d ? $token .= chr(rand(33,79)) : $token .= chr(rand(80,126));
        }

        (rand(1, 100000) % 2) ? $token = strrev($token) : $token = $token;

        // Generate hash of random string
        $hash = Security::hash($token, 'sha256', true);
        
        for ($i = 0; $i < 20; $i++) 
        {
            $hash = Security::hash($hash, 'sha256', true);
        }

        $user->recovery_token = $hash;
        $user->recovery_expired_date = New Time();
        $user->recovery_expired_date->modify('+60 minutes');
        return $user;
    }

    function __sendForgotPasswordEmail($id = null) 
    {
        if(!empty($id)) 
        {
            $user= $this->Users->get($id);

            if($user != null)
            {
                $email = new Email('default');
                $email->emailFormat('html');
                $email->template('reset_password_request', 'modern');
                $email->viewVars(array('user' => $user));
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
                $email->subject(__('Password reset request'));
                
                $email->from (array('reports@zippedi.com' => 'Zippedi Reports'));
                $email->to($user->email, $user->name.' '.$user->last_name);


                if($email->send())
                {
                    return true;
                }
            }
        }

        return false;
    }
    function resetPasswordToken($recovery_hash = null){
        
        $this->viewBuilder()->setLayout('new_login_layout');

        if(empty($this->request->data)){
            if($recovery_hash != null){

                $user = $this->Users->find('all')
                    ->where([
                        'Users.recovery_token' => $recovery_hash,                
                    ])
                    ->first();

                if($user != null){
                    if($user->active == 0){
                        $this->Flash->error(__("Disabled user"));
                        $this->redirect('/');
                    }
                    else{

                        $now = New Time();
                        if($user->recovery_expired_date > $now){
                            $this->set('user', $user);
                        }
                        else{
                            $this->Flash->error(__('The token has expired, please request reset the password again').'.');
                            return $this->redirect('/');
                        }
                    }
                }
                else
                {
                    $this->Flash->error(__("Not exist password reset request"));
                    $this->redirect('/');
                }

            }
            else{
                $this->Flash->error(__("Recovery token not found").'.');
                $this->redirect('/');
            }
        }
        else{

            if($this->request->data['password'] != $this->request->data['retype_password'])
            {
                $this->Flash->error(__('Both passwords must be equals'));
                $this->redirect(array('action' => 'resetPasswordToken', $this->request->data['recovery_token']));
            }
            else
            {
                if(strlen($this->request->data['password']) <= 5){
                    $this->Flash->error(__('Password must be at least 6 characters'));
                    $this->redirect(array('action' => 'resetPasswordToken', $this->request->data['recovery_token']));
                }
                else{
                    if(1 === preg_match('~[0-9]~', $this->request->data['password'])){

                        if(preg_match('/[A-Z]/', $this->request->data['password'])){

                            $user = $this->Users->find('All')->where(['Users.recovery_token' => $this->request->data['recovery_token']])->first();

                            
                            //$user->password = Security::hash($this->request->data['Users']['password']);
                            $user->password = $this->request->data['password'];
                            $user->recovery_token = null;
                            $user->recovery_expired_date = null;

                            
                            if($this->Users->save($user)) 
                            {
                                $this->Flash->success(__("Password changed successfull"));
                                $this->redirect('/');
                            }
                        }
                        else{
                            $this->Flash->error(__('Password must have at least one upper case letter'));
                            $this->redirect(array('action' => 'resetPasswordToken', $this->request->data['recovery_token']));
                        }
                    }
                    else{
                        $this->Flash->error(__('Password must be at least one number'));
                        $this->redirect(array('action' => 'resetPasswordToken', $this->request->data['recovery_token']));
                    }
                }
            }
        }
    }
}
