<?php
App::uses('AppController', 'Controller');
/**
 * Organizations Controller
 *
 * @property Organization $Organization
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class OrganizationsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'Session');

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Organization->recursive = 0;
		$this->set('organizations', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Organization->exists($id)) {
			throw new NotFoundException(__('Invalid organization'));
		}
		$options = array('conditions' => array('Organization.' . $this->Organization->primaryKey => $id));
		$this->set('organization', $this->Organization->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
            $fileName = $this->data['Organization']['selectCSV']['name'];//data_10_utf.csv
            $ret;
            if(empty($fileName))
            {
                $this->Organization->create();
                $ret = $this->Organization->save($this->request->data);
            }
            else
            {
                $tmp_file_file = $this->data['Organization']['selectCSV']['tmp_name'];
                $ret = $this->Organization->uploadFromCSV($tmp_file_file);
            }
            if ($ret) {
				$this->Session->setFlash(__('The organization has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The organization could not be saved. Please, try again.'));
			}
		}
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Organization->exists($id)) {
			throw new NotFoundException(__('Invalid organization'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Organization->save($this->request->data)) {
				$this->Session->setFlash(__('The organization has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The organization could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Organization.' . $this->Organization->primaryKey => $id));
			$this->request->data = $this->Organization->find('first', $options);
		}
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Organization->id = $id;
		if (!$this->Organization->exists()) {
			throw new NotFoundException(__('Invalid organization'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Organization->delete()) {
			$this->Session->setFlash(__('The organization has been deleted.'));
		} else {
			$this->Session->setFlash(__('The organization could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}