<?php
namespace app\controllers;
use app\models\User;
use app\models\Message;
use lithium\security\Auth;
use lithium\util\Set;

class MessagesController extends \lithium\action\Controller {
	
	public function index() {
		if(!$me = Auth::check('default'))
		{
			return $this->redirect(array('controller' => 'Sessions', 'action' => 'add', 'args' => base64_encode($this->request->url)));
		}

		$msgReceived = Message::find('all', array('conditions' => array('to_id' => $me['_id'])));
		$msgSent = Message::find('all', array('conditions' => array('from_id' => $me['_id'])));

		$usersIdTo = Set::extract($msgReceived->data(), '/from_id');
		$usersIdFrom = Set::extract($msgSent->data(), '/to_id');

		$usersRq = User::find('all', array('conditions' => array('_id' => array_unique(array_merge ($usersIdTo, $usersIdFrom)))));

		$users = array();
		foreach($usersRq->data() as $usr) {
			$users[$usr['_id']] = $usr;
		}
		// All Users in runtime();

		$title = 'Boite de récéption';
		return compact('title', 'msgReceived', 'msgSent', 'users');
	}

	public function add() {
		if(!$me = Auth::check('default'))
		{
			return $this->redirect(array('controller' => 'sessions', 'action' => 'add', 'args' => base64_encode($this->request->url)));
		}
		
		if ($this->request->data) {
			$post = Message::create();
			$post->from_id = $me['_id'];

			if ($post->save($this->request->data)) {
				$this->redirect(array('controller' => 'messages', 'action' => 'index'));
			}
		}

		//$usersRq = User::find('all');
		//$usersRq = User::find('all', array('conditions' => array('not' => array('username' => null))));
		$usersRq = User::find('all');
		$users = array();
		foreach($usersRq->data() as $usr) {
			$users[$usr['_id']] = $usr['firstname'].' '.$usr['lastname'] .' <'.$usr['username'].'>';
		}
		unset($users[$me['_id']]);

		$title = 'Nouveau message';
		return compact('title', 'users', 'me');
	}

}
?>