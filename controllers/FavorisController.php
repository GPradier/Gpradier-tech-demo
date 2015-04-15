<?php
namespace app\controllers;
use app\models\Post;
use app\models\Favori;
use lithium\security\Auth;
use lithium\util\Set;

class FavorisController extends \lithium\action\Controller {

	public function index() {
		if(!$me = Auth::check('default')) {
			return $this->redirect(array('controller' => 'Sessions', 'action' => 'add', 'args' => base64_encode($this->request->url)));
		}
		$favoris = Favori::find('all', array('conditions' => array('user_id'=>$me['_id'])));
			//KO//$ids = $favoris->map(function($doc) {return ($doc->_id); });
		$productsId = Set::extract($favoris->data(), '/product_id');
		$productsall = Post::find('all', array('conditions' => array('_id' => $productsId)));
		$products = $productsall->to('array');
		
		return compact('favoris', 'products');
	}

	public function add() {
		$post = Post::find($this->request->id);

		if (empty($post)) {// No product => redirect index
			$this->redirect(array('controller' => 'posts', 'action' => 'index'));
		}
		
		$post = $post->to('array');
		
		if(!$me = Auth::check('default')) {// Non logué => redirect page produit ou connexion ?
			// to Page produit
			//$this->redirect(array('controller' => 'posts', 'action' => 'view', 'args' => $this->request->id));
			
			// to Login
			//return $this->redirect(array('controller' => 'Sessions', 'action' => 'add', 'args' => base64_encode($this->request->url)));
			
			// to Login then back to fav
			return $this->redirect(array('controller' => 'sessions', 'action' => 'add', 'args' => base64_encode($this->request->url)));
		}
		
		$favori = Favori::create();
		$favori->user_id = $me['_id'];
		$favori->product_id = $post['_id'];
		$favori->created = time();
		$favori->save();
		
		$this->redirect(array('controller' => 'posts', 'action' => 'view', 'args' => $this->request->id));
		
		return;
	}
	
	
	
	
	public function delete() {
		$post = Post::find($this->request->id);
	//        $post = Post::find('first', array($id));

		if (empty($post)) {
			$this->redirect(array('controller' => 'posts', 'action' => 'index'));
		}

		if ($post->delete()) {
			//$this->redirect(array('controller' => 'posts', 'action' => 'index'));
		} else {
			//
		}
		$this->redirect(array('controller' => 'posts', 'action' => 'index'));

		return false;
	}
	
	
}