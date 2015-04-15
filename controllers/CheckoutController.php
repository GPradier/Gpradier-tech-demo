<?php
namespace app\controllers;
use app\models\Post;
use app\models\Checkout;
use lithium\security\Auth;
use lithium\storage\Session;

class CheckoutController extends \lithium\action\Controller {

	private function __getId() {
		/**
		 *	Gestion de l'ID pour le connecté & l'invité
		 */
		if($me = Auth::check('default')) {
			$userId = $me['_id'];
		}
		elseif(Session::read('userId')) {
			$userId = Session::read('userId');
		}
		else {
			$userId = uniqid();
			Session::write('userId', $userId);
		}
		return $userId;
	}

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
		$total = Checkout::find('count');
	
		$userId = self::__getId();
		$conditions = array('userId' => $userId);
	
		$checkouts = Checkout::find('all', compact('conditions', 'limit', 'offset', 'order'))->to('array');
		$posts = array();
		foreach($checkouts as $addone) {
			$post = Post::find('first', array('conditions' => array('_id' => $addone['product'])))->to('array');
			
			$posts[] = array(
				'title' => $post['title'],
				'image' => $post['image'],
				'price' => $post['price'],
				'ship' => $post['ship'],
				'_id' => $addone['_id'],
				'id_product' => $post['_id'],
			);
		}
		

		//$posts = Post::find('all', array('conditions' => array('_id' => $ids)))->to('array');
		
		$total_articles=0;$total_ship=0;
		foreach($posts as $p) {
			$total_articles += $p['price'];
			$total_ship += $p['ship'];
		}
		$total_both = $total_articles + $total_ship;

		$title = 'Panier';

		return compact('posts', 'limit', 'page', 'total', 'title', 'total_both', 'total_articles', 'total_ship');
	}

    public function add() {
		$userId = self::__getId();
	
		$new = Checkout::create(array(
			'product'=>$this->request->id,
			'userId' => $userId,
		));
		
		if ($new->save()) {
			$this->redirect(array('action' => 'index'));
		}
		return false;
    }

    public function delete() {
        $post = Checkout::find('first', array('conditions' => array('_id' => $this->request->id)));
        if (empty($post)) {
            $this->redirect(array('controller' => 'posts', 'action' => 'index'));
        }

        if ($post->delete()) {
            //$this->redirect(array('controller' => 'posts', 'action' => 'index'));
        } else {
            //
        }
        $this->redirect(array('controller' => 'checkout', 'action' => 'index'));

        return;
    }
}