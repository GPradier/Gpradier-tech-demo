<?php
namespace app\models;

use lithium\util\Validator;
	
class User extends \lithium\data\Model {
	public $validates = array(
		'username' => array(
			array('notEmpty','message' => 'Please supply a username.'),
			array('email','message' => 'Please supply a valid email.'),
			array('uniqueUsername','message' => 'This username is already taken.'),
		),
		'password' => array(
			array('notEmpty','required' => true,'message' => 'Please supply a password.')
		),
		'firstname' => array(
			array('notEmpty','required' => true,'message' => 'Please supply a firstname.')
		),
		'lastname' => array(
			array('notEmpty','required' => true,'message' => 'Please supply a lastname.')
		),
		'gender' => array(
			array('inList' => array(1,2,3),'message' => 'Please select your gender.')
		)
	);
	
	protected $_schema = array(
		'_id'       => array('type' => 'id'),
		'username' => array('type' => 'string'),
		'firstname'  => array('type' => 'string'),
		'lastname'     => array('type' => 'string')
	);

	public function title($user) {
		return "{$user->firstname} {$user->lastname} <{$user->username}>";
	}
	//Validator::add('isEmail', '/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,6}$/');
}

Validator::add('uniqueUsername', function($value, $rule, $options) { // Cree un validator qui test le username unique
	$conflicts = User::count(array('username' => $value));
	if($conflicts) return false;
	return true;
});
?>