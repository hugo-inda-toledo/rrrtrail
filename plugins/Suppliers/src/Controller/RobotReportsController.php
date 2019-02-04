<?php
namespace Suppliers\Controller;

use Suppliers\Controller\AppController;

/**
 * RobotReports Controller
 *
 *
 * @method \Suppliers\Model\Entity\RobotReport[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class RobotReportsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $robotReports = $this->paginate($this->RobotReports);

        $this->set(compact('robotReports'));
    }

    /**
     * View method
     *
     * @param string|null $id Robot Report id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $robotReport = $this->RobotReports->get($id, [
            'contain' => []
        ]);

        $this->set('robotReport', $robotReport);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $robotReport = $this->RobotReports->newEntity();
        if ($this->request->is('post')) {
            $robotReport = $this->RobotReports->patchEntity($robotReport, $this->request->getData());
            if ($this->RobotReports->save($robotReport)) {
                $this->Flash->success(__('The robot report has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The robot report could not be saved. Please, try again.'));
        }
        $this->set(compact('robotReport'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Robot Report id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $robotReport = $this->RobotReports->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $robotReport = $this->RobotReports->patchEntity($robotReport, $this->request->getData());
            if ($this->RobotReports->save($robotReport)) {
                $this->Flash->success(__('The robot report has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The robot report could not be saved. Please, try again.'));
        }
        $this->set(compact('robotReport'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Robot Report id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $robotReport = $this->RobotReports->get($id);
        if ($this->RobotReports->delete($robotReport)) {
            $this->Flash->success(__('The robot report has been deleted.'));
        } else {
            $this->Flash->error(__('The robot report could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
