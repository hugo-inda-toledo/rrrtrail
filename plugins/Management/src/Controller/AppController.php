<?php

namespace Management\Controller;

use App\Controller\AppController as BaseController;
use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\Core\Configure;

class AppController extends BaseController
{

	public function initialize()
    {
        parent::initialize();
		$this->viewBuilder()->setLayout('management');
	}

	public function beforeFilter(Event $event)
    {
        if($this->request->session()->read('Auth') != null)
        {
            $this->validateAdminUser();
        }
    }

    function validateAdminUser(){
    	if($this->request->session()->read('Auth.User.is_admin') == 0){
    		$this->Flash->warning(__("You don't access to management area"));
    		$this->redirect('/');
    	}
    }
}
