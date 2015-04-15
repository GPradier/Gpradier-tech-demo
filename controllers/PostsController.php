<?php
namespace app\controllers;
use app\models\Post;
use app\models\Story;
use app\models\Comment;
use app\models\User;
use app\models\File;
use app\models\Stats;
use lithium\security\Auth;
use lithium\util\Set;

use lithium\net\http\Media;//

//use lithium\net\socket\Curl;
use lithium\net\socket\Stream;

use lithium\action\Request;

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
			$post->firstimage = File::find('first', array('conditions' => array('related' => $post->_id), 'order' => array('_id' => 'DESC')));
			
			return $post;
		});
		
		$posts = $posts->to('array');
		
		
		

		$title = 'Home';
		
		//
		Media::type('mobile',  array('text/html'), array(
//			'paths' => array('layout' => false),
			//'view' => 'lithium\template\View',
			'view' => 'lithium\template\View',
			'conditions' => array('type' => true)
		));
		
		//var_dump($this->_env['PLATFORM']);
		
		if($this->request->is('mobile')) {
			if($this->request->type != 'mobile') {
				return($this->redirect($this->request->url . '.mobile'));
			}
			/*
			Media::type('mobile',  array('text/html'), array(
				'view' => 'lithium\template\View',
				'paths' => array('layout' => false)
			));
			*/
		}
		
		
		//
		//$this->render(array('template' => 'index', 'layout' => false));
		//
		
		return $this->render(array('data' => compact('posts', 'limit', 'page', 'total', 'title')));
		
		//return compact('posts', 'limit', 'page', 'total', 'title');
	}
	
	public function boutique() {		
		$posts = Post::find('all', array('conditions' => array('user_id' => $this->request->id)))->to('array');
		$vendor = User::find('first', array('conditions' => array('_id' => $this->request->id)))->to('array');
		$title = 'Boutique de '. $vendor['firstname'] .' '. $vendor['lastname'];

		return compact('posts', 'vendor', 'title');
	}
	
	public function seller($admin = null) {
		if(!$me = Auth::check('default')) {
			return $this->redirect(array('controller' => 'sessions', 'action' => 'add', 'args' => base64_encode($this->request->url)));
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

		//$conditions = array('user_id' => $me['_id']);
		$conditions = array('user_id' => $me['_id'], 'title' => array('$exists' => true, '$nin' => array('', " ")));
		// Query pour éliminer les produits non finis ; purge later
		
		if($admin == 'admin') {$conditions = array();}

		$total = Post::find('count', compact('conditions'));

		//$posts = Post::find('all', compact('conditions', 'limit', 'offset', 'order'))->to('array');
		/*
		 * Ajout Dynamique Insertion du User (me) et de la first image */
		$posts = Post::find('all', compact('conditions', 'limit', 'offset', 'order'));
		$posts = $posts->map(function($post) {
			$user = User::find('first', array('conditions' => array('_id' => $post['user_id']), 'fields' => array('username', 'firstname', 'lastname','location', 'avatar')));
			$post->user = $user;
			$post->firstimage = File::find('first', array('conditions' => array('related' => $post->_id), 'order' => array('_id' => 'DESC')));
			
			return $post;
		});
		$posts = $posts->to('array');
		/* END AJOUT */
		

		$title = 'Espace Créateurs - Tableau de bord';
		$disptype = ($this->request->query['format'] ? $this->request->query['format'] : 'table');

		return compact('posts', 'limit', 'page', 'total', 'title', 'orderby', 'sort', 'admin', 'disptype');
	}

	public function view() {
		$post = Post::find($this->request->id);
		
		if (!$post) {
			throw new \Exception ('Invalid post if provided');
		}
		
		$post->user = User::find('first', array('conditions' => array('_id' => $post->user_id), 'fields' => array('username', 'firstname', 'lastname', 'location', 'avatar')));
		
		$docs = File::find('all', array('conditions' => array('related' => $post->_id)));
		$post = $post->to('array');
		
		$title = $post['title'];

		$me = Auth::check('default');
		
		Stats::listen('view', $post['_id'], $post['user_id']);
		// //** BEGIN STAT
		// $now = time();
		// $idU = ($me ? $me['_id'] : -1);
		// $stat = Stats::create(array(
			// 'event' => 'view',			// Event = View (Nombre de vues d'un produit
			// 'target' =>$post['_id'],		// Target = ID du produit
			// 'user' => $idU,				// User = false, ou ID du user
			// 'owner' => $post['user_id'],	// Owner = ID du créateur du produit
			// 'created_at' => $now,		// Timestamp de création du produit
			// 'from' => 'web'				// WEB
			// 'time_bucket' => array(		// Time bucket pour indexation noSQL
				// date('Y-m-d-H', $now).'-hour',
				// date('Y-m-d', $now).'-day',
				// date('Y-W', $now).'-week',
				// date('Y-m', $now).'-month',
				// date('Y', $now).'-year'
		// )))->save();
		// //** END STAT
		
		
	
		//$comments = Comment::find('all', array('conditions' => array('post_id' => $this->request->id, 'status' => 'live')));
		$comments = Comment::find('all', array('conditions' => array('post_id' => $this->request->id))); // Les commentaires sur CE produit

		//$usersId = Set::extract('/posts/user_id', $comments->data());
		$usersId = Set::extract($comments->data(), '/user_id');
		$users = User::find('all', array('conditions' => array('_id' => $usersId), 'fields' => array('username', 'firstname', 'lastname', 'location', 'avatar')));
		
//		var_dump($comments);

		// Ce que je ne veux "PAS" faire ...
		$bourrin = $comments->map(function($post) {
			$user = User::find('first', array('conditions' => array('_id' => $post['user_id']), 'fields' => array('username', 'firstname', 'lastname', 'avatar')));
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
		
		
		$storys = Story::find('all', array('conditions' => array('related' => $post['_id']), 'order' =>  array('order' => 'ASC')));
		$storys = ($storys ? $storys->to('array') : null);

		return compact('post', 'title', 'comment', 'comments', 'users', 'me', 'storys', 'docs');
	}
	
	public function image_miniature() {
		/*
		 * Fix incorrect Path
		$posts = Post::find('all', array('conditions' => array('image' => 'img/noimg.png')));
		foreach($posts as $post) {
			$post->image = 'noimg.png';
			$post->save();
		}
		exit();
		
		*/
		
		/* 
		 * Fix setup default image
		$conditions = array('image' => array('$exists' => false));
		$posts = Post::find('all', compact('conditions'));
		
		foreach($posts as $post) {
			// $post->upload = null;
			// unset($post->upload);
			// $post->save();
			
			$image = File::find('first', array('conditions' => array('related' => $post->_id)));
			//($image ? $image->to('array') : '');
			
			if($image) {
				$post->image = 'upload/view/'. $image->unique_name .'';
				$post->save();
			}
			else {
				$post->image = 'noimg.png';
				$post->save();
			}
		}
		*/
		
		$posts = Post::find('all');
		
		foreach($posts as $post) {
			$image = File::find('first', array('conditions' => array('related' => $post->_id)));			
			if($image) {
				$post->image = '/upload/view/'. $image->_id .'';
				$post->save();
			}
			else {
				// Il en a une par défaut, ou la sienne
			}
		}
		
		var_dump($posts);
		exit();
	}
	/*
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
	*/
	public function story() {// uniquement pour l'ajout
		$post = Post::find($this->request->id);

		if (!$post) {
			throw new \Exception ('Invalid post if provided');
		}
		
		if (!$me = Auth::check('default')) {
			return $this->redirect(array('controller' => 'sessions', 'action' => 'add', 'args' => base64_encode($this->request->url)));
		}
		
		if($this->request->data) {//new story element
			$story = Story::create($this->request->data);
			$story->related = $this->request->id;
			//$story->order = 99;// pas classé encore
			$laststory  = Story::find('first', array('conditions' => array('related' => $this->request->id), 'order' => array('order' => 'DESC')));
			$story->order = $laststory->order + 1;// LA dernière +1
			
			$story->user = $me['_id'];
			if($story->save()) {
				//return $this->redirect(array('action' => 'edit', 'args' => $this->request->id));
				
				/*
				$currentStep = $this->request->query['step'];
				return $this->redirect(array('action' => 'edit', 'args' => array($post->_id, '?step='.$currentStep)));
				*/
				return $this->redirect(array('action' => 'edit', 'args' => array($post->_id, '?step=etape3')));
			}
			else {
				throw new \Exception ('Invalid new story added');
			}
		}

		return false;
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
			return $this->redirect(array('controller' => 'sessions', 'action' => 'add', 'args' => base64_encode($this->request->url)));
		}
		
		/* 
		 * New Process : création & redirect to new edit
		 */
		$post = Post::create();
		$post->user_id = $me['_id'];
		$post->created = time();
		$post->valid = false;
		$post->save();
		
		return $this->redirect(array('action' => 'edit', 'args' => $post->_id));
		// End new process ; 
		
		
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
		//$allowedExtensions = array("txt","csv","htm","html","xml","css","doc","docx","xls","rtf","ppt","pdf","jpg","jpeg","gif","png");
		$allowedExtensions = array("jpg","jpeg","gif","png","bmp");
		
		$type = $upload['type'];
		/*
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
		*/
		$allowedTypes = array(
			"image/gif", 
			"image/jpeg",
			"image/png",
			"image/bmp",
		);
		
//		var_dump($upload);
		
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
				//$file->unique_name = sha1($user->_id .'-'. $file->_id .'-'. time());
				$file->unique_name = sha1($user->_id .'-'. mt_rand() .'-'. microtime());
				
				if(move_uploaded_file($tmp_name, self::$upload_repertory . $file->unique_name .'.'. $extension))
				{
					if($file->save()) {
					/*
					 * Moodstocks
					 */
						$key = "pzkzvme87firwd4ctrik";
						$secret = "VDJ5xVAtev70iMxK";
						$image_id = (string) $file->_id;
						$image_url = 'http://ec2-54-246-65-91.eu-west-1.compute.amazonaws.com/favstory/new/upload/view/'.$image_id.'';
																		
						$all_elements = exec('curl --digest -u '.$key.':'.$secret.' "http://api.moodstocks.com/v2/stats/refs"');
						$json = json_decode($all_elements);
						$last_element = ($json->count >= 100 ? $json->ids[3] : null);//ids[3] correspond au 4 element, les 3 premiers étants réservés
						if($last_element) {
							$request_elem_suppression = exec('curl --digest -u '.$key.':'.$secret.' "http://api.moodstocks.com/v2/ref/'.$last_element.'" -X DELETE');
						}
						// else Index is not full (< 10), no suppression

						// echo "exec('curl --digest -u $key:$secret \"http://api.moodstocks.com/v2/ref/$image_id\" --form image_url=\"$image_url\" -X PUT')";

						$request_elem_add = exec('curl --digest -u '.$key.':'.$secret.' "http://api.moodstocks.com/v2/ref/'.$image_id.'" --form image_url="'.$image_url.'" -X PUT');
						
						// echo $all_elements;
						// echo $request_elem_suppression;
						// echo $request_elem_add;						
						// exit();
						
						return 'success';
					}
					else {
						return 'fail';
						// header('HTTP/1.1 999 fail');
						// exit();
					}
					/*
					 * End Moodstocks
					 */
				}
				else{
					//
					return 'fail';
				}
			}
		}
		
		return 'end of upload fail';
	}

	public function edit() {
		if(!$me = Auth::check('default')) {
			return $this->redirect(array('controller' => 'sessions', 'action' => 'add', 'args' => base64_encode($this->request->url)));
		}
		
		$currentStep = $this->request->query['step'];
		
		$post = Post::find('first', array('conditions' => array('_id' => $this->request->id, 'user_id' => $me['_id'])));
		$docs = File::find('all', array('conditions' => array('user' => $me['_id'], 'related' => $post->_id)));
		$allimages = File::find('all', array('conditions' => array('user' => $me['_id'])));
		
		if (empty($post)) {
			return $this->redirect(array('controller' => 'posts', 'action' => 'index'));
		}
		
		$data = $post->to('array');
		$storys = Story::find('all', array('conditions' => array('user' => $me['_id'], 'related' => $data['_id']), 'order' =>  array('order' => 'ASC')));
		// Order mal geré
		//$storys = Story::find('all', array('conditions' => array('user' => $me['_id'], 'related' => $data['_id']), 'order' =>  array('_id' => 'ASC')));
		
		$storys = ($storys ? $storys->to('array') : null);

		if ($this->request->data) {
			if ($post->save($this->request->data)) {
				//$this->redirect(array('controller' => 'posts', 'action' => 'index'));
				
				$is_upload = self::upload($this->request->data['upload'], $post);			
				if($is_upload) {
					$post->upload = null;
					$post->save();
				}
				
				return $this->redirect(array('action' => 'edit', 'args' => array($post->_id, '?step='.$currentStep)));
			}
		}

		$title = 'Modifier '.$post->title;

		return compact('post', 'docs', 'title', 'allimages', 'storys', 'currentStep');
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
			// $this->redirect(array('controller' => 'posts', 'action' => 'view', 'args' => $this->request->id));
			// to Login
			return $this->redirect(array('controller' => 'Sessions', 'action' => 'add', 'args' => base64_encode($this->request->url)));
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
			return $this->redirect(array('controller' => 'posts', 'action' => 'index'));
		}

		return false;
	}
	
	public function publish() {
		if(!$me = Auth::check('default')) {
			return $this->redirect(array('controller' => 'Sessions', 'action' => 'add', 'args' => base64_encode($this->request->url)));
		}

		$post = Post::find('first', array('conditions' => array('_id' => $this->request->id, 'user_id' => $me['_id'])));

		if (empty($post)) {
			return $this->redirect(array('controller' => 'posts', 'action' => 'index'));
		}

		$post->valid = true;
	
		// /*
		 // *	Moodstocks
		 // */
		 // $imageMoodstocks = File::find('first', array('conditions' => array('related' => $post->_id), 'order' => array('_id' => 'DESC')));
		// // Settings
		// $key = "pzkzvme87firwd4ctrik";
		// $secret = "VDJ5xVAtev70iMxK";
		// $image_filename = $imageWoodstocks->realname;
		// $image_url = 'http://ec2-54-246-65-91.eu-west-1.compute.amazonaws.com/favstory/new/upload/view/'. $imageMoodstocks->_id;
		// $id = $imageMoodstocks->_id;

		// // CURL
		// $curl_opts = array(
		  // CURLOPT_RETURNTRANSFER=>true,
		  // CURLOPT_HTTPAUTH=>CURLAUTH_DIGEST,
		  // CURLOPT_USERPWD=>$key.":".$secret
		// );
		// $api_ep = "http://api.moodstocks.com/v2";

		// function disp($opts){
			// //$ch = curl_init();
			// //curl_setopt_array($ch, $opts);
			
			// //$ch = Curl::open($opts);
			// //$ch = Curl::open();
			// //Curl::set($opts);

			// //$ch = Curl::open(array('options' => $opts));
			// //Curl::open(array('options' => $opts));
			// //$ch = Curl::open();
			// //Curl::set($opts);
			// //Curl::open();
			// //$ch->set($opts);
			
			// $ch = new Stream;
			// Stream::open();
			
			// $raw_resp = curl_exec($ch);
			// $array_resp = json_decode($raw_resp);
			// print_r($array_resp);
			// curl_close($ch);
		// }
// /*
		// // Authenticating with your API key (Echo service)
		// $params = array("foo"=>"bar","bacon"=>"chunky");
		// $opts = $curl_opts;
		// $opts[CURLOPT_URL] = $api_ep."/echo?".http_build_query($params);
		// disp($opts);

		// // Adding a reference image
		// $opts = $curl_opts;
		// $opts[CURLOPT_URL] = $api_ep."/ref/".$id;
		// $opts[CURLOPT_POSTFIELDS] = array("image_file"=>"@".$image_filename);
		// $opts[CURLOPT_CUSTOMREQUEST] = "PUT";
		// disp($opts);
// */
		// // Making an image available offline
		// $opts = $curl_opts;
		// $opts[CURLOPT_URL] = $api_ep."/ref/".$id."/offline";
		// $opts[CURLOPT_CUSTOMREQUEST] = "POST";
		// disp($opts);
// /*
		// // Using online search
		// $opts = $curl_opts;
		// $opts[CURLOPT_URL] = $api_ep."/search";
		// $opts[CURLOPT_POSTFIELDS] = array("image_file"=>"@".$image_filename);
		// disp($opts);

		// // Updating a reference & using a hosted image
		// $opts = $curl_opts;
		// $opts[CURLOPT_URL] = $api_ep."/ref/".$id;
		// $opts[CURLOPT_POSTFIELDS] = array("image_url"=>$image_url);
		// $opts[CURLOPT_CUSTOMREQUEST] = "PUT";
		// disp($opts);

		// // Removing reference images
		// $opts = $curl_opts;
		// $opts[CURLOPT_URL] = $api_ep."/ref/".$id;
		// $opts[CURLOPT_CUSTOMREQUEST] = "DELETE";
		// disp($opts);
// */
		// /*
		 // * End Moodstocks
		 // */
		if ($post->save()) {
			$this->redirect(array('controller' => 'posts', 'action' => 'view', 'args' => $this->request->id));
		}

		return false;
	}

	public function deleteStory() {
		if(!$me = Auth::check('default')) {
			return $this->redirect(array('controller' => 'Sessions', 'action' => 'add', 'args' => base64_encode($this->request->url)));
		}
		
		$post = Story::find($this->request->id);
		$real_post = Post::find('first', array('conditions' => array('idProduct' => $post->idProduct)));
		
		if (empty($post)) {
			$this->redirect(array('controller' => 'posts', 'action' => 'index'));
		}

		if ($post->delete()) {
			//$this->redirect(array('controller' => 'posts', 'action' => 'index'));
			return $this->redirect(array('controller' => 'posts', 'action' => 'edit', 'args' => $post->idProduct));
		} else {
			//
		}
		
		//$this->redirect(array('controller' => 'posts', 'action' => 'sortable', 'args' => $post->idProduct));

		return false;
	}
	public function editStory() {
		if(!$me = Auth::check('default')) {
			return $this->redirect(array('controller' => 'Sessions', 'action' => 'add', 'args' => base64_encode($this->request->url)));
		}
		
		$story = Story::find($this->request->id);
		$real_post = Post::find('first', array('conditions' => array('idProduct' => $story->idProduct)));
		
		if (empty($story)) {
			return $this->redirect(array('controller' => 'posts', 'action' => 'index'));//no Story
		}
		
		if ($this->request->data && $story->save($this->request->data)) {
			return $this->redirect(array('controller' => 'posts', 'action' => 'edit', 'args' => array($story->idProduct, '?step=etape3')));
		}

		return false;
	}
	
	public function orderStorys() {
		if(!$me = Auth::check('default')) {
			//return $this->redirect(array('controller' => 'Sessions', 'action' => 'add', 'args' => base64_encode($this->request->url)));
			return false;
		}
		
		$post = Post::find('first', array('conditions' => array('_id' => $this->request->id, 'user_id' => $me['_id'])));		
		
		if (empty($post)) {
			return false;
		}
		
		if($this->request->data) {
			$idsList = $this->request->data['changeorder'];
			foreach($idsList as $order=>$id) {
				$find_story = Story::find('first', array('conditions' => array('_id' => $id, 'user' => $me['_id'])));
				
				if($find_story) {
					$find_story->order = $order;
					$find_story->save();
				}
			}
			
			return 'success';
		}
		
		return false;
	}
	
	public function mainstory() {
		if(!$me = Auth::check('default')) {
			return $this->redirect(array('controller' => 'sessions', 'action' => 'add', 'args' => base64_encode($this->request->url)));
		}
		$me = User::find($me['_id']);
		
		
		if ($this->request->data) {
			if($this->request->data['idStory']) {
				// edit it
				$story = Story::find($this->request->data['idStory']);
				
				if (empty($story)) {
					return $this->redirect(array('controller' => 'posts', 'action' => 'mainstory'));//no Story to edit
				}
				
				if ($story->save($this->request->data)) {
					return $this->redirect(array('controller' => 'posts', 'action' => 'mainstory'));//success edit Story
				}
			}
			else {
				// create 
				$story = Story::create($this->request->data);
				$story->related = 0;// 0 = mainstory
				$story->order = 99;// pas classé encore
				$story->user = $me['_id'];
				if($story->save()) {
					// return to mainstory
					//return $this->redirect(array('controller' => 'posts', 'action' => 'mainstory'));
					// No action needed ?
				}
			}
		}
		
		
		// DATAS RETURN
		$docs = File::find('all', array('conditions' => array('related' => 0, 'user' => $me->_id)));
		$storys = Story::find('all', array('conditions' => array('user' => $me['_id'], 'related' => 0), 'order' =>  array('order' => 'ASC')));
		return compact('docs', 'storys');
	}
	
	public function last() {
		$last = Post::find('first', array('order' => array('_id' => 'DESC')));
		return compact('last');
	}
	
	public function all() {
		$last = Post::find('all', array('order' => array('_id' => 'DESC')));
		return compact('last');
	}
	
	public function remove($barcode) {
		$post = Post::find('first', array('conditions' => array('codebarre' => $barcode)));
		$post->delete();
	}
	
}