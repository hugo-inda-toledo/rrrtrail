<?php
namespace Management\Controller;

use Management\Controller\AppController;
use Cake\I18n\Time;
use Cake\Utility\Text;

class RobotSessionsController extends AppController
{

    public function initialize(){
        parent::initialize();
        $this->loadModel('RobotSessions');
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function onRealTime()
    {
        $now = New Time();
        $now->modify('-1 days');


        $robot_sessions = $this->RobotSessions->find()
            ->contain([
                'Stores' => [
                    'Companies',
                    'Locations' => [
                        'Countries',
                        'Regions',
                        'Communes'
                    ]
                ]
            ])
            ->where([
                'RobotSessions.is_test' => 0,
                'RobotSessions.session_date >' => $now->format('Y-m-d').' 22:00:00',
            ])
            ->order([
                'RobotSessions.price_differences_labels_finished_date' => 'ASC'
            ])
            ->toArray();

        $this->set(compact('robot_sessions'));
    }

    function ignoreSession(){
        if($this->request->is('post')){


            $robot_session = $this->RobotSessions->get($this->request->data['robot_session_id']);

            if($robot_session != null){

                switch ($this->request->data['type_report']) {
                    case 'assortmentReport':
                        $robot_session->assortment_ignore_session = 1;
                        break;

                    case 'priceDifferenceReport':
                        $robot_session->price_differences_ignore_session = 1;
                        break;

                    case 'stockOutReport':
                        $robot_session->facing_ignore_session = 1;
                        break;
                    
                    default:
                        die('bad request');
                        break;
                }

                if($this->RobotSessions->save($robot_session)){
                    $this->Flash->success(__('Session data updated'));
                }
                else{
                    $this->Flash->error(__('Information not updated'));
                }
            }
            else{
                $this->Flash->warning(__('No exist robot session'));
            }
        }
        else{
            $this->Flash->error(__('No post data'));
        }


        return $this->redirect(['controller' => 'RobotSessions', 'action' => 'onRealTime', 'plugin' => 'Management']);
    }

    function reactiveSession(){
        if($this->request->is('post')){


            $robot_session = $this->RobotSessions->get($this->request->data['robot_session_id']);

            if($robot_session != null){

                switch ($this->request->data['type_report']) {
                    case 'assortmentReport':
                        $robot_session->assortment_ignore_session = 0;
                        break;

                    case 'priceDifferenceReport':
                        $robot_session->price_differences_ignore_session = 0;
                        break;

                    case 'stockOutReport':
                        $robot_session->facing_ignore_session = 0;
                        break;
                    
                    default:
                        die('bad request');
                        break;
                }

                if($this->RobotSessions->save($robot_session)){
                    $this->Flash->success(__('Session data updated'));
                }
                else{
                    $this->Flash->error(__('Information not updated'));
                }
            }
            else{
                $this->Flash->warning(__('No exist robot session'));
            }
        }
        else{
            $this->Flash->error(__('No post data'));
        }


        return $this->redirect(['controller' => 'RobotSessions', 'action' => 'onRealTime', 'plugin' => 'Management']);
    }
}