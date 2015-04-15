<?php
namespace app\controllers;
use app\models\Post;
use app\models\Story;
use app\models\Comment;
use app\models\User;
use app\models\File;
use lithium\security\Auth;
use lithium\util\Set;

class PostsController extends \lithium\action\Controller {

	public function index() {
		$page = 1;
		$limit = 20;
		$order = array('_id' => 'DESC');// ou /created

		if (isset($this->request->params['page'])) {
			$page = $this->request->params['page'];

			if (!empty($this->request->params['limit'])) {
				$limit = $this->request->params['limit'];
			}
		}

		$offset = ($page - 1) * $limit;
		$total = Post::find('count');
		
		$conditions = array('valid' => true);

		$postsObj = Post::find('all', compact('conditions', 'limit', 'offset', 'order'));

		$usersId = Set::extract($postsObj->data(), '/user_id');
		$users = User::find('all', array('conditions' => array('_id' => $usersId), 'fields' => array('username', 'firstname', 'lastname', 'location', 'avatar')));
		
		$posts = $postsObj->map(function($post) {
			$user = User::find('first', array('conditions' => array('_id' => $post['user_id']), 'fields' => array('username', 'firstname', 'lastname','location', 'avatar')));
			$post->user = $user;
			return $post;
		});
		
		$posts = $posts->to('array');
		
		
		

		$title = 'Home';

		return compact('posts', 'limit', 'page', 'total', 'title');
	}
	
	public function boutique() {		
		$posts = Post::find('all', array('conditions' => array('user_id' => $this->request->id)))->to('array');
		$vendor = User::find('first', array('conditions' => array('_id' => $this->request->id)))->to('array');
		$title = 'Boutique de '. $vendor['firstname'] .' '. $vendor['lastname'];

		return compact('posts', 'vendor', 'title');
	}
	
	public function seller($admin = null) {
		if(!$me = Auth::check('default')) {
			$this->redirect(array('action' => 'index'));
		}

		// Defaults values Pagination
		$page = 1;
		$limit = 50;
		$sort = '_id';
		$orderby = 'DESC';

		if (isset($this->request->query['page']) && !empty($this->request->query['page'])) {
			$page = $this->request->query['page'];}
		if (isset($this->request->query['limit']) && !empty($this->request->query['limit'])) {
			$limit = $this->request->query['limit'];}		
		if (!empty($this->request->query['sort']) && !empty($this->request->query['orderby'])) {
			$sort = $this->request->query['sort'];
			$orderby = $this->request->query['orderby'];
		}

		$order = array($sort => $orderby);

		// Final Pagination
		$offset = ($page - 1) * $limit;

		$conditions = array('user_id' => $me['_id']);
		if($admin == 'admin') {$conditions = array();}

		$total = Post::find('count', compact('conditions'));
		$posts = Post::find('all', compact('conditions', 'limit', 'offset', 'order'))->to('array');

		$title = 'Espace Créateurs - Tableau de bord';

		return compact('posts', 'limit', 'page', 'total', 'title', 'orderby', 'sort', 'admin');
	}

	public function view() {
		$post = Post::find($this->request->id);
		
		if (!$post) {
			throw new \Exception ('Invalid post if provided');
		}
		
		$post->user = User::find('first', array('conditions' => array('_id' => $post->user_id), 'fields' => array('username', 'firstname', 'lastname', 'location', 'avatar')));
		
		$post = $post->to('array');
		$title = $post['title'];

		$me = Auth::check('default');
		
		
		
	
		//$comments = Comment::find('all', array('conditions' => array('post_id' => $this->request->id, 'status' => 'live')));
		$comments = Comment::find('all', array('conditions' => array('post_id' => $this->request->id))); // Les commentaires sur CE produit

		//$usersId = Set::extract('/posts/user_id', $comments->data());
		$usersId = Set::extract($comments->data(), '/user_id');
		$users = User::find('all', array('conditions' => array('_id' => $usersId), 'fields' => array('username', 'firstname', 'lastname')));
		
//		var_dump($comments);

		// Ce que je ne veux "PAS" faire ...
		$bourrin = $comments->map(function($post) {
			$user = User::find('first', array('conditions' => array('_id' => $post['user_id']), 'fields' => array('username', 'firstname', 'lastname')));
			$post->user = $user;
			return $post;
		});
		
		$comments = $bourrin;
		
//		var_dump($bourrin->to('array'));
//		exit();
		
		
		/*
		
		$usertemp = array();
		foreach($users as $user) {
			echo $user['_id'];
			$usertemp[$user['_id']] = '55';

			$usertemp[$user['_id']] = $user;
			var_dump($usertemp);
		}
		foreach($comments as $a => $comm) {
			$comments->{$a} = 'hihi';
		}
			
			var_dump($comm['user_id']);
			
			var_dump($users->data());
			
			//$simpleUser = Set::extract($users->data(), '/_id='.$comm['user_id']);
			$simpleUser = Set::extract($users->data(), '/.[1]');
			var_dump($simpleUser);
			exit();

		//}
		
		*/
		
		/*
		echo '________________________________________';
		
		var_dump(Set::combine($comments->data(), $users->data(), '/user_id', '_id'));
		
		*/
		
		$comments = ($comments->count()) ? $comments->to('array') : false;

		$comment = Comment::create();

		return compact('post', 'title', 'comment', 'comments', 'users', 'me');
	}
	
