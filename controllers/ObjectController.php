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

class ObjectController extends \lithium\action\Controller {
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
		if($comments) {
			$usersId = Set::extract($comments->data(), '/user_id');
			$users = User::find('all', array('conditions' => array('_id' => $usersId), 'fields' => array('username', 'firstname', 'lastname', 'location', 'avatar')));

			$average = 0;
			foreach($comments as $comm) {
				$average += $comm->note;
			}
			$count = $comments->count();
			if($count > 0) {$average = $average / $count;}
			
			$bourrin = $comments->map(function($post) {
				$user = User::find('first', array('conditions' => array('_id' => $post['user_id']), 'fields' => array('username', 'firstname', 'lastname', 'avatar')));
				$post->user = $user;
				// average comments;
				return $post;
			});
			
			$comments = $bourrin;
			$comments = ($comments->count()) ? $comments->to('array') : false;
			
			$comment = array('count' => $count, 'average' => $average);
		} else {
			//$comment = Comment::create();
			$comment = array(
				'count' => 0,
				'average' => -1
			);
		}
		
		$storys = Story::find('all', array('conditions' => array('related' => $post['_id']), 'order' =>  array('order' => 'ASC')));
		$storys = ($storys ? $storys->to('array') : null);
		
		//FIX RELATIVE LINK
		$base_url = 'http://ec2-54-246-65-91.eu-west-1.compute.amazonaws.com';
			foreach($storys as $i => $stor) {
				$pattern = '/\/favstory\//i';
				preg_match($pattern, $stor['link'], $matches);
				$storys[$i]['link'] = $base_url . $stor['link'];
//				$stor['link'] = $base_url . $subject;
			}

		//
		$success = true;
		$datas = compact('post', 'title', 'comment', 'comments', 'users', 'me', 'storys', 'docs');
		return $this->render(array('type' => 'json', 'data' => compact('success', 'datas')));
		
		//return compact('post', 'title', 'comment', 'comments', 'users', 'me', 'storys', 'docs');
	}
	
	public function comment() {
		$comments = Comment::find('all', array('conditions' => array('post_id' => $this->request->id))); // Les commentaires sur CE produit
		
		if($comments) {
			foreach($comments as $comm) {
				$average += $comm->note;
			}
			$count = $comments->count();
			$average = ($count > 0 ? $average / $count : 0);
			
			/* * Add full user */
			$bourrin = $comments->map(function($post) {
				$user = User::find('first', array('conditions' => array('_id' => $post['user_id']), 'fields' => array('username', 'firstname', 'lastname', 'avatar')));
				if(!$user->avatar) {$user->avatar = 'http://ec2-54-246-65-91.eu-west-1.compute.amazonaws.com/favstory/new/img/avatar-user.png';}
				$post->user = $user;
				return $post;
			});
			
			$comments = $bourrin->data();
			
			$success = true;
			$datas = compact('count', 'average', 'comments');
			return $this->render(array('type' => 'json', 'data' => compact('success', 'datas')));
		}
		
		/*
		$comments = Comment::find('all'); // Les commentaires sur CE produit
		foreach($comments as $comm) {
			$comm->note = mt_rand(0,5);
			$comm->save();
			if($comm->save()) {
				echo 'gg';
			}
		}
		return 'success';
		*/
	}
	
	public function addcomment() {
		$success = false;
		if ($this->request->data) {
			$comment = Comment::create($this->request->data);

			if($me = Auth::check('default')) {
				$comment->user_id = $me['_id'];
				$comment->created = time();
				$comment->post_id = $this->request->id;
				
				$comment->note = (int) $this->request->data['note'];
				//DEBUG
				//$comment->debug = array('post' => $this->request->data, 'request' => $this->request->url);
			}
			else {
				$datas = array('errorcode' => '20', 'message' => 'No user connected');
				return $this->render(array('type' => 'json', 'data' => compact('success', 'datas')));
			}

			if ($comment->save()) {
				$success = true;
				$datas = $comment->to('array');
				return $this->render(array('type' => 'json', 'data' => compact('success', 'datas')));
			}
			else {
				$datas = array('errorcode' => '12', 'message' => 'Error when saving comment.');
				return $this->render(array('type' => 'json', 'data' => compact('success', 'datas')));
			}
		}
		else {
			$datas = array('errorcode' => '00', 'message' => 'Bad product selected.');
			return $this->render(array('type' => 'json', 'data' => compact('success', 'datas')));
		}
	}
	
	public function lastcomment() {
		$datas = Comment::find('first', array('order' => array('_id' => 'DESC')))->data();
		return $this->render(array('type' => 'json', 'data' => compact('datas')));
	}
	
	public function lastupload() {
	
		$datas = File::find('all', array('conditions' => array('_id' => array('51423fd3c59e6e8b5e000000', '51423fcac59e6ead60000000')), 'limit' => 10, 'order' => array('_id' => 'DESC')));
		//$datas = File::find('all', array('limit' => 10, 'order' => array('_id' => 'DESC')));
		
		
		/*
		$datas = File::find('all', array('conditions' => array('_id' => array('51423fcac59e6ead60000000')), 'limit' => 10, 'order' => array('_id' => 'DESC')));
		$newdata = $datas->next();
		$newdata->url = 'http://ec2-54-246-65-91.eu-west-1.compute.amazonaws.com/favstory/new/upload/videos/'.$newdata->unique_name.'.mp4';
		$datas->save();
		*/
		
		/*
		$newdata = $datas->next();
		$newdata->recipient = null;
		$newdata->url = null;
		$newdata->type = 'text';
		$newdata->size = 0;
		$newdata->extension = null;
		$newdata->productdescription = 'This is the text written by the creator of the product. Use me when type = "text".';
		$datas->save();
		*/
		/*
		$newdata = $datas->next();
		$newdata->productname = 'Video : How to use your product !';
		$datas->save();
		*/
		
		/*
		$data = File::find('first', array('order' => array('_id' => 'DESC')));
		$objarr = $data->data();
		unset($objarr['_id']);
		$bob = File::create($objarr);
		$bob->save();
		*/
		
		return $this->render(array('type' => 'json', 'data' => compact('datas')));
	}
}
?>