<?php
namespace app\controllers;

use lithium\security\Auth;
use app\models\User;
use app\models\Post;

class ScanController extends \lithium\action\Controller {

	public function index() {
		if(!$me = Auth::check('default')) {
			return $this->redirect(array('controller' => 'sessions', 'action' => 'add', 'args' => base64_encode($this->request->url)));
		}

		$title = 'Scanner un objet';
		return compact('title');
	}
	
	public function check($barcode) {
		if(!$me = Auth::check('default')) {
			return $this->redirect(array('controller' => 'sessions', 'action' => 'add', 'args' => base64_encode($this->request->url)));
		}
		
		$post = Post::find('first', array('conditions' => array('barcode' => $barcode)));
		
		if(empty($post)) {
			return $this->redirect('Posts::add');// non trouvé, nouvel objet
		}
		
		$post = $post->to('array');
		
		return $this->redirect(array('controller' => 'posts', 'action' => 'view', 'args' => $post['_id']));

		/*
		$title = 'Analyse de l\'objet';
		return compact('title');
		*/
		
	}
}

?>