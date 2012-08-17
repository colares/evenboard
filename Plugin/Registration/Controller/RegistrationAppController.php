<?php

class RegistrationAppController extends AppController {
	public $components = array(
		'ShoppingCart.CartHandler' => array(
			'pluginName' => 'Registration',
			'modelName' => 'Registration'
		)
	);
	
	public function addToCart(){
		return $this->CartHandler->addToCart();
	}		
	
}


?>