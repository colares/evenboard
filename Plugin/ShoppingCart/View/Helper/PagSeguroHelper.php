<?php
App::uses('AppHelper', 'View/Helper');

class PagSeguroHelper extends AppHelper {

    /**
     * Other helpers used by this helper
     *
     * @var array
     * @access public
     */
    var $helpers = array(
        'Html',
        'Form',
        'BForm',
    );

	/**
	 * View object
	 *
	 * @var Object
	 */
	var $View;
	
	/**
	 * Cart Array data
	 *
	 * @var string
	 */
	var $cart;

	/**
	 * Method to fecth View object
	 *
	 * @return void
	 * @author Thiago Colares
	 */
    protected function getView() {
        return $this->_View; // cake 2.0
    }


	/**
     * Before render callback. Called before the view file is rendered.
     *
     * @return void
     */
    public function beforeRender() {
        // View object and all its vars
        $this->View = $this->getView();
		if(isset($this->View->viewVars['cart']))
			$this->cart = $this->View->viewVars['cart'];
	}
	
	
	public function linkButton($cartId, $label = null){
		if ($label == null) {
			$label = __('Pay with PagSeguro');
		}

		return $this->Html->link(
	        $label,
	        '/profile/carts/doCheckout/' . $cartId,
	        array('class' => 'btn btn-success btn-large')
	    );
	}


	/**
	 * Returns the correct URl that redirects to checkout
	 *
	 * @return void
	 * @author 
	 **/
	public function doCheckoutURL($router = true){
		$params = array('plugin' => 'shopping_cart', 'controller' => 'carts', 'action' => 'doCheckout');
		if (!$router) {
			return $params;
		}
		return Router::url(array('plugin' => 'shopping_cart', 'controller' => 'carts', 'action' => 'doCheckout'));
	}

	/**
	 * Redirects user to PagSeguro in order to generea duplicate billet
	 *
	 * @return void
	 * @author 
	 **/
	public function duplicateButton($label = null){
		return $this->Html->link(
             __('Generate Duplicate at PagSeguro'),
			'https://pagseguro.uol.com.br/transaction/search.jhtml',
			array('target' => '_blank')
         );
	}


	public function acceptedMethods(){
		return $this->Html->css('/registration/css/pagseguro') . 
		'<small>Formas de pagamento aceitas pelo PagSeguro: </small>
		<div id="flags">
	        <span id="flag_pagseguro" title="Saldo PagSeguro">Saldo PagSeguro</span>
	        <span id="flag_visa" title="Visa">Visa</span>
	        <span id="flag_mastercard" title="MasterCard">MasterCard</span>
	        <span id="flag_diners" title="Diners">Diners</span>
	        <span id="flag_americanexpress" title="American Express">American Express</span>
	        <span id="flag_hipercard" title="Hipercard">Hipercard</span>
	        <span id="flag_aura" title="Aura">Aura</span>
	        <span id="flag_elo" title="Elo">Elo</span>
	        <span id="flag_plenocard" title="PLENOCard">PLENOCard</span>
	        <span id="flag_oipaggo" title="Oi Paggo">Oi Paggo</span>
	        <span id="flag_bradesco" title="Banco Bradesco">Banco Bradesco</span>
	        <span id="flag_itau" title="Banco Itaú">Banco Itaú</span>
	        <span id="flag_bb" title="Banco do Brasil">Banco do Brasil</span>
	        <span id="flag_banrisul" title="Banco Banrisul">Banco Banrisul</span>
	        <span id="flag_hsbc" title="Banco HSBC">Banco HSBC</span>
	        <span id="flag_boleto" title="Boleto">Boleto</span>
	    </div>';
		
	}

}	
