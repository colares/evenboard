<?php

/**
 * SHOPPING CART
 */
Router::connect(
	'/carts',
	array('plugin' => 'shopping_cart', 'controller' => 'carts', 'action' => 'index')
);

Router::connect(
	'/carts/:action/*',
	array('plugin' => 'shopping_cart', 'controller' => 'carts')
);