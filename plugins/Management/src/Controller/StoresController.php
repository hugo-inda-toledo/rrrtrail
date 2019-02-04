<?php
namespace Management\Controller;

use Management\Controller\AppController;
use Cake\Event\Event;

/**
 * Stores Controller
 *
 * @property \Management\Model\Table\StoresTable $Stores
 *
 * @method \Management\Model\Entity\Store[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class StoresController extends AppController
{
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        $this->loadModel('Stores');
    }
    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Companies', 'Locations']
        ];
        $stores = $this->paginate($this->Stores);

        $this->set(compact('stores'));
    }

    /**
     * View method
     *
     * @param string|null $id Store id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $store = $this->Stores->get($id, [
            'contain' => ['Companies', 'Locations', 'Products', 'Aisles', 'UsersCompanies', 'UsersSuppliers']
        ]);

        $this->set('store', $store);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $store = $this->Stores->newEntity();
        if ($this->request->is('post')) {

            $location = $this->Stores->Locations->newEntity();
            $location = $this->Stores->Locations->patchEntity($location, $this->request->data['Location']);

            //Commune Name for string GMaps
            $commune_data = $this->Stores->Locations->Communes->find('all')->select(['Communes.commune_name'])->where(['Communes.id' => $location->commune_id])->first();

            //Region Name for string GMaps
            $region_data = $this->Stores->Locations->Regions->find('all')->select(['Regions.region_name'])->where(['Regions.id' => $location->region_id])->first();

            //Country Name for string GMaps
            $country_data = $this->Stores->Locations->Countries->find('all')->select(['Countries.country_name'])->where(['Countries.id' => $location->country_id])->first();

            $address = $location->street_name." ".$location->street_number.", ".$commune_data->commune_name.", ".$region_data->region_name.", ".$country_data->country_name;

            $address = str_replace(" ", "+", $address);

            $json = file_get_contents("http://maps.googleapis.com/maps/api/geocode/json?address=".$address."&sensor=false");
            $json = json_decode($json, true);

            $location->latitude = isset($json['results'][0]['geometry']['location']['lat']) ? $json['results'][0]['geometry']['location']['lat'] : null;
            $location->longitude = isset($json['results'][0]['geometry']['location']['lng']) ? $json['results'][0]['geometry']['location']['lng'] : null;
            $location->enabled = 1;

            if($this->Stores->Locations->save($location)) {

                $store = $this->Stores->patchEntity($store, $this->request->data['Store']);
                $store->active = 1;
                $store->location_id = $location->id;

                if ($this->Stores->save($store)) {
                    $this->Flash->success(__('The store has been saved.'));

                    return $this->redirect(['action' => 'index']);
                }
                else{
                    $this->Stores->Locations->delete($location);
                    $this->Flash->error(__('The store could not be saved. Please, try again.'));

                    return $this->redirect(['action' => 'add']);
                }
            }
            else{
                $this->Flash->error(__('The location could not be saved. Please, try again.'));
                return $this->redirect(['action' => 'add']);
            }
        }
        
        $companies = $this->Stores->Companies->find('list');
        $locations = $this->Stores->Locations->find('list');
        $countries = $this->Stores->Locations->Countries->find('list');
        $this->set(compact('store', 'companies', 'locations', 'products', 'countries'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Store id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $store = $this->Stores->get($id, [
            'contain' => ['Products']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $store = $this->Stores->patchEntity($store, $this->request->getData());
            if ($this->Stores->save($store)) {
                $this->Flash->success(__('The store has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The store could not be saved. Please, try again.'));
        }
        $companies = $this->Stores->Companies->find('list', ['limit' => 200]);
        $locations = $this->Stores->Locations->find('list', ['limit' => 200]);
        $products = $this->Stores->Products->find('list', ['limit' => 200]);
        $this->set(compact('store', 'companies', 'locations', 'products'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Store id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $store = $this->Stores->get($id);
        if ($this->Stores->delete($store)) {
            $this->Flash->success(__('The store has been deleted.'));
        } else {
            $this->Flash->error(__('The store could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Enable method
     *
     * @param string|null $id Store id.
     * @return \Cake\Http\Response|null Redirects to referer.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function enable($id = null)
    {
        if($id == null){
            $this->Flash->warning(__('Invalid params'));
            return $this->redirect($this->referer());
        }

        $store = $this->Stores->get($id);
        $store->active = 1;
        if ($this->Stores->save($store)) {
            $this->Flash->success(__('The store has been enabled.'));
        } else {
            $this->Flash->error(__('The store could not be enabled. Please, try again.'));
        }

        return $this->redirect($this->referer());
    }

    /**
     * Enable method
     *
     * @param string|null $id Store id.
     * @return \Cake\Http\Response|null Redirects to referer.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function disable($id = null)
    {
        if($id == null){
            $this->Flash->warning(__('Invalid params'));
            return $this->redirect($this->referer());
        }

        $store = $this->Stores->get($id);
        $store->active = 0;
        if ($this->Stores->save($store)) {
            $this->Flash->success(__('The store has been disabled.'));
        } else {
            $this->Flash->error(__('The store could not be disabled. Please, try again.'));
        }

        return $this->redirect($this->referer());
    }
}
