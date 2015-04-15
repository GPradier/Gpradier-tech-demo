<?php
namespace app\controllers;

use app\models\Comment;
use app\models\Post;
use lithium\security\Auth;
use app\models\User;
use lithium\util\Set;

class CommentsController extends \lithium\action\Controller {
	public function index() {
		$page = 1;
		$limit = 100;
		$order = 'created desc';

		if (isset($this->request->params['page'])) {
			$page = $this->request->params['page'];

			if (!empty($this->request->params['limit'])) {
				$limit = $this->request->params['limit'];
			}
		}

		$offset = ($page - 1) * $limit;
		$total = Comment::find('count');
		$posts = Comment::find('all', compact('conditions', 'limit', 'offset', 'order'));
		
			// Get Users in runtime()
		$usersId = Set::extract($posts->data(), '/user_id');		
		$usersRq = User::find('all', array('conditions' => array('_id' => array_unique($usersId))));
		$users = array();
		foreach($usersRq->data() as $usr) {
			$users[$usr['_id']] = $usr;
		}
		
			// Get products in runtime()
		$productsId = Set::extract($posts->data(), '/post_id');
		$productsRq = Post::find('all', array('conditions' => array('_id' => array_unique($productsId))));
		$products = array();
		foreach($productsRq->data() as $product) {
			$products[$product['_id']] = $product;
		}

		// FOR DEMO :
		$comments = $posts->sort('user_id')->to('array');// Tri des commentaires par Utilisateur
		$commentsB = $posts->sort('post_id')->to('array');// Tri des commentaires par Produit
//		$comments = $posts->to('array');// Standard non trié

		$title = 'Commentaires';

		return compact('comments', 'commentsB', 'products', 'limit', 'page', 'total', 'title', 'users');
	}

	public function add() {
		if ($this->request->data) {
			$comment = Comment::create($this->request->data);

			if($me = Auth::check('default')) {
				$comment->user_id = $me['_id'];
				//$comment->created = date('Y-m-d h:i:s');
				$comment->created = time();
			}
			else {
				$this->redirect(array('controller' => 'posts', 'action' => 'index'));
			}

			if ($comment->save()) {
					$this->redirect(array('controller' => 'posts', 'action' => 'view', 'args' => array($this->request->data['post_id'])));
			}
			else {
				$id = $this->request->data['post_id'];
				$post = Post::find($id)->to('array');
				$comments = Comment::find('all', array('conditions' => array('post_id' => $id, 'status' => 'live')));

				$comments = ($comments->count()) ? $comments->to('array') : $comments;

				$title = $post['title'];

				$this->render(array('template' => '../posts/view', 'data' => compact('post', 'comment', 'comments', 'id', 'title')));

				return;
			}
		}
		else {
			$this->redirect(array('controller' => 'posts', 'action' => 'index'));
		}
	}
}