<?php

namespace Suppliers\Controller;

use App\Controller\AppController as BaseController;
use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\Core\Configure;

class AppController extends BaseController
{
	public function initialize()
    {
        parent::initialize();
		$this->viewBuilder()->setLayout('suppliers_layout');
	}

	public function beforeFilter(Event $event)
    {
        if($this->request->session()->read('Auth') != null)
        {
            $this->validateSupplierUser();
        }
    }

    function validateSupplierUser(){
    	if(count($this->request->session()->read('Auth.Suppliers')) == 0){
    		$this->Flash->warning(__("You don't access to suppliers area"));
    		$this->redirect('/');
    	}
    }
}
