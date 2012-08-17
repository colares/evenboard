<?php

App::uses('ShoppinCartAppController', 'ShoppingCart.Controller');

/**
 * Carts Controller
 */
class CartProductsController extends ShoppingCartAppController {

    /**
     * Controller name
     *
     * @var string
     * @access public
     */
    public $name = 'CartProduct';

    /**
     * Models used by Controller
     * @access public
     */
    public $uses = array('ShoppingCart.CartProduct');

	public function getInfo() {
		return $this->CartProduct->getInfo($this->request->params['product']);
	}

}