<?php

namespace app\controllers;

class PagesController extends \lithium\action\Controller {

	public function view() {
		$options = array();
		$path = func_get_args();

		if (!$path || $path === array('home')) {
			$path = array('home');
			$options['compiler'] = array('fallback' => true);
		}

		$options['template'] = join('/', $path);
		return $this->render($options);
	}
}
?>