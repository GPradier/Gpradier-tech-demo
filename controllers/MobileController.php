<?php
namespace app\controllers;

use lithium\security\Auth;
use app\models\Post;

use app\models\Story;
use app\models\Comment;
use app\models\User;
use app\models\Favori;
use app\models\File;
use app\models\Viewing;
use lithium\util\Set;

use lithium\net\http\Media;//
use lithium\action\Request;

class MobileController extends \lithium\action\Controller {
	public function index() {
		$success = false;
		$datas = array('errorcode' => '00', 'message' => 'No controller selected. Controllers currently availables : login, register, objects, favorites');
		return $this->render(array('type' => 'json', 'data' => compact('success', 'datas')));
	}

	public function trymania() {
		$user = User::find('all', array('conditions' => array('_id' => array('$lt' => $this->request->query['last'])), 'fields' => array('username', 'firstname', 'lastname','location', 'avatar')));

		$user = $user->data();
		return $this->render(array('type' => 'json', 'data' => $user));
	}

	public function objects(){
		$page = 1;
		$limit = 20;
		$order = array('_id' => 'DESC');// ou /created

		if (isset($this->request->query['page'])) {
			$page = $this->request->query['page'];
		}
		if (!empty($this->request->query['limit'])) {
				$limit = $this->request->query['limit'];
		}

		$offset = ($page - 1) * $limit;
		$total = Post::find('count');

		$conditions = array('valid' => true);
		if(!empty($this->request->query['last'])) {
			$conditions['_id'] = array('$lt' => $this->request->query['last']) ;}
		$postsObj = Post::find('all', compact('conditions', 'limit', 'offset', 'order'));

		$usersId = Set::extract($postsObj->data(), '/user_id');
		$users = User::find('all', array('conditions' => array('_id' => $usersId), 'fields' => array('username', 'firstname', 'lastname', 'location', 'avatar')));

		$posts = $postsObj->map(function($post) {
			$user = User::find('first', array('conditions' => array('_id' => $post['user_id']), 'fields' => array('username', 'firstname', 'lastname','location', 'avatar')));
			$post->user = $user;

			//$post->firstimage = File::find('first', array('conditions' => array('related' => $post->_id)));
			$firstimage = File::find('first', array('conditions' => array('related' => $post->_id)));
			$firstimage->url = (!$firstimage->url ? 'http://ec2-54-246-65-91.eu-west-1.compute.amazonaws.com/favstory/new/upload/view/'.$firstimage->_id : $firstimage->url);
			$post->firstimage = $firstimage;

			unset($post->image);// drop for API

			return $post;
		});

		if($posts) {
			$datas = $posts->to('array');
		}
		else {
			$datas = array('errorcode' => '02', 'message' => 'No products available for home');
		}

		return $this->render(array('type' => 'json', 'data' => compact('success', 'datas')));
	}

