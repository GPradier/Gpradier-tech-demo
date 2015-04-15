<?php
namespace app\controllers;

use lithium\security\Auth;
use lithium\storage\Session;
use app\models\Users;

use app\models\Files;

class FilesController extends \lithium\action\Controller {

//public function upload_cv() {
public function index() {

	if(!$user = Auth::check('default')) {
		return false;
	}
	if(!$this->request->data) {
		return false;
	}
	$user = Users::find($user['_id']);//array to object
	
	
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
			
			$file = Files::create();
			$file->realname = $real_name;
			$file->extension = $extension;
			$file->size = $size;
			$file->type = $type;
			$file->user = $user->_id;
			$file->created_at = time();
			$file->unique_name = sha1($user->_id .'-'. $file->_id .'-'. time());
			
			if(move_uploaded_file($tmp_name, '/files_upload/'. $file->unique_name .'.'. $extension))
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

public function view($id_file) {
	if(!$user = Auth::check('default')) {
		//return false;
		return $this->redirect('/', array('exit' => true));
	}
	
	$file_request = Files::find('first', array('conditions' => array('_id' => $id_file)));
	
	if($file_request) {
		$file = '/files_upload/'. $file_request->unique_name .'.'. $file_request->extension;

		if (file_exists($file)) {
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
//			header('Content-Disposition: attachment; filename='.basename($file));
			header('Content-Disposition: attachment; filename='.$file_request->realname);
			header('Content-Transfer-Encoding: binary');
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('Content-Length: ' . filesize($file));
			ob_clean();
			flush();
			readfile($file);
			exit;
		}
	}
}

public function all() {
	$user = Auth::check('default');
	$user = Users::find($user['_id']);
	$all = Files::find('all');
	
	$my_docs = Files::find('all', array('conditions' => array('user' => $user['_id'])));
	
	return compact('all', 'my_docs');
}

public function show() {
	echo '<pre>';
	var_dump(Files::find('all')->to('array'));
	echo '</pre>';
	exit();
	return ' ';
}
}
?>