	public function story() {
		$post = Post::find($this->request->id);

		if (!$post) {
			throw new \Exception ('Invalid post if provided');
		}
		
		if($this->request->data) {//new story element
			$story = Story::create($this->request->data);
			$story->idProduct = $this->request->id;
			$story->order = 99;// pas classé encore
			if(!$story->save()) {
				throw new \Exception ('Invalid new story added');
			}
		}

		$post = $post->to('array');
		$storys = Story::find('all', array('conditions' => array('idProduct' => $this->request->id), 'order' =>  array('order' => 'ASC')));
//		var_dump($storys->to('array'));
		$title = 'Ecrire l\'histoire pour '. $post['title'];

		return compact('post', 'title');
	}

	public function sortable() {
		$post = Post::find($this->request->id);

		if (!$post) {
			throw new \Exception ('Invalid post if provided');
		}
		$post = $post->to('array');
		
		$storys = Story::find('all', array('conditions' => array('idProduct' => $this->request->id), 'order' =>  array('order' => 'ASC')));
		if($storys) {
			$storys = $storys->to('array');
		}

		$title = 'Gérer les éléments de  l\'histoire pour '. $post['title'];

		return compact('post', 'title', 'storys');
	}

	public function add() {
		if(!$me = Auth::check('default')) {
			$this->redirect(array('action' => 'index'));
		}

		if ($this->request->data) {
			// Create a post object and add the posted data to it
			$post = Post::create($this->request->data);
			$post->user_id = $me['_id'];
			$post->created = time();
			$post->valid = false;
			
			if ($post->save()) {
				$is_upload = self::upload($this->request->data['upload'], $post);			
				if($is_upload) {
					$post->upload = null;
					$post->save();
				}
				//$this->redirect(array('action' => 'index'));
				$this->redirect(array('action' => 'edit', 'args' => $post->_id));
			}
		}

		if (empty($post)) {
			// Create an empty post object
			$post = Post::create();
		}

		$title = 'Ajouter un produit';
		return compact('post', 'title');
	}
	
	public static $upload_repertory = '/upload_favstory/';
	public function upload($upload, $related) {
		$size = $upload['size'];
		$maxSize = 8388608;
		
		$extension = end(explode(".", $upload['name']));
		$allowedExtensions = array("txt","csv","htm","html","xml",
			"css","doc","docx","xls","rtf","ppt","pdf","jpg","jpeg","gif","png");
		
		$type = $upload['type'];
		$allowedTypes = array(
			"application/pdf",
			"application/msword",
			"application/vnd.oasis.opendocument.text",
			"application/vnd.openxmlformats-officedocument.wordprocessingml.document",
			"image/gif", 
			"image/jpeg",
			"image/png",
			"text/html",
			"application/xml",
			"text/xml"
		);
		
		if(in_array($extension, $allowedExtensions) && in_array($type, $allowedTypes) && $size < $maxSize) {
			if ($_FILES['upload']['error'] == 0) {
				$tmp_name = $upload['tmp_name'];
				$real_name = $upload['name'];
				
				$file = File::create();
				$file->realname = $real_name;
				$file->extension = $extension;
				$file->size = $size;
				$file->type = $type;
				$file->related = $related->_id;
				$file->user = $related->user_id;
				$file->created_at = time();
				$file->unique_name = sha1($user->_id .'-'. $file->_id .'-'. time());
				
				if(move_uploaded_file($tmp_name, self::$upload_repertory . $file->unique_name .'.'. $extension))
				{
					$file->save();
					return true;
				}
				else{
					//
				}
			}
		}
	}
	
	/*
	public function finish(){
		$all = Post::find('all');
		foreach($all as $a) {
			$a->valid = true;
			$a->save();
		}
	}
	*/

