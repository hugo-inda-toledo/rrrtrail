<?php
namespace Management\Controller;

use Management\Controller\AppController;

/**
 * Companies Controller
 *
 * @property \Management\Model\Table\CompaniesTable $Companies
 *
 * @method \Management\Model\Entity\Company[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CompaniesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $companies = $this->paginate($this->Companies);

        $this->set(compact('companies'));
    }

    /**
     * View method
     *
     * @param string|null $id Company id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {

        /*$limit = 10;
        $this->paginate = [
            'Companies' => [
                'contain' => [
                    'ProductsStores' => function ($q){ return $q->limit(10); },
                    'Categories' => function ($q){ return $q->limit(10); },
                    'SubCategories' => function ($q){ return $q->limit(10); },
                    'Suppliers',
                    'Users',
                    'Aisles',
                    'Sections',
                    'Stores',
                    'UsersSuppliers'
                ],
                'conditions' => [
                    'Companies.id' => $id
                ]
            ]
        ];
        $company = $this->paginate($this->Companies);
        $this->set('company', $company);
        $this->set('_serialize', ['company']);*/

        $this->loadModel('Companies');

        $company = $this->Companies->get($id, [
            'contain' => [
                //'ProductsStores' => function ($q){ return $q->limit(10); },
                //'Categories' => function ($q){ return $q->limit(10); },
                //'SubCategories' => function ($q){ return $q->limit(10); },
                'Suppliers',
                'Sections' =>[
                    'Categories' => [
                        'SubCategories'
                    ]
                ],
                'Stores' =>[
                    'Locations' => [
                        'Countries',
                        'Regions',
                        'Communes'
                    ]
                ]
            ]
        ]);

        $this->set('company', $company);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $company = $this->Companies->newEntity();
        if ($this->request->is('post')) {
            $company = $this->Companies->patchEntity($company, $this->request->getData());
            if ($this->Companies->save($company)) {
                $this->Flash->success(__('The company has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The company could not be saved. Please, try again.'));
        }
        $suppliers = $this->Companies->Suppliers->find('list', ['limit' => 200]);
        $users = $this->Companies->Users->find('list', ['limit' => 200]);
        $this->set(compact('company', 'suppliers', 'users'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Company id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $company = $this->Companies->get($id, [
            'contain' => ['Suppliers', 'Users']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $company = $this->Companies->patchEntity($company, $this->request->getData());
            if ($this->Companies->save($company)) {
                $this->Flash->success(__('The company has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The company could not be saved. Please, try again.'));
        }
        $suppliers = $this->Companies->Suppliers->find('list', ['limit' => 200]);
        $users = $this->Companies->Users->find('list', ['limit' => 200]);
        $this->set(compact('company', 'suppliers', 'users'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Company id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $company = $this->Companies->get($id);
        if ($this->Companies->delete($company)) {
            $this->Flash->success(__('The company has been deleted.'));
        } else {
            $this->Flash->error(__('The company could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