	public function favorites($action = 'list', $idprod = null) {
		//return self::objects();

		$success = false;
		if(!$user = Auth::check('default')) {
			$datas = array('errorcode' => '20', 'message' => 'No user connected');
			return $this->render(array('type' => 'json', 'data' => compact('success', 'datas')));
		}

		if($action == 'list') {
				$page = 1;
				$limit = 20;
				$order = array('_id' => 'DESC');// ou /created

				if (isset($this->request->query['page'])) {
					$page = $this->request->query['page'];
				}
				if (!empty($this->request->query['limit'])) {
						$limit = $this->request->query['limit'];
				}

				$offset = ($page - 1) * $limit;
				//$total = Post::find('count');

				if(!empty($this->request->query['last'])) {
					$favoris = Favori::find('all', array('conditions' => array('product_id' => array('$lt' => $this->request->query['last']), 'user_id' => $user['_id']), 'limit' => $limit, 'offset' => $offset, 'order' => $order));
				}
				else {
					$favoris = Favori::find('all', array('conditions' => array('user_id' => $user['_id']), 'limit' => $limit, 'offset' => $offset, 'order' => $order));
				}
				$productsId = Set::extract($favoris->data(), '/product_id');
				$conditions = array('_id' => $productsId);
				$postsObj = Post::find('all', compact('conditions', 'limit', 'offset', 'order'));

				//$conditions = array('user_id' => $user->_id, 'valid' => true);
				//$postsObj = Post::find('all', compact('conditions', 'limit', 'offset', 'order'));

				$usersId = Set::extract($postsObj->data(), '/user_id');
				$users = User::find('all', array('conditions' => array('_id' => $usersId), 'fields' => array('username', 'firstname', 'lastname', 'location', 'avatar')));

				$posts = $postsObj->map(function($post) {
					if($post->user->_id != -1) {// Est un objet connu
						$user = User::find('first', array('conditions' => array('_id' => $post['user_id']), 'fields' => array('username', 'firstname', 'lastname','location', 'avatar')));
						$post->user = $user;
					

					//$post->firstimage = File::find('first', array('conditions' => array('related' => $post->_id)));
					$firstimage = File::find('first', array('conditions' => array('related' => $post->_id)));
					$firstimage->url = (!$firstimage->url ? 'http://ec2-54-246-65-91.eu-west-1.compute.amazonaws.com/favstory/new/upload/view/'.$firstimage->_id : $firstimage->url);
					$post->firstimage = $firstimage;

					unset($post->image);// drop for API
					}
					return $post;
				});

				if($posts) {
					$favoris = $favoris->data();
					$posts = $posts->data();
					$success = true;
					$datas = compact('posts', 'favoris');
				}
				else {
					$datas = array('errorcode' => '02', 'message' => 'No products available for favorites');
				}
		}
		if($action == 'add') {
				$post = Post::find($idprod);

				if (!$post) {
					$datas = array('errorcode' => '27', 'message' => 'Invalid object, no detail');
					return $this->render(array('type' => 'json', 'data' => compact('success', 'datas')));
				}
				$post = $post->to('array');

				$alreadyfav = Favori::find('first', array('conditions' => array('user_id' => $user['_id'], 'product_id' => $post['_id'])));

				if(!$alreadyfav) {//new, create
					$favori = Favori::create();
					$favori->user_id = $user['_id'];
					$favori->product_id = $post['_id'];
					$favori->created = time();
					$favori->save();
				} else {
					$favori = $alreadyfav;
				}

				$favori = $favori->data();

				$success = true;
				$datas = compact('favori');
		}
		if($action == 'all') {
				$favori = Favori::find('all', array('conditions' => array('user_id' => $user['_id'])));

				$success = true;
				$datas = $favori->to('array');
				return $this->render(array('type' => 'json', 'data' => compact('success', 'datas')));
		}

		return $this->render(array('type' => 'json', 'data' => compact('success', 'datas')));
	}

	public function register(){
		$success = false;
		if($user = Auth::check('default')) {
			$datas = array('errorcode' => '20', 'message' => 'Already connected');
			return $this->render(array('type' => 'json', 'data' => compact('success', 'datas')));
		}

		if(!$this->request->data) {
			$datas = array('errorcode' => '01', 'message' => 'Waiting post to register');
			return $this->render(array('type' => 'json', 'data' => compact('success', 'datas')));
		}

		$datas = User::create($this->request->data);
		if (($this->request->data) && $datas->save()) {
			Auth::check('default', $this->request);// Auto-login

			$success = true;
			return $this->render(array('type' => 'json', 'data' => compact('success', 'datas')));
		}

		$errors = $datas->errors();
		//$firstError = array_shift(array_values(array_shift(array_values($errors))));
		// "un peu ghetto hein"

		$datas = array('errorcode' => '04', 'message' => 'Error when trying to create the new user.', 'errors' => $errors);
		return $this->render(array('type' => 'json', 'data' => compact('success', 'datas')));
	}