	public function edit() {
		if(!$me = Auth::check('default')) {
			return $this->redirect(array('controller' => 'Sessions', 'action' => 'add', 'args' => base64_encode($this->request->url)));
		}

		$post = Post::find('first', array('conditions' => array('_id' => $this->request->id, 'user_id' => $me['_id'])));
		$docs = File::find('all', array('conditions' => array('user' => $me['_id'], 'related' => $post->_id)));
		$allimages = File::find('all', array('conditions' => array('user' => $me['_id'])));
		
		
		if (empty($post)) {
			$this->redirect(array('controller' => 'posts', 'action' => 'index'));
		}

		if ($this->request->data) {
			if ($post->save($this->request->data)) {
				//$this->redirect(array('controller' => 'posts', 'action' => 'index'));
				
				$is_upload = self::upload($this->request->data['upload'], $post);			
				if($is_upload) {
					$post->upload = null;
					$post->save();
				}
				
				$this->redirect(array('action' => 'edit', 'args' => $post->_id));
			}
		}

		$title = 'Modifier '.$post->title;

		return compact('post', 'docs', 'title', 'allimages');
	}
	
	public function scan() {
		if(!$me = Auth::check('default')) {
			return $this->redirect(array('controller' => 'Sessions', 'action' => 'add', 'args' => base64_encode($this->request->url)));
		}
		$post = Post::find('first', array('conditions' => array('_id' => $this->request->id, 'user_id' => $me['_id'])));
		
		if (empty($post)) {
			$this->redirect(array('controller' => 'posts', 'action' => 'index'));
		}

		if ($this->request->data) {
			if ($post->save($this->request->data)) {
				$this->redirect(array('controller' => 'posts', 'action' => 'index'));
			}
		}

		$title = 'Scanner '.$post->title;

		return compact('post', 'title');
	}
	
	public function favori() {
		$post = Post::find($this->request->id);

		if (empty($post)) {// No product => redirect index
			$this->redirect(array('controller' => 'posts', 'action' => 'index'));
		}
		
		$post = $post->to('array');
		
		if(!$me = Auth::check('default')) {// Non logué => redirect page produit ou connexion ?
			// to Page produit
			$this->redirect(array('controller' => 'posts', 'action' => 'view', 'args' => $this->request->id));
			
			// to Login
			//return $this->redirect(array('controller' => 'Sessions', 'action' => 'add', 'args' => base64_encode($this->request->url)));
		}
		
		$favori = Favori::create();
		$favori->user_id = $me['_id'];
		$favori->post_id = $post['_id'];
		$favori->created = time();
		$favori->save();
		
		$this->redirect(array('controller' => 'posts', 'action' => 'view', 'args' => $this->request->id));
		
		return;
	}
	
	public function copy() {
		if(!$me = Auth::check('default')) {
			return $this->redirect(array('controller' => 'Sessions', 'action' => 'add', 'args' => base64_encode($this->request->url)));
		}

		$post = Post::find('first', array('conditions' => array('_id' => $this->request->id, 'user_id' => $me['_id'])));
		if (empty($post)) {
			$this->redirect(array('controller' => 'posts', 'action' => 'seller'), array('exit' => true));// EXIT
		}

		$post = $post->to('array');
		unset($post['_id']);// delete old ID
		$post['created'] = time();// Refresh created
		
		$copy = Post::create($post);
		if ($copy->save()) {
			$this->redirect(array('controller' => 'posts', 'action' => 'seller'));
		}

		return false;
	}

	public function delete() {
		if(!$me = Auth::check('default')) {
			return $this->redirect(array('controller' => 'Sessions', 'action' => 'add', 'args' => base64_encode($this->request->url)));
		}

		$post = Post::find('first', array('conditions' => array('_id' => $this->request->id, 'user_id' => $me['_id'])));

		if (empty($post)) {
			return $this->redirect(array('controller' => 'posts', 'action' => 'index'));
			// log tentative de delete sur item inexistant
		}

		if ($post->delete()) {
			$this->redirect(array('controller' => 'posts', 'action' => 'index'));
		}

		return false;
	}

	public function deleteStory() {
		$post = Story::find($this->request->id);
		$real_post = Post::find('first', array('conditions' => array('idProduct' => $post->idProduct)));
		
		if (empty($post)) {
			$this->redirect(array('controller' => 'posts', 'action' => 'index'));
		}

		if ($post->delete()) {
			//$this->redirect(array('controller' => 'posts', 'action' => 'index'));
		} else {
			//
		}
		$this->redirect(array('controller' => 'posts', 'action' => 'sortable', 'args' => $post->idProduct));

		return false;
	}
}