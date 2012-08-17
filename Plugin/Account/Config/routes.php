<?php

Router::connect('/login', array('plugin' => 'account', 'controller' => 'users', 'action' => 'login'));
Router::connect('/logout', array('plugin' => 'account', 'controller' => 'users', 'action' => 'logout'));

/**
 * USER
 */
Router::connect(
	'/users/:action/*',
	array('plugin' => 'account', 'controller' => 'users')
);
Router::connect(
	'/admin/users/:action/*',
	array('plugin' => 'account', 'controller' => 'users', 'prefix' => 'admin')
);

/**
 * PROFILE
 */
Router::connect(
	'/profiles/:action/*',
	array('plugin' => 'account', 'controller' => 'profiles')
);
Router::connect(
	'/admin/profiles/:action/*',
	array('plugin' => 'account', 'controller' => 'profiles', 'prefix' => 'admin')
);

