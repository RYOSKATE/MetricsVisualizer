<?php
App::uses('AppController', 'Controller');
/**
 * GroupNames Controller
 *
 * @property GroupName $GroupName
 * @property PaginatorComponent $Paginator
 */
class GroupNamesController extends AppController {
    public $uses = array('GroupName','Graph');

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->GroupName->recursive = 0;
		$this->set('groupNames', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->GroupName->exists($id)) {
			throw new NotFoundException(__('Invalid group name'));
		}
		$options = array('conditions' => array('GroupName.' . $this->GroupName->primaryKey => $id));
		$this->set('groupName', $this->GroupName->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->GroupName->create();
			if ($this->GroupName->save($this->request->data)) {
				$this->flashText(__('The group name has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->flashText(__('The group name could not be saved. Please, try again.'),false);
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
		if (!$this->GroupName->exists($id)) {
			throw new NotFoundException(__('Invalid group name'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->GroupName->save($this->request->data)) {
				$this->flashText(__('The group name has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->flashText(__('The group name could not be saved. Please, try again.'),false);
			}
		} else {
			$options = array('conditions' => array('GroupName.' . $this->GroupName->primaryKey => $id));
			$this->request->data = $this->GroupName->find('first', $options);
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
		$this->GroupName->id = $id;
		$name = $this->GroupName->find('list')[$id];
		if($this->Graph->hasAny(array('Graph.25'=>$name))){
			$this->Session->setFlash(__($name.'に依存したデータはまだ存在しています<button class="close" data-dismiss="alert">&times;</button>'), 'default', array('class'=> 'alert alert-danger alert-dismissable'));
		}else {
			if (!$this->GroupName->exists()) {
				throw new NotFoundException(__('Invalid group name'));
			}
			$this->request->allowMethod('post', 'delete');
			if ($this->GroupName->delete()) {
				$this->flashText(__($name.' を削除しました。'));
			} else {
				$this->flashText(__($name.' を削除できませんでした。'),false);
			}
		}
		return $this->redirect(array('action' => 'index'));
	}
}
