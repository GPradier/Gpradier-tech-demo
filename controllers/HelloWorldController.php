<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2013, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace app\controllers;

use lithium\net\http\Media;

class HelloWorldController extends \lithium\action\Controller {

	public function index() {
		return $this->render(array('layout' => false));
	}

	public function to_string() {
		return "Hello World";
	}

	public function to_json() {
		//return $this->render(array('json' => array('success' => true, 'datas' => array('_id' => '00011ad28', 'username' => 'balde@mail.com', 'location' => 'Paris'))));
		
		return $this->render(array('json' => array('success' => false, 'datas' => array('errorcode' => '27', 'message' => 'Mot de passe invalide'))));
	}
	
	public function tryit() {
		//return $this->render(array('template' => 'index', 'layout' => false));
		
		Media::type('mobile', 'text/mobile', array(
			'layout' => false,
			'view' => 'lithium\template\View'
		));

		return $this->render(array('template' => 'index', 'layout' => false));
	}
}

?>