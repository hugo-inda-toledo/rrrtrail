<?php
namespace Management\Controller;

use Management\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Utility\Security;
use Cake\Auth\DefaultPasswordHasher;
use App\Controller\EmailsController;
use Cake\I18n\Time;

/**
 * Users Controller
 *
 * @property \Management\Model\Table\UsersTable $Users
 *
 * @method \Management\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->loadModel('Users');
        $this->loadModel('Invitations');
        $this->loadModel('UsersCompaniesRobotReports');
        $this->loadModel('UsersSuppliersRobotReports');
    }
    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $users = $this->paginate($this->Users);

        $this->set(compact('users'));
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => ['Companies', 'Groups', 'Permissions', 'Suppliers']
        ]);

        $this->set('user', $user);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        if ($this->request->is('post')) {

            $this->request->data['Users']['username'] = $this->request->data['Users']['email'];
            $this->request->data['Users']['password_changed'] = 0;
            

            if($this->request->data['send_invitation'] == 0){
                $this->request->data['Users']['active'] = 1;
                $this->request->data['Users']['password_token'] = Security::hash($this->request->data['Users']['email'], 'sha256', true);
            }
            else{

                $this->request->data['Users']['password'] = $this->request->data['Users']['email'];
                $this->request->data['Users']['active'] = 0;
                $this->request->data['Users']['is_invitation'] = 1;
                
            }


            /*echo '<pre>';
            print_r($this->request->data);
            echo '</pre>';

            die();*/
            

            $user = $this->Users->newEntity();
            $user = $this->Users->patchEntity($user, $this->request->data['Users']);
            $user->is_admin = 0;

            if ($this->Users->save($user)) {

                if(isset($this->request->data['UsersCompanies']) || isset($this->request->data['UsersSuppliers'])){

                    //Iterar Companias
                    if(array_key_exists('UsersCompanies', $this->request->data) && count($this->request->data['UsersCompanies']) > 0){
                        for($x=0; $x < count($this->request->data['UsersCompanies']); $x++){
                            $this->request->data['UsersCompanies'][$x]['user_id'] = $user->id;
                            $this->request->data['UsersCompanies'][$x]['enabled'] = 1; 
                        }

                        $users_companies_table = TableRegistry::get('UsersCompanies');
                        $entities = $users_companies_table->newEntities($this->request->data['UsersCompanies']);
                        if(!$users_companies_table->saveMany($entities)){
                            $this->Users->delete($user);
                            $this->Flash->error(__('Error on save the company associations'));

                            return $this->redirect(['action' => 'add']);
                        }
                        else{

                            for($x=0; $x < count($this->request->data['UsersCompanies']); $x++){

                                $user_company = $users_companies_table->find()
                                    ->select('UsersCompanies.id')
                                    ->where([
                                        'UsersCompanies.user_id' => $this->request->data['UsersCompanies'][$x]['user_id'],
                                        'UsersCompanies.company_id' => $this->request->data['UsersCompanies'][$x]['company_id'],
                                        'UsersCompanies.store_id' => $this->request->data['UsersCompanies'][$x]['store_id'],
                                        'UsersCompanies.section_id' => $this->request->data['UsersCompanies'][$x]['section_id'],
                                    ])
                                    ->first();

                                if($user_company != null){

                                    //Association to assortment
                                    if(isset($this->request->data['UsersCompanies'][$x]['assortment_report'])){

                                        $this->loadModel('UsersCompaniesRobotReports');
                                        $this->loadModel('RobotReports');

                                        $robot_report = $this->RobotReports->find()
                                            ->select('RobotReports.id')
                                            ->where([
                                                'RobotReports.report_keyword' => $this->request->data['UsersCompanies'][$x]['assortment_report']
                                            ])
                                            ->first();

                                        if($robot_report != null){
                                            $user_company_robot_report = $this->UsersCompaniesRobotReports->newEntity();
                                            $user_company_robot_report->user_company_id = $user_company->id;
                                            $user_company_robot_report->robot_report_id = $robot_report->id;
                                            $user_company_robot_report->enabled = 1;

                                            $this->UsersCompaniesRobotReports->save($user_company_robot_report);
                                        }
                                    }

                                    //Association to price differences
                                    if(isset($this->request->data['UsersCompanies'][$x]['price_differences'])){
                                        $this->loadModel('UsersCompaniesRobotReports');
                                        $this->loadModel('RobotReports');

                                        $robot_report = $this->RobotReports->find()
                                            ->select('RobotReports.id')
                                            ->where([
                                                'RobotReports.report_keyword' => $this->request->data['UsersCompanies'][$x]['price_differences']
                                            ])
                                            ->first();

                                        if($robot_report != null){
                                            $user_company_robot_report = $this->UsersCompaniesRobotReports->newEntity();
                                            $user_company_robot_report->user_company_id = $user_company->id;
                                            $user_company_robot_report->robot_report_id = $robot_report->id;
                                            $user_company_robot_report->enabled = 1;

                                            $this->UsersCompaniesRobotReports->save($user_company_robot_report);
                                        }
                                    }

                                    //Association to assortment
                                    if(isset($this->request->data['UsersCompanies'][$x]['stock_alert'])){
                                        $this->loadModel('UsersCompaniesRobotReports');
                                        $this->loadModel('RobotReports');

                                        $robot_report = $this->RobotReports->find()
                                            ->select('RobotReports.id')
                                            ->where([
                                                'RobotReports.report_keyword' => $this->request->data['UsersCompanies'][$x]['stock_alert']
                                            ])
                                            ->first();

                                        if($robot_report != null){
                                            $user_company_robot_report = $this->UsersCompaniesRobotReports->newEntity();
                                            $user_company_robot_report->user_company_id = $user_company->id;
                                            $user_company_robot_report->robot_report_id = $robot_report->id;
                                            $user_company_robot_report->enabled = 1;

                                            $this->UsersCompaniesRobotReports->save($user_company_robot_report);
                                        }
                                    }
                                }
                            }

                        }
                    }

                    //Iterar Companias
                    if(array_key_exists('UsersSuppliers', $this->request->data) && count($this->request->data['UsersSuppliers']) > 0){
                        for($x=0; $x < count($this->request->data['UsersSuppliers']); $x++){
                            $this->request->data['UsersSuppliers'][$x]['user_id'] = $user->id;
                            $this->request->data['UsersSuppliers'][$x]['enabled'] = 1; 
                        }

                        $users_suppliers_table = TableRegistry::get('UsersSuppliers');
                        $entities = $users_suppliers_table->newEntities($this->request->data['UsersSuppliers']);
                        if(!$users_suppliers_table->saveMany($entities)){
                            $this->Users->delete($user);
                            $this->Flash->error(__('Error on save the supplier associations'));

                            return $this->redirect(['action' => 'add']);
                        }
                        else{

                            for($x=0; $x < count($this->request->data['UsersSuppliers']); $x++){

                                $user_supplier = $users_suppliers_table->find()
                                    ->select('UsersSuppliers.id')
                                    ->where([
                                        'UsersSuppliers.user_id' => $this->request->data['UsersSuppliers'][$x]['user_id'],
                                        'UsersSuppliers.company_id' => $this->request->data['UsersSuppliers'][$x]['company_id'],
                                        'UsersSuppliers.store_id' => $this->request->data['UsersSuppliers'][$x]['store_id'],
                                        'UsersSuppliers.section_id' => $this->request->data['UsersSuppliers'][$x]['section_id'],
                                        'UsersSuppliers.supplier_id' => $this->request->data['UsersSuppliers'][$x]['supplier_id'],
                                    ])
                                    ->first();

                                if($user_supplier != null){

                                    //Association to assortment
                                    if(isset($this->request->data['UsersSuppliers'][$x]['assortment_report'])){

                                        $this->loadModel('UsersSuppliersRobotReports');
                                        $this->loadModel('RobotReports');

                                        $robot_report = $this->RobotReports->find()
                                            ->select('RobotReports.id')
                                            ->where([
                                                'RobotReports.report_keyword' => $this->request->data['UsersSuppliers'][$x]['assortment_report']
                                            ])
                                            ->first();

                                        if($robot_report != null){
                                            $user_supplier_robot_report = $this->UsersSuppliersRobotReports->newEntity();
                                            $user_supplier_robot_report->user_supplier_id = $user_supplier->id;
                                            $user_supplier_robot_report->robot_report_id = $robot_report->id;
                                            $user_supplier_robot_report->enabled = 1;

                                            $this->UsersSuppliersRobotReports->save($user_supplier_robot_report);
                                        }
                                    }

                                    //Association to price differences
                                    if(isset($this->request->data['UsersSuppliers'][$x]['price_differences'])){
                                        $this->loadModel('UsersSuppliersRobotReports');
                                        $this->loadModel('RobotReports');

                                        $robot_report = $this->RobotReports->find()
                                            ->select('RobotReports.id')
                                            ->where([
                                                'RobotReports.report_keyword' => $this->request->data['UsersSuppliers'][$x]['price_differences']
                                            ])
                                            ->first();

                                        if($robot_report != null){
                                            $user_supplier_robot_report = $this->UsersSuppliersRobotReports->newEntity();
                                            $user_supplier_robot_report->user_supplier_id = $user_supplier->id;
                                            $user_supplier_robot_report->robot_report_id = $robot_report->id;
                                            $user_supplier_robot_report->enabled = 1;

                                            $this->UsersSuppliersRobotReports->save($user_supplier_robot_report);
                                        }
                                    }

                                    //Association to assortment
                                    if(isset($this->request->data['UsersSuppliers'][$x]['stock_alert'])){
                                        $this->loadModel('UsersSuppliersRobotReports');
                                        $this->loadModel('RobotReports');

                                        $robot_report = $this->RobotReports->find()
                                            ->select('RobotReports.id')
                                            ->where([
                                                'RobotReports.report_keyword' => $this->request->data['UsersSuppliers'][$x]['stock_alert']
                                            ])
                                            ->first();

                                        if($robot_report != null){
                                            $user_supplier_robot_report = $this->UsersSuppliersRobotReports->newEntity();
                                            $user_supplier_robot_report->user_supplier_id = $user_supplier->id;
                                            $user_supplier_robot_report->robot_report_id = $robot_report->id;
                                            $user_supplier_robot_report->enabled = 1;

                                            $this->UsersSuppliersRobotReports->save($user_supplier_robot_report);
                                        }
                                    }
                                }
                            }

                        }
                    }


                    if($this->request->data['send_invitation'] == 1){
                        $invitation = $this->Invitations->newEntity();
                        $invitation->user_id = $user->id;
                        $invitation->name = $user->name;
                        $invitation->last_name = $user->last_name;
                        $invitation->email = $user->email;
                        $invitation->hash_code = Security::hash($user->email, 'sha256', true);
                        $invitation->short_code = $this->generateRandomCode(10);
                        $invitation->active = 1;
                        $invitation->submitted = 0;
                        $invitation->requested_public = 0;
                        $invitation->expired_date = new Time();
                        $invitation->expired_date->modify('+1 days');


                        if ($this->Invitations->save($invitation)) {

                            $emails = new EmailsController;
                            if($emails->sendInvitation($invitation) == true)
                            {
                                $this->Flash->success(__('The invitation has been saved and sent'));
                            }
                            else
                            {
                                $this->Flash->error(__('The invitation has not been send'));
                            }

                            
                        }
                        else
                        {
                            $this->Flash->error(__('The invitation could not be saved. Please, try again.'));
                        }

                        return $this->redirect(['action' => 'index']);
                    }
                    else{

                        $this->Flash->success(__('User created successful'));
                        return $this->redirect(['action' => 'index']); 
                    }

                    
                    
                }
                else{

                    $this->Users->delete($user);
                    $this->Flash->error(__('You must associate at least one company or one supplier'));

                    return $this->redirect(['action' => 'add']);
                }
            }
            else{
                $this->Flash->error(__('The user could not be saved. Please, try again.'));
                return $this->redirect(['action' => 'add']);
            }

            
        }

        $companies = $this->Users->Companies->find('list', ['limit' => 200]);
        $groups = $this->Users->Groups->find('list', ['limit' => 200]);
        $permissions = $this->Users->Permissions->find('list', ['limit' => 200]);
        //$suppliers = $this->Users->Suppliers->find('list', ['limit' => 200]);
        $this->set(compact('user', 'companies', 'groups', 'permissions'));
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->loadModel('UsersSuppliers');
        $this->loadModel('UsersCompanies');
        $this->loadModel('UsersRobotReports');

        $user = $this->Users->get($id, [
            'contain' => ['RobotReports', 'Companies', 'Groups', 'Permissions', 'Suppliers' => ['Companies']]
        ]);

        
        if ($this->request->data != null) {

            /*echo '<pre>';
            print_r($this->request->data);
            echo '</pre>';*/


        
            $this->request->data['Users']['username'] = $this->request->data['Users']['email'];
            $user = $this->Users->patchEntity($user, $this->request->data['Users']);

            /*echo '<pre>';
            print_r($user);
            echo '</pre>';

            die();*/

            if ($this->Users->save($user)) {

                if(isset($this->request->data['UsersCompaniesRobotReports']) || isset($this->request->data['UsersSuppliersRobotReports']) ){

                    //Iterar Companias
                    if(array_key_exists('UsersCompanies', $this->request->data) && count($this->request->data['UsersCompanies']) > 0){

                        for($x=0; $x < count($this->request->data['UsersCompanies']); $x++){

                            $exist = $this->UsersCompanies->find('all')
                                ->where([
                                    'UsersCompanies.user_id' => $user->id, 
                                    'UsersCompanies.company_id' => $this->request->data['UsersCompanies'][$x]['company_id'], 
                                    'UsersCompanies.store_id' => $this->request->data['UsersCompanies'][$x]['store_id'], 
                                    'UsersCompanies.section_id' => $this->request->data['UsersCompanies'][$x]['section_id']
                                ])
                                ->count();

                            //Ignorar los existentes
                            if($exist == 0){
                                $this->request->data['UsersCompanies'][$x]['user_id'] = $user->id;
                                $this->request->data['UsersCompanies'][$x]['enabled'] = 1; 
                            }
                        }

                        for($x=0; $x < count($this->request->data['UsersCompanies']); $x++){

                            if(!isset($this->request->data['UsersCompanies'][$x]['user_id'])){
                                unset($this->request->data['UsersCompanies'][$x]);
                            }
                        }

                        $users_companies_table = TableRegistry::get('UsersCompanies');
                        $entities = $users_companies_table->newEntities($this->request->data['UsersCompanies']);
                        if(!$users_companies_table->saveMany($entities)){
                            $this->Flash->error(__('Error on save the company associations'));

                            return $this->redirect(['action' => 'edit', $id]);
                        }
                        else{
                            for($x=0; $x < count($this->request->data['UsersCompanies']); $x++){

                                $user_company = $users_companies_table->find()
                                    ->select('UsersCompanies.id')
                                    ->where([
                                        'UsersCompanies.user_id' => $this->request->data['UsersCompanies'][$x]['user_id'],
                                        'UsersCompanies.company_id' => $this->request->data['UsersCompanies'][$x]['company_id'],
                                        'UsersCompanies.store_id' => $this->request->data['UsersCompanies'][$x]['store_id'],
                                        'UsersCompanies.section_id' => $this->request->data['UsersCompanies'][$x]['section_id'],
                                    ])
                                    ->first();

                                if($user_company != null){

                                    //Association to assortment
                                    if(isset($this->request->data['UsersCompanies'][$x]['assortmentReport'])){

                                        $this->loadModel('UsersCompaniesRobotReports');
                                        $this->loadModel('RobotReports');

                                        $robot_report = $this->RobotReports->find()
                                            ->select('RobotReports.id')
                                            ->where([
                                                'RobotReports.report_keyword' => $this->request->data['UsersCompanies'][$x]['assortmentReport']
                                            ])
                                            ->first();

                                        if($robot_report != null){
                                            $user_company_robot_report = $this->UsersCompaniesRobotReports->newEntity();
                                            $user_company_robot_report->user_company_id = $user_company->id;
                                            $user_company_robot_report->robot_report_id = $robot_report->id;
                                            $user_company_robot_report->enabled = 1;

                                            $this->UsersCompaniesRobotReports->save($user_company_robot_report);
                                        }
                                    }

                                    //Association to price differences
                                    if(isset($this->request->data['UsersCompanies'][$x]['priceDifferenceReport'])){
                                        $this->loadModel('UsersCompaniesRobotReports');
                                        $this->loadModel('RobotReports');

                                        $robot_report = $this->RobotReports->find()
                                            ->select('RobotReports.id')
                                            ->where([
                                                'RobotReports.report_keyword' => $this->request->data['UsersCompanies'][$x]['priceDifferenceReport']
                                            ])
                                            ->first();

                                        if($robot_report != null){
                                            $user_company_robot_report = $this->UsersCompaniesRobotReports->newEntity();
                                            $user_company_robot_report->user_company_id = $user_company->id;
                                            $user_company_robot_report->robot_report_id = $robot_report->id;
                                            $user_company_robot_report->enabled = 1;

                                            $this->UsersCompaniesRobotReports->save($user_company_robot_report);
                                        }
                                    }

                                    //Association to assortment
                                    if(isset($this->request->data['UsersCompanies'][$x]['stockOutReport'])){
                                        $this->loadModel('UsersCompaniesRobotReports');
                                        $this->loadModel('RobotReports');

                                        $robot_report = $this->RobotReports->find()
                                            ->select('RobotReports.id')
                                            ->where([
                                                'RobotReports.report_keyword' => $this->request->data['UsersCompanies'][$x]['stockOutReport']
                                            ])
                                            ->first();

                                        if($robot_report != null){
                                            $user_company_robot_report = $this->UsersCompaniesRobotReports->newEntity();
                                            $user_company_robot_report->user_company_id = $user_company->id;
                                            $user_company_robot_report->robot_report_id = $robot_report->id;
                                            $user_company_robot_report->enabled = 1;

                                            $this->UsersCompaniesRobotReports->save($user_company_robot_report);
                                        }
                                    }
                                }
                            }   
                        }
                    }

                    //Iterar Proveedores
                    if(array_key_exists('UsersSuppliers', $this->request->data) && count($this->request->data['UsersSuppliers']) > 0){

                        for($x=0; $x < count($this->request->data['UsersSuppliers']); $x++){

                            $exist = $this->UsersSuppliers->find('all')
                                ->where([
                                    'UsersSuppliers.user_id' => $user->id, 
                                    'UsersSuppliers.company_id' => $this->request->data['UsersSuppliers'][$x]['company_id'], 
                                    'UsersSuppliers.store_id' => $this->request->data['UsersSuppliers'][$x]['store_id'], 
                                    'UsersSuppliers.section_id' => $this->request->data['UsersSuppliers'][$x]['section_id'],
                                    'UsersSuppliers.supplier_id' => $this->request->data['UsersSuppliers'][$x]['supplier_id']
                                ])
                                ->count();

                            //Ignorar los existentes
                            if($exist == 0){
                                $this->request->data['UsersSuppliers'][$x]['user_id'] = $user->id;
                                $this->request->data['UsersSuppliers'][$x]['enabled'] = 1; 
                            }
                        }

                        for($x=0; $x < count($this->request->data['UsersSuppliers']); $x++){

                            if(!isset($this->request->data['UsersSuppliers'][$x]['user_id'])){
                                unset($this->request->data['UsersSuppliers'][$x]);
                            }
                        }

                        $users_suppliers_table = TableRegistry::get('UsersSuppliers');
                        $entities = $users_suppliers_table->newEntities($this->request->data['UsersSuppliers']);
                        if(!$users_suppliers_table->saveMany($entities)){
                            $this->Flash->error(__('Error on save the suppliers associations'));

                            return $this->redirect(['action' => 'edit', $id]);
                        }
                        else{
                            for($x=0; $x < count($this->request->data['UsersSuppliers']); $x++){

                                $user_supplier = $users_suppliers_table->find()
                                    ->select('UsersSuppliers.id')
                                    ->where([
                                        'UsersSuppliers.user_id' => $this->request->data['UsersSuppliers'][$x]['user_id'],
                                        'UsersSuppliers.company_id' => $this->request->data['UsersSuppliers'][$x]['company_id'],
                                        'UsersSuppliers.store_id' => $this->request->data['UsersSuppliers'][$x]['store_id'],
                                        'UsersSuppliers.section_id' => $this->request->data['UsersSuppliers'][$x]['section_id'],
                                        'UsersSuppliers.supplier_id' => $this->request->data['UsersSuppliers'][$x]['supplier_id'],
                                    ])
                                    ->first();

                                if($user_supplier != null){

                                    //Association to assortment
                                    if(isset($this->request->data['UsersSuppliers'][$x]['assortmentReport'])){

                                        $this->loadModel('UsersSuppliersRobotReports');
                                        $this->loadModel('RobotReports');

                                        $robot_report = $this->RobotReports->find()
                                            ->select('RobotReports.id')
                                            ->where([
                                                'RobotReports.report_keyword' => $this->request->data['UsersSuppliers'][$x]['assortmentReport']
                                            ])
                                            ->first();

                                        if($robot_report != null){
                                            $user_supplier_robot_report = $this->UsersSuppliersRobotReports->newEntity();
                                            $user_supplier_robot_report->user_supplier_id = $user_supplier->id;
                                            $user_supplier_robot_report->robot_report_id = $robot_report->id;
                                            $user_supplier_robot_report->enabled = 1;

                                            $this->UsersSuppliersRobotReports->save($user_supplier_robot_report);
                                        }
                                    }

                                    //Association to price differences
                                    if(isset($this->request->data['UsersSuppliers'][$x]['priceDifferenceReport'])){
                                        $this->loadModel('UsersSuppliersRobotReports');
                                        $this->loadModel('RobotReports');

                                        $robot_report = $this->RobotReports->find()
                                            ->select('RobotReports.id')
                                            ->where([
                                                'RobotReports.report_keyword' => $this->request->data['UsersSuppliers'][$x]['priceDifferenceReport']
                                            ])
                                            ->first();

                                        if($robot_report != null){
                                            $user_supplier_robot_report = $this->UsersSuppliersRobotReports->newEntity();
                                            $user_supplier_robot_report->user_supplier_id = $user_supplier->id;
                                            $user_supplier_robot_report->robot_report_id = $robot_report->id;
                                            $user_supplier_robot_report->enabled = 1;

                                            $this->UsersSuppliersRobotReports->save($user_supplier_robot_report);
                                        }
                                    }

                                    //Association to assortment
                                    if(isset($this->request->data['UsersSuppliers'][$x]['stockOutReport'])){
                                        $this->loadModel('UsersSuppliersRobotReports');
                                        $this->loadModel('RobotReports');

                                        $robot_report = $this->RobotReports->find()
                                            ->select('RobotReports.id')
                                            ->where([
                                                'RobotReports.report_keyword' => $this->request->data['UsersSuppliers'][$x]['stockOutReport']
                                            ])
                                            ->first();

                                        if($robot_report != null){
                                            $user_supplier_robot_report = $this->UsersSuppliersRobotReports->newEntity();
                                            $user_supplier_robot_report->user_supplier_id = $user_supplier->id;
                                            $user_supplier_robot_report->robot_report_id = $robot_report->id;
                                            $user_supplier_robot_report->enabled = 1;

                                            $this->UsersSuppliersRobotReports->save($user_supplier_robot_report);
                                        }
                                    }
                                }
                            }   
                        }
                    }

                    //Iterar Newsletter Retailer
                    if(array_key_exists('UsersCompaniesRobotReports', $this->request->data) && count($this->request->data['UsersCompaniesRobotReports']) > 0){

                        for($x=0; $x < count($this->request->data['UsersCompaniesRobotReports']); $x++){

                            //Existe el registro
                            if(isset($this->request->data['UsersCompaniesRobotReports'][$x]['id'])){

                                $user_company_robot_report = $this->UsersCompaniesRobotReports->get($this->request->data['UsersCompaniesRobotReports'][$x]['id'], ['contain' => ['RobotReports']]);

                                if($user_company_robot_report != null && !isset($this->request->data['UsersCompaniesRobotReports'][$x][$user_company_robot_report->robot_report->report_keyword])){

                                    $this->UsersCompaniesRobotReports->delete($user_company_robot_report);

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
                                }
                            }
                        }
                    }

                    //Iterar Newsletter Proveedor
                    if(array_key_exists('UsersSuppliersRobotReports', $this->request->data) && count($this->request->data['UsersSuppliersRobotReports']) > 0){

                        for($x=0; $x < count($this->request->data['UsersSuppliersRobotReports']); $x++){

                            //Existe el registro
                            if(isset($this->request->data['UsersSuppliersRobotReports'][$x]['id'])){

                                $user_supplier_robot_report = $this->UsersSuppliersRobotReports->get($this->request->data['UsersSuppliersRobotReports'][$x]['id'], ['contain' => ['RobotReports']]);

                                if($user_supplier_robot_report != null && !isset($this->request->data['UsersSuppliersRobotReports'][$x][$user_supplier_robot_report->robot_report->report_keyword])){

                                    $this->UsersSuppliersRobotReports->delete($user_supplier_robot_report);

                                }
                            }
                            else{
                                //No existe el registro

                                $this->loadModel('RobotReports');

                                $robot_report = $this->RobotReports->find()
                                    ->where([
                                        'RobotReports.id' => $this->request->data['UsersSuppliersRobotReports'][$x]['robot_report_id']
                                    ])
                                    ->first();

                                if($robot_report != null && isset($this->request->data['UsersSuppliersRobotReports'][$x][$robot_report->report_keyword])){
                                    $user_supplier_robot_report = $this->UsersSuppliersRobotReports->newEntity();
                                    $user_supplier_robot_report->user_supplier_id = $this->request->data['UsersSuppliersRobotReports'][$x]['user_supplier_id'];
                                    $user_supplier_robot_report->robot_report_id = $robot_report->id;
                                    $user_supplier_robot_report->enabled = 1;

                                    $this->UsersSuppliersRobotReports->save($user_supplier_robot_report);
                                }
                            }
                        }
                    }

                    $this->Flash->success(__('User updated successful'));
                    return $this->redirect(['action' => 'index']);
                    
                }
                else{
                    $this->Flash->success(__('User updated successful'));

                    return $this->redirect(['action' => 'index']);
                }
            }
            else{
                $this->Flash->error(__('The user could not be saved. Please, try again.'));
                return $this->redirect(['action' => 'add']);
            }
        }

        
        $companies = $this->Users->Companies->find('list', ['limit' => 200]);
        $groups = $this->Users->Groups->find('list', ['limit' => 200]);
        $permissions = $this->Users->Permissions->find('list', ['limit' => 200]);

        $users_suppliers = $this->UsersSuppliers->find('all', ['contain' => ['UsersSuppliersRobotReports' => ['RobotReports'], 'Companies', 'Stores', 'Sections', 'Suppliers'], 'conditions' => ['UsersSuppliers.user_id' => $user->id]])->order(['UsersSuppliers.company_id' => 'ASC', 'UsersSuppliers.store_id' => 'ASC', 'UsersSuppliers.section_id' => 'ASC'])->toArray();

        $users_companies = $this->UsersCompanies->find('all', ['contain' => ['UsersCompaniesRobotReports' => ['RobotReports'], 'Companies', 'Stores', 'Sections'], 'conditions' => ['UsersCompanies.user_id' => $user->id]])->order(['UsersCompanies.company_id' => 'ASC', 'UsersCompanies.store_id' => 'ASC', 'UsersCompanies.section_id' => 'ASC'])->toArray();

        $users_robot_reports = $this->UsersRobotReports->find('all', ['conditions' => ['UsersRobotReports.user_id' => $user->id]])->toArray();

        $robot_reports = $this->Users->RobotReports->find('all', [
            ])
            ->toArray();

        $this->set(compact('user', 'companies', 'groups', 'permissions', 'users_suppliers', 'users_companies', 'robot_reports', 'users_robot_reports'));
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        if ($this->Users->delete($user)) {
            $this->Flash->success(__('The user has been deleted.'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function active($id = null)
    {
        if($id != null){
            $user = $this->Users->get($id);
            $user->active = 1;
            if ($this->Users->save($user)) {
                $this->Flash->success(__('User activated'));
            } else {
                $this->Flash->error(__('The user could not be activated. Please, try again.'));
            }
        }
        
        return $this->redirect(['action' => 'index']);
    }

    public function disable($id = null)
    {
        if($id != null){
            $user = $this->Users->get($id);
            $user->active = 0;
            if ($this->Users->save($user)) {
                $this->Flash->success(__('User disabled'));
            } else {
                $this->Flash->error(__('The user could not be activated. Please, try again.'));
            }
        }
        
        return $this->redirect(['action' => 'index']);
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

    function generateRandomCode($length = 10) {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
