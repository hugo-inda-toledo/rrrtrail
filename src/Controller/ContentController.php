<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Content Controller
 *
 *
 * @method \App\Model\Entity\Content[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ContentController extends AppController
{

    public function verifyRootRedirect()
    {
        if($this->request->session()->read('Auth') != null){

            if(count($this->request->session()->read('Auth.Companies')) > 0 || count($this->request->session()->read('Auth.Suppliers')) > 0){
                
                if(count($this->request->session()->read('Auth.Companies')) > 0){
                    return $this->redirect(['controller' => 'Stores', 'action' => 'map', 'plugin' => 'Retailers']);
                }

                if(count($this->request->session()->read('Auth.Suppliers')) > 0){
                    return $this->redirect(['controller' => 'Stores', 'action' => 'map', 'plugin' => 'Suppliers']);
                }   
            }
            else{
                $this->Flash->error(__('No data association'));
                return $this->redirect(['controller' => 'Users', 'action' => 'logout', 'plugin' => false]);
            }
        }
        else{
            $this->Flash->error(__('Please login to see the content'));
            return $this->redirect(['controller' => 'Users', 'action' => 'login', 'plugin' => false]);
        }
    }
}
