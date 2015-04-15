<?php
namespace app\controllers;

use lithium\security\Auth;
use app\models\Post;

use app\models\Story;
use app\models\Comment;
use app\models\User;
use app\models\File;
use lithium\util\Set;

use lithium\net\http\Media;//
use lithium\action\Request;

class FavoritesController extends \lithium\action\Controller {
	public function index() {
		$success = false;
		$datas = array('errorcode' => '00', 'message' => 'No action selected.');
		return $this->render(array('type' => 'json', 'data' => compact('success', 'datas')));
	}
	
	public function details() {
		$success = false;
		$post = Post::find($this->request->id);
		
		if (!$post) {
			$datas = array('errorcode' => '27', 'message' => 'Invalid object, no detail');
			return $this->render(array('type' => 'json', 'data' => compact('success', 'datas')));
		}
		/*
		if(!($me = Auth::check('default'))) {
			$datas = array('errorcode' => '20', 'message' => 'Already connected');
			return $this->render(array('type' => 'json', 'data' => compact('success', 'datas')));
		}
		*/
		$me = Auth::check('default');
		
		$post->user = User::find('first', array('conditions' => array('_id' => $post->user_id), 'fields' => array('username', 'firstname', 'lastname', 'location', 'avatar')));
		
		$docs = File::find('all', array('conditions' => array('related' => $post->_id)));
		($docs ? $docs = $docs->to('array') : '');
		$post = $post->to('array');
		
		$title = $post['title'];
		$me = Auth::check('default');
		
		
		
	
		$comments = Comment::find('all', array('conditions' => array('post_id' => $this->request->id))); // Les commentaires sur CE produit

		$usersId = Set::extract($comments->data(), '/user_id');
		$users = User::find('all', array('conditions' => array('_id' => $usersId), 'fields' => array('username', 'firstname', 'lastname', 'location', 'avatar')));

		$bourrin = $comments->map(function($post) {
			$user = User::find('first', array('conditions' => array('_id' => $post['user_id']), 'fields' => array('username', 'firstname', 'lastname', 'avatar')));
			$post->user = $user;
			return $post;
		});
		
		$comments = $bourrin;
		$comments = ($comments->count()) ? $comments->to('array') : false;
		$comment = Comment::create();
		
		
		$storys = Story::find('all', array('conditions' => array('related' => $post['_id']), 'order' =>  array('order' => 'ASC')));
		$storys = ($storys ? $storys->to('array') : null);

		//
		$success = true;
		$datas = compact('post', 'title', 'comment', 'comments', 'users', 'me', 'storys', 'docs');
		return $this->render(array('type' => 'json', 'data' => compact('success', 'datas')));
		
		//return compact('post', 'title', 'comment', 'comments', 'users', 'me', 'storys', 'docs');
	}
	
	
	public function add() {
		$success = false;
		$post = Post::find($this->request->id);

		if (!$post) {
			$datas = array('errorcode' => '27', 'message' => 'Invalid object, no detail');
			return $this->render(array('type' => 'json', 'data' => compact('success', 'datas')));
		}
		
		$post = $post->to('array');
		
		if(!$me = Auth::check('default')) {// Non logué => redirect page produit ou connexion ?
			$datas = array('errorcode' => '20', 'message' => 'No user connected');
			return $this->render(array('type' => 'json', 'data' => compact('success', 'datas')));
		}
		
		$favori = Favori::create();
		$favori->user_id = $me['_id'];
		$favori->product_id = $post['_id'];
		$favori->created = time();
		$favori->save();
		
		$success = true;
		$id = $this->request->id;
		$datas = compact('id', 'favori');
		return $this->render(array('type' => 'json', 'data' => compact('success', 'datas')));
	}
	
	public function all() {
		if(!$me = Auth::check('default')) {// Non logué => redirect page produit ou connexion ?
			$datas = array('errorcode' => '20', 'message' => 'No user connected');
			return $this->render(array('type' => 'json', 'data' => compact('success', 'datas')));
		}
		
		$favori = Favori::find('all', array('conditions' => array('user_id' => $me['_id'])));
		
		$success = true;
		$datas = $favori->to('array');
		return $this->render(array('type' => 'json', 'data' => compact('success', 'datas')));
	}
}
?>