<?php


Router::connect('/', array('plugin' => 'registration', 'controller' => 'registrations', 'action' => 'store'));

/**
 * REGISTRATION
 */
Router::connect(
	'/registrations',
	array('plugin' => 'registration', 'controller' => 'registrations', 'action' => 'index')
);

Router::connect(
	'/registrations/:action/*',
	array('plugin' => 'registration', 'controller' => 'registrations')
);
Router::connect(
	'/admin/registrations/:action/*',
	array('plugin' => 'registration', 'controller' => 'registrations', 'prefix' => 'admin')
);