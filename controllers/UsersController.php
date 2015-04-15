<?php

namespace app\controllers;

use lithium\security\Auth;
use app\models\User;

class UsersController extends \lithium\action\Controller {

	public function index() {
		$users = User::all();
		return compact('users');
	}

	public function add() {
		if(Auth::check('default')) {
			return $this->redirect('Pages::home');
		}
		
		$user = User::create($this->request->data);

		if (($this->request->data) && $user->save()) {
			Auth::check('default', $this->request);// Auto-login
			return $this->redirect('Pages::home');// Go to HOME
		}
		$data = compact('user');
		return $this->render(array('data' => $data, 'layout' => 'light'));
	}
	
	public function profile(){
		if(!$me = Auth::check('default')) {
			return $this->redirect(array('controller' => 'sessions', 'action' => 'add', 'args' => base64_encode($this->request->url)));
		}
		$me = User::find('first', array('conditions' => array('_id' => $me['_id'])));
		
		$title = 'Mon compte';
		return compact('me', 'title');
	}
	
	public function getlog(){
		$me = Auth::check('default');
		return compact('me');
	}
	
	
	public function edit(){
		if(!$me = Auth::check('default')) {
			return $this->redirect(array('controller' => 'sessions', 'action' => 'add', 'args' => base64_encode($this->request->url)));
		}
		
		$user = User::find('first', array('conditions' => array('_id' => $me['_id'])));
		
		if ($this->request->data) {
			$success = User::update($this->request->data, array('_id' => $user->_id));
			if($success) {
				return $this->redirect(array('controller' => 'users', 'action' => 'profile'));
			}
		}
		
		/*
		$user = User::find('first', array('conditions' => array('_id' => $me['_id'])));
		
		if ($this->request->data) {
			if ($user->save($this->request->data)) {
				$this->redirect(array('controller' => 'users', 'action' => 'profile'));
			}
		}
		*/
		$title = 'Modifier mes informations';
		return compact('user', 'title');
	}
	
	private function delete(){
		// TODO Admin
	}
}

?>