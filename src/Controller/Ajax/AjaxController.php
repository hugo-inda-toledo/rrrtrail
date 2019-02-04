<?php
namespace App\Controller\Ajax;

use Cake\ORM\TableRegistry;
use Cake\Network\Exception\MethodNotAllowedException;

use App\Controller\AppController;

class AjaxController extends AppController
{
    /**
     * Constructor
     *
     * @return void
     **/
    public function initialize()
    {
        if ( ! $this->request->is('ajax')) {
            throw new MethodNotAllowedException();
        }

        parent::initialize();

        $this->viewBuilder()->layout('ajax');
    }
}