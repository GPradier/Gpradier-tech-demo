<?php
namespace app\controllers;

use lithium\security\Auth;
use lithium\storage\Session;
use app\models\User;
use app\models\File;

use lithium\net\http\Media;

class UploadController extends \lithium\action\Controller {

	public static $upload_repertory = '/upload_favstory/';

	public function dynamic() {
		header('HTTP/1.1 314 Not Modified');
		var_dump($this->request->data);

		var_dump($this->request->data['upload']['tmp_name']);

		//if(move_uploaded_file($tmp_name, self::$upload_repertory . $file->unique_name .'.'. $extension))

		exit();
	}

	public function index() {
		if(!$user = Auth::check('default')) {
			return false;
		}

		if(!$this->request->data) {
			return false;
		}

		($this->request->id ? $id_related = $this->request->id : $id_related = 0);
		$user = User::find($user['_id']);//array to object


		$size = $this->request->data['upload']['size'];
		$maxSize = 8388608;

		$extension = end(explode(".", $this->request->data['upload']['name']));
		$allowedExtensions = array("txt","csv","htm","html","xml",
			"css","doc","docx","xls","rtf","ppt","pdf","jpg","jpeg","gif","png");

		$type = $this->request->data['upload']['type'];
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
				$tmp_name = $this->request->data['upload']['tmp_name'];
				$real_name = $this->request->data['upload']['name'];

				$file = File::create();
				$file->realname = $real_name;
				$file->extension = $extension;
				$file->size = $size;
				$file->type = $type;
				$file->related = $id_related;
				$file->user = $user->_id;
				$file->created_at = time();
				$file->unique_name = sha1($user->_id .'-'. $file->_id .'-'. time());

				if(move_uploaded_file($tmp_name, self::$upload_repertory . $file->unique_name .'.'. $extension))
				{
					//$file->save();
					if($file->save()) {
						header('HTTP/1.1 999 ID: '. $file->_id .'');
						exit();
					}
						header('HTTP/1.1 888 FAIL');
						exit();

					return true;
				}
			}
		}
	}

	public function delete() {
		if(!$me = Auth::check('default')) {
			return $this->redirect(array('controller' => 'Sessions', 'action' => 'add', 'args' => base64_encode($this->request->url)));
		}

		$post = File::find('first', array('conditions' => array('_id' => $this->request->id, 'user' => $me['_id'])));

		if (empty($post)) {
			return $this->redirect(array('controller' => 'posts', 'action' => 'index'));
			// log tentative de delete sur item inexistant
		}

		if ($post->delete()) {
			return $this->redirect(array('controller' => 'upload', 'action' => 'all'));
		}

		return false;
	}

	public function frame() {
		$id_related = $this->request->id;
		$content = compact('id_related');

		Media::type('html', array('text/html'), array(
			// template settings...
			'paths' => array('layout' => false)
		));

		return $this->render(array('type' => 'html', $content, array('paths' => array('layout' => false))));
	}

	public function view() {
		$file_request = File::find('first', array('conditions' => array('_id' => $this->request->id)));

		if($file_request) {
			$file = self::$upload_repertory . $file_request->unique_name .'.'. $file_request->extension;

	/*
			$headers = getallheaders();
			header('Content-type: '.$file_request->type);
			header("Content-Disposition: inline; filename=\"{$file_request->unique_name}\";");

			if (ereg($file_request->unique_name, $headers['If-None-Match'])) {
				header('HTTP/1.1 304 Not Modified');
				header("ETag: \"{$file_request->unique_name}\"");
			}
			else {
			// TEMPORAIRE :
				$expires = 60*60*24*365;
				header("Pragma: public");
				header("Cache-Control: maxage=".$expires);
				header('Expires: ' . gmdate('D, d M Y H:i:s', time()+$expires) . ' GMT');
				header("ETag: \"{$file_request->unique_name}\"");
			}

			readfile($file);
			exit();
	*/

			//CACHE
			$headers = getallheaders();
			// if Browser sent ID, we check if they match
			if (ereg($file_request->unique_name, $headers['If-None-Match']))
			{
			header('HTTP/1.1 304 Not Modified');
			}
			else {

			if (file_exists($file)) {
				header('Content-Description: File Transfer');
				//header('Content-Type: application/octet-stream');
				header('Content-Type: '. $file_request->type);
	//			header('Content-Disposition: attachment; filename='.basename($file));
				// DL image
				//header('Content-Disposition: attachment; filename='.$file_request->realname);

				// Inline
				header("Content-Disposition: inline; filename=\"{$file_request->unique_name}\";");

				header('Content-Transfer-Encoding: binary');
//				header('Expires: 0');
//				//header('Cache-Control: must-revalidate');
				$expires = 60*60*24*14;
				header("Pragma: public");
				header("Cache-Control: maxage=".$expires);
				header('Expires: ' . gmdate('D, d M Y H:i:s', time()+$expires) . ' GMT');



				header('Content-Length: ' . filesize($file));

				// Création du cache avec un hash :
				header("ETag: \"{$file_request->unique_name}\"");
				ob_clean();
				flush();
				readfile($file);
				exit;
			}
			}
		}
	}

	public function url() {
		$images = File::find('all');
		foreach($images as $image) {
			$image->url = 'http://ec2-54-246-65-91.eu-west-1.compute.amazonaws.com/favstory/new/upload/view/'. $image->_id;
			$image->save();
		}

		return 'end';
	}

	public function all() {
		$user = Auth::check('default');
		$user = User::find($user['_id']);
		$all = File::find('all');

		$my_docs = File::find('all', array('conditions' => array('user' => $user['_id']), 'order' => array('_id' => 'DESC')));

		return compact('all', 'my_docs');
	}

	public function show() {
		echo '<pre>';
		var_dump(File::find('all')->to('array'));
		echo '</pre>';
		exit();
		return ' ';
	}
}
?>