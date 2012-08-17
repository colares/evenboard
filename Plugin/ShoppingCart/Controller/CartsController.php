<?php

App::uses('ShoppinCartAppController', 'ShoppingCart.Controller');

/**
 * Carts Controller
 */
class CartsController extends ShoppingCartAppController {

    /**
     * Controller name
     *
     * @var string
     * @access public
     */
    public $name = 'Cart';

    /**
     * Models used by Controller
     * @access public
     */
    public $uses = array('ShoppingCart.Cart');

	public function checkout() {
		
	}

}