	public function login() {
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

	public function logout() {
		Auth::clear('default');
		$success = true;
		$datas = array();
		return $this->render(array('type' => 'json', 'data' => compact('success', 'datas')));
	}

	public function passwordrequest() {
		$username = $this->request->data['email'];

		if(!$username) {
			$success = false;
			$datas = array('errorcode' => '29', 'message' => 'No user requested');
			return $this->render(array('type' => 'json', 'data' => compact('success', 'datas')));
		}

		$success = User::find('first', array('conditions' => array('username' => $username), 'fields' => array('_id')));

		if($success) {
			$datas = "Email sent with instructions for reset";
			return $this->render(array('type' => 'json', 'data' => compact('success', 'datas')));
		}
		else {
			$success = false;
			$datas = array('errorcode' => '31', 'message' => 'No user with this username');
			return $this->render(array('type' => 'json', 'data' => compact('success', 'datas')));
		}
	}

	public function scan($barcode) {
		$success = false;

		if(!$user = Auth::check('default')) {
			$datas = array('errorcode' => '20', 'message' => 'No user connected');
			return $this->render(array('type' => 'json', 'data' => compact('success', 'datas')));
		}

		$post = Post::find('first', array('conditions' => array('codebarre' => $barcode)));

		if(empty($post)) {
			//return $this->redirect('Posts::add');// non trouvé, nouvel objet
			$post = Post::create();
			$post->title = "Inconnu";
			$post->valid = false;// Ne pas afficher dans les results
			$post->user = array('_id' => -1, 'username' => 'Inconnu', 'firstname' => 'Inconnu', 'lastname' => 'Inconnu', 'location' => 'Inconnu', 'avatar' => 'http://ec2-54-246-65-91.eu-west-1.compute.amazonaws.com/favstory/new/img/avatar-user.png');
			$post->firstimage = array('_id' => -1, 'url' => 'http://ec2-54-246-65-91.eu-west-1.compute.amazonaws.com/favstory/new/img/ex1.png', 'created_at' => time(), 'realname' => 'ex1.jpg', 'extension' => 'jpg', 'size' => 0, 'type' => "image/jpeg");
			$post->images = array($post->firstimage);
			$post->codebarre = $barcode;

			$post->created = time();
			if($post->save()) {
				//
			}
		}
		else {
			if($post->user['_id'] != -1) {
			//GENERATION DU FULL PRODUIT :
				$usersId = Set::extract($post->data(), '/user_id');
				$users = User::find('all', array('conditions' => array('_id' => $usersId), 'fields' => array('username', 'firstname', 'lastname', 'location', 'avatar')));

					$user = User::find('first', array('conditions' => array('_id' => $post['user_id']), 'fields' => array('username', 'firstname', 'lastname','location', 'avatar')));
					$post->user = $user;
					//$post->firstimage = File::find('first', array('conditions' => array('related' => $post->_id)));
					$post->images = File::find('all', array('conditions' => array('related' => $post->_id)))->to('array');
					unset($post->image);// drop for API
			}
		}

		//$post->favcode = substr(md5(microtime()),rand(0,26),6);
		// dont send favcode
		//$post->save();

		$success = true;
		$datas = $post;

		return $this->render(array('type' => 'json', 'data' => compact('success', 'datas')));
	}

	public static $upload_repertory = '/var/www/favstory/new/webroot/upload/videos/';//video repertory
	public static $link_absolute = 'http://ec2-54-246-65-91.eu-west-1.compute.amazonaws.com/favstory/new/upload/videos/';//video repertory
	public function videoadd() {
		$success = false;

		if(!$user = Auth::check('default')) {
			$datas = array('errorcode' => '20', 'message' => 'No user connected');
			return $this->render(array('type' => 'json', 'data' => compact('success', 'datas')));
		}

		if(!$this->request->data) {
			$datas = array('errorcode' => '81', 'message' => 'Waiting video post data');
			return $this->render(array('type' => 'json', 'data' => compact('success', 'datas')));
		}

		// Gestion des infos du produit scan video
		$id_related = $this->request->data['related'];
		$product_related = Post::find('first', array('conditions' => array('_id' => $id_related)));
		$productName = ($product_related->title ? $product_related->title : 'Objet inconnu');
		
		if(!$product_related->user_id) {
			$productCreatorId = -1;
			$productCreatorName =  'Inconnu';
			$productCreatorAvatar = 'http://ec2-54-246-65-91.eu-west-1.compute.amazonaws.com/favstory/new/img/avatar-user.png';
		}
		else {
			$creatObj = User::find('first', array('conditions' => array('_id' => $product_related->user_id), 'fields' => array('username', 'firstname', 'lastname', 'avatar')));
			$productCreatorId = $creatObj->_id;
			$productCreatorName = $creatObj->firstname .' '. $creatObj->lastname;
			$productCreatorAvatar = ($creatObj->avatar ? $creatObj->avatar : 'http://ec2-54-246-65-91.eu-west-1.compute.amazonaws.com/favstory/new/img/avatar-user.png');
		}
		
		$productDescription = ($product_related->body ? $product_related->body : '');
		// End gestion
		
		

		//$favcode = $this->request->data['favcode'];// GENERATE HERE
		$favcode = substr(md5(microtime()),rand(0,26),6);

		$user = User::find($user['_id']);//array to object

		$size = $this->request->data['upload']['size'];
		$maxSize = 16777216;//16Mo

		$extension = end(explode(".", $this->request->data['upload']['name']));
		//$allowedExtensions = array("txt","csv","htm","html","xml","css","doc","docx","xls","rtf","ppt","pdf","jpg","jpeg","gif","png");
		$allowedExtensions = array("avi","mpeg","mp4","divx","flv");

		$type = $this->request->data['upload']['type'];
		$allowedTypes = array(
			"video/mpeg",
			"video/mp4",
			"video/quicktime",
			"video/x-ms-wmv",
			"video/x-msvideo",
			"video/x-flv",
			"application/mp4"
		);

		if(in_array($extension, $allowedExtensions)) {
			if(in_array($type, $allowedTypes)) {
				if($size < $maxSize) {
					if ($_FILES['upload']['error'] == 0) {
						$tmp_name = $this->request->data['upload']['tmp_name'];
						$real_name = $this->request->data['upload']['name'];

						$file = File::create();
						$file->realname = $real_name;
						$file->extension = $extension;
						$file->size = $size;
						// Add recipient name
						$file->recipient = array(
							'_id' => -1,
							'avatar' => 'http://ec2-54-246-65-91.eu-west-1.compute.amazonaws.com/favstory/new/img/avatar-user.png',
							'username' => 'useFirstname+Lastname',
							'firstname' => $this->request->data['recipient'],
							'lastname' => ''
						);
						
						$file->productname = $productName .' par '. $productCreatorName;
						$file->productdescription = $productDescription;
						$file->productcreator = array(
							'_id' => $productCreatorId,
							'name' => $productCreatorName,
							'avatar' => $productCreatorAvatar,
						);
						
						// End Add recipient
						$file->type = $type;
						$file->related = $id_related;
						$file->favcode = $favcode;
						$file->type = 'video';
						$file->user = $user->_id;
						$file->created_at = time();
						$file->ip = $_SERVER['REMOTE_ADDR'];

						$file->unique_name = sha1($user->_id .'-'. $file->_id .'-'. microtime());

						if(@move_uploaded_file($tmp_name, self::$upload_repertory . $file->unique_name .'.'. $extension))
						{
							$file->url = self::$link_absolute . $file->unique_name . '.' . $extension;
							$file->save();
		//					header('HTTP/1.1 888 Something Wrong');
							$success = true;
							$video = $file->data();
							$datas = compact('favcode', 'video');
							return $this->render(array('type' => 'json', 'data' => compact('success', 'datas')));
						}
						$datas = array('errorcode' => '81', 'message' => 'Video file cannot be save on server.');
						return $this->render(array('type' => 'json', 'data' => compact('success', 'datas')));
					}
					$datas = array('errorcode' => '82', 'message' => 'Unknow error #'.$_FILES['upload']['error'].'');
					return $this->render(array('type' => 'json', 'data' => compact('success', 'datas')));
				}
				$datas = array('errorcode' => '83', 'message' => 'Video file too big.');
				return $this->render(array('type' => 'json', 'data' => compact('success', 'datas')));
			}
			$datas = array('errorcode' => '84', 'message' => 'Unknow MIME type : '. $type);
			return $this->render(array('type' => 'json', 'data' => compact('success', 'datas')));
		}
		$datas = array('errorcode' => '85', 'message' => 'Forbidden file extension.');
		$error = $this->request->data;
		return $this->render(array('type' => 'json', 'data' => compact('success', 'datas', 'error')));
	}

	public function videoget($idproduct, $favcode) {
		$success = false;
		if(!$user = Auth::check('default')) {
			$datas = array('errorcode' => '20', 'message' => 'No user connected');
			return $this->render(array('type' => 'json', 'data' => compact('success', 'datas')));
		}

		$video = File::find('first', array('conditions' => array('type' => 'video', 'related' => $idproduct, 'favcode' => $favcode)));
		if($video) {
			$creator = User::find('first', array('conditions' => array('_id' => $video['user'])));
			unset($creator->password);
			$datas = $video->data();
			$success = true;

			//Viewing::remove();exit();
			$vision = Viewing::find('first', array('conditions' => array('favcode' => $favcode, 'video_id' => $video['_id'])));
			if($vision) {
				$vision->totalviews = $vision->totalviews +1;
				$vision->views[] = array('ip' => $_SERVER['REMOTE_ADDR'], 'time' => time());

				if($vision->save()) {
					//
				}
			}
			else {
				////// MaJ with new full details recipient
				if(!$user->avatar) {$user->avatar = 'http://ec2-54-246-65-91.eu-west-1.compute.amazonaws.com/favstory/new/img/avatar-user.png';}
				$video->recipient = $user;
				$video->save();
				//// End
				
				$readvideo = Viewing::create();
				$readvideo->user_id = $user['_id'];// Log qui a regardé la video
				//$readvideo->video_user = $video['user'];// Qui a crée video //
				$readvideo->video_user = $creator;
				$readvideo->video_user_id = $creator['_id'];
				$readvideo->favcode = $favcode;
				$readvideo->product_id = $video['related'];
				$readvideo->video_id = $video['_id'];
				$readvideo->created = time();

				$readvideo->totalviews = 1;
				$readvideo->views = array(array('ip' => $_SERVER['REMOTE_ADDR'], 'time' => time()));

				$readvideo->save();
				// Fin du log
			}

			return $this->render(array('type' => 'json', 'data' => compact('success', 'datas')));
		}
		$datas = array('errorcode' => '70', 'message' => 'No video found.', 'args' => array($idproduct, $favcode));
		return $this->render(array('type' => 'json', 'data' => compact('success', 'datas')));
	}

	////$readvideo->video_user_id = $creator['_id'];

	public function videosuploaded() {
		$success = false;
		if(!$user = Auth::check('default')) {
			$datas = array('errorcode' => '20', 'message' => 'No user connected');
			return $this->render(array('type' => 'json', 'data' => compact('success', 'datas')));
		}

		$user = User::find('first', array('conditions' => array('_id' => $user['_id'])));

		$videos = File::find('all', array('conditions' => array('type' => 'video', 'user' => $user['_id'])));
		$datas = $videos->data();
		$success = true;

		return $this->render(array('type' => 'json', 'data' => compact('success', 'datas')));
	}

	public function videosrecip() {
		$success = false;
		if(!$user = Auth::check('default')) {
			$datas = array('errorcode' => '20', 'message' => 'No user connected');
			return $this->render(array('type' => 'json', 'data' => compact('success', 'datas')));
		}

		if(isset($this->request->id) && $this->request->id) {
			$videos = Viewing::find('all', array('conditions' => array('product_id' => $this->request->id, 'user_id' => $user['_id']), 'order' => array('_id' => 'DESC')));
		}
		else {
			$videos = Viewing::find('all', array('conditions' => array('user_id' => $user['_id']), 'order' => array('_id' => 'DESC')));
		}
		$count = $videos->count();
		if($videos) {
			$datas = $videos->data();
			$success = true;
		}
		else {
			$datas = null;
			$success = true;
			// PAS DE MESSAGE
		}

		return $this->render(array('type' => 'json', 'data' => compact('success','count', 'datas')));
	}

	public function account($action = 'index') {
		$success = false;
		if(!$user = Auth::check('default')) {
			$datas = array('errorcode' => '20', 'message' => 'No user connected');
			return $this->render(array('type' => 'json', 'data' => compact('success', 'datas')));
		}

		$user = User::find('first', array('conditions' => array('_id' => $user['_id'])));
			unset($user->password);
		if($user) {
			if($action == 'index') {
				$datas = $user->data();
				$success = true;
				return $this->render(array('type' => 'json', 'data' => compact('success', 'datas')));
			}
			if($action == 'edit') {
				if ($this->request->data) {
					$success = User::update($this->request->data, array('_id' => $user->_id));
					if($success) {
						$success = true;
						$datas = User::find('first', array('conditions' => array('_id' => $user['_id'])));// REFRESH AFTER UPDATE
							unset($user->password);
						return $this->render(array('type' => 'json', 'data' => compact('success', 'datas')));
					}
					$datas = array('errorcode' => '04', 'message' => 'Error when trying to update user.', 'errors' => $success->errors());
					return $this->render(array('type' => 'json', 'data' => compact('success', 'datas')));
				}
				$datas = array('errorcode' => '36', 'message' => 'Updating profile require POST datas.');
				return $this->render(array('type' => 'json', 'data' => compact('success', 'datas')));
			}
			$datas = array('errorcode' => '39', 'message' => 'Action unknow, use index or edit.');
			return $this->render(array('type' => 'json', 'data' => compact('success', 'datas')));
		}

		$datas = array('errorcode' => '34', 'message' => 'User not found');
		return $this->render(array('type' => 'json', 'data' => compact('success', 'datas')));
	}
	
	public function stats() {
		$videos = Viewing::find('all');
		$stats = array('totalviews' => null,'nbusers' => null,'nbproducts'=>null,'nbvideos'=>null,'datas'=>null);
		//$stats = array();
		//$stats['nbusers'] = null;
		/*
		$videos->each(function($video) {
			$stats = $video->totalviews;
			var_dump($video->totalviews);
			return true;
		});
		*/
		foreach($videos as $video) {
			$stats['totalviews'] += $video->totalviews;
			$stats['datas']['videos'][] = $video->video_id;
			$stats['datas']['products'][] = $video->product_id;
			$stats['datas']['users'][] = $video->user_id;
		}
		$stats['nbvideos'] = count(array_unique($stats['datas']['videos']));
		$stats['nbproducts'] = count(array_unique($stats['datas']['products']));
		$stats['nbusers'] = count(array_unique($stats['datas']['users']));
		
		return $this->render(array('type' => 'json', 'data' => compact('stats')));
	}
	
	public function favclub() {
		$datas = File::find('all', array('conditions' => array('_id' => array('51423fd3c59e6e8b5e000000', '51423fcac59e6ead60000000')), 'limit' => 10, 'order' => array('_id' => 'DESC')));
	
		return $this->render(array('type' => 'json', 'data' => compact('datas')));
	}

}
?>