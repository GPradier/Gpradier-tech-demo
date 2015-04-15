<?php
namespace app\controllers;

use lithium\security\Auth;

class SessionsController extends \lithium\action\Controller {
	public function add($redirect = null) {
		if ($this->request->data && Auth::check('default', $this->request)) {
			if($redirect) {
				return $this->redirect(base64_decode($redirect));
			}
			return $this->redirect('/');
		}
		// Handle failed authentication attempts
		return $this->render(array('template' => 'add', 'layout' => 'light'));
	}
	
	public function delete() {
		Auth::clear('default');
		return $this->redirect('/');
	}
	
	public function mobile() {
		$success = false;
		if($user = Auth::check('default')) {
			$datas = array('errorcode' => '20', 'message' => 'Already connected');
			return $this->render(array('type' => 'json', 'data' => compact('success', 'datas')));
		}
		
		if(!$this->request->data) {
			$datas = array('errorcode' => '21', 'message' => 'No user login sent');
			return $this->render(array('type' => 'json', 'data' => compact('success', 'datas')));
		}
		
		if ($this->request->data && $user = Auth::check('default', $this->request)) {
			$success = true;
			$datas = $user;

			return $this->render(array('type' => 'json', 'data' => compact('success', 'datas')));
		}

		$datas = array('errorcode' => '27', 'message' => 'Nom d\'utilisateur ou mot de passe invalide');
		return $this->render(array('type' => 'json', 'data' => compact('success', 'datas')));
	}
}
?>