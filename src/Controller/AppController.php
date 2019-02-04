<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\Core\Configure;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('RequestHandler');
        //$this->loadComponent('RequestHandler', ['viewClassMap' => ['xlsx' => 'Cewi/Excel.Excel']]);
        $this->loadComponent('Flash');
        $this->loadComponent('Auth', [
            'authError' => __('You must login to see the content'),
            'loginRedirect' => [
                'controller' => 'Stores',
                'action' => 'map'
            ],
            'logoutRedirect' => [
                'controller' => 'Users',
                'action' => 'login'
            ]
        ]);

        /*
         * Enable the following components for recommended CakePHP security settings.
         * see https://book.cakephp.org/3.0/en/controllers/components/security.html
         */
        //$this->loadComponent('Security');
        //$this->loadComponent('Csrf');
    }

    public function beforeFilter(Event $event)
    {
        //$this->Auth->allow(['login', 'view', 'display']);
        if (Configure::read('debug') == false) {
            if($this->request->session()->read('Auth') != null)
            {
                $this->recordLog();

            }
        }

        if($this->request->session()->read('Auth') != null){
            if($this->request->session()->read('Auth.User.password_changed') != 1){

                if($this->request->params['controller'] != 'Users' && ($this->request->params['action'] != 'passwordChange' || $this->request->params['action'] != 'logout' || $this->request->params['action'] != 'login')){
                    return $this->redirect(['controller' => 'Users', 'action' => 'passwordChange', $this->request->session()->read('Auth.User.password_token'), 'plugin' => false]);
                }
            }


            if(count($this->request->session()->read('Auth.Suppliers')) == 0 && count($this->request->session()->read('Auth.Companies')) == 0){
                $this->Flash->error(__('Error to login'));
                return $this->redirect(['controller' => 'Users', 'action' => 'logout', 'plugin' => false]);
            }
            
        }

        //$this->set('processing_robot_sessions', $this->gettingSessionsInProcess());
        $this->set('processing_robot_sessions', []);
    }


    public function recordLog()
    {
        $user = $this->request->session()->read('Auth');

        if (isset($user['User']['id']))
        {
            if($this->request->params['controller'] != 'logs'){
                $log_data = array(
                    'user_id' => $user['User']['id'],
                    'controller' => strtolower($this->request->params['controller']),
                    'action' => $this->request->params['action'],
                    'params' => (!empty($this->request->data)) ? json_encode($this->request->data) : null,
                    'plugin' => strtolower($this->request->params['plugin']),
                    'ip' => $this->request->clientIp(),
                    'metodo' => ($this->request->is('post')) ? 'post' : 'get'
                );

                $this->loadModel('Logs');
                $log = $this->Logs->newEntity();
                $log = $this->Logs->patchEntity($log, $log_data);
                $this->Logs->save($log);
            }
        }
    }

    public function gettingSessionsInProcess()
    {  
        $data = [];
        $user = $this->request->session()->read('Auth');

        if (isset($user['User']['id'])){

            $this->loadModel('RobotSessions');
            $processing_robot_sessions = $this->RobotSessions->find('all')
                ->contain(['Stores'])
                ->where([
                    'RobotSessions.labels_processing' => 1, 
                    'RobotSessions.labels_finished' => 0,
                    'RobotSessions.price_differences_labels_processing' => 1, 
                    'RobotSessions.price_differences_labels_finished' => 0,
                    'RobotSessions.facing_labels_processing' => 1, 
                    'RobotSessions.facing_labels_finished' => 0,
                ])
                ->toArray();

            if(count($processing_robot_sessions) > 0){
                $data = [];

                foreach($processing_robot_sessions as $robot_session){
                    $data[$robot_session->id]['object'] = $robot_session;
                    $data[$robot_session->id]['current_processing'] = $this->RobotSessions->Detections->find('all')->where(['Detections.robot_session_id' => $robot_session->id])->count();   
                }
            }
        }

        

        return $data;
    }
}
