<?php

App::uses('ShoppingCartAppController', 'ShoppingCart.Controller');

/**
 * Stores Controller
 */
class StoresController extends ShoppingCartAppController {

    /**
     * Controller name
     *
     * @var string
     * @access public
     */
    public $name = 'Stores';

    /**
     * Models used by Controller
     * @access public
     */
    public $uses = array('ShoppingCart.Store');

	public function index() {
		
	}

}