<?php

use app\models\User;
use lithium\security\Password;

User::applyFilter('save', function($self, $params, $chain) {
    if ($params['data']) {
        $params['entity']->set($params['data']);
        $params['data'] = array();
    }
    if (!$params['entity']->exists()) {
        $params['entity']->password = Password::hash($params['entity']->password);
    }
    return $chain->next($self, $params, $chain);
});

?>