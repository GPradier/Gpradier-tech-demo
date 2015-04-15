<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2012, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace app\controllers;

use lithium\security\Auth;
use lithium\storage\Session;
use app\models\Sites;
use app\models\Candidatures;
use app\models\Champs;
use app\models\Users;
use app\models\Bookmarks;

use app\models\Logs;

use lithium\util\Inflector;

class LogsController extends \lithium\action\Controller {
	public function index() {
		$logs = Logs::all();
		return compact('logs');
	}
	
	public function command() {
		$command = Logs::find('first', array(
			'conditions' => array('_id' => $this->request->args[0])
		));
		return $command->print;
	}
}
?>