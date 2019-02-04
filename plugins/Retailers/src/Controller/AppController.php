<?php

namespace Retailers\Controller;

use App\Controller\AppController as BaseController;
use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\Core\Configure;

class AppController extends BaseController
{
	public function initialize()
    {
        parent::initialize();
		$this->viewBuilder()->setLayout('retailers_layout');
	}

	public function beforeFilter(Event $event)
    {
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


            $this->validateRetailerUser();
        }
    }

    function validateRetailerUser(){
    	if(count($this->request->session()->read('Auth.Companies')) == 0){
    		$this->Flash->warning(__("You don't access to retailer area"));
    		$this->redirect('/');
    	}
    }
}
