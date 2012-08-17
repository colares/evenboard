<?php

App::uses('ShoppingCartAppModel', 'ShoppingCart.Model');

class Cart extends ShoppingCartAppModel {

    /**
     * Model name
     * @var string
     */
    public $name = 'Cart';

    /**
     * Defines hasMany associations
     * @var array
     */
    public $hasMany = array(
        'CartItem' => array(
            'dependent' => true,
            'className' => 'ShoppingCart.CartItem'
        )
    );
    
    /**
     * 
     * @var type 
     */
    
    public $tempData = array();

	/*
		TODO Constructor __constructor?
	*/
    public function newCart($idUser=null, &$data = null) {
        //Delete carts not initialized
        if (!empty($idUser)) {
            $conditions['Cart.user_id'] = $idUser;    
            $conditions['Cart.payment_status_id'] = 9;
            $this->deleteAll($conditions);
        }
        
        if (!empty($data)) {
            $data['Cart']['payment_entity_id'] = 2;
            $data['Cart']['payment_entity_payment_method_id'] = null;
            $data['Cart']['payment_status_id'] = 9;
            $data['Cart']['total_cost'] = 0;
            if (!empty($idUser)) {
                $data['Cart']['user_id'] = $idUser;
            }            
        } else {
            //Initialize a new cart
            $this->tempData = array();
            $this->tempData['Cart']['payment_entity_id'] = 2;
            $this->tempData['Cart']['payment_entity_payment_method_id'] = null;
            $this->tempData['Cart']['payment_status_id'] = 9;
            $this->tempData['Cart']['total_cost'] = 0;
            if (!empty($idUser)) {
                $this->tempData['Cart']['user_id'] = $idUser;
            }            
        }

    }


    /**
     * Add a set of products in a row
     *
     * @param string $products model | plugin | foreign_id
     * @return void
     * @author Thiago Colares
     */
    public function addItems($products = array(), &$data = null){
        if(is_array($products) && !empty($products)){
            foreach($products as $product){
                $this->addItem($product, $data);
            }
        }
    }
    

    /**
     * Add a product in cart
     *
     * @param string $product 
     * @return void
     * @author Thiago Colares
     */
    public function addItem($product = array(), &$data = null){
        $CartProduct = ClassRegistry::init('ShoppingCart.CartProduct');
        $res = $CartProduct->get($product['CartProduct']);

        if (!empty($data)) {
            if($res){
                $data['CartItem'][] = array(
                    'cart_product_id' => $res['CartProduct']['id'],
                    'quantity' => 1,
                    'total_cost' => $res['CartProduct']['price']
                );
            }
            $data['Cart']['total_cost'] += $res['CartProduct']['price'];
        } else {
            if($res){
                $this->tempData['CartItem'][] = array(
                    'cart_product_id' => $res['CartProduct']['id'],
                    'quantity' => 1,
                    'total_cost' => $res['CartProduct']['price']
                );
            }
            $this->tempData['Cart']['total_cost'] += $res['CartProduct']['price'];    
        }
        
    }
	

	/**
	 * Save cart an its cart_items!
	 *
	 * @return void
	 */
    public function saveCart() {
        if (empty($this->tempData))
			return array('success' => true);
        return array('success' => $this->saveAll($this->tempData,array('deep' => true)));
    }
    
    public function getData() {
        return $this->tempData;
    }
    
	/**
	 * Set an id as key from position
	 *
	 * @param string $data 
	 * @param string $id 
	 * @return void
	 * @author Thiago Colares
	 */
	private function _indexById(&$data, $id = 'id'){
		$aux = array();
		foreach($data as $item){
			$aux[$item[$id]] = $item;
		}
		$data = $aux;
	}

	/**
	 * Get all items and its products from a given Cart
	 *
	 * @param string $userId 
	 * @return void
	 * @author Thiago Colares
	 */
    public function getCart($id = null, $by = 'id', $payable = 1) {
        $this->recursive = 1;
        $options = array();

        $options['joins'] = array(
            array(
                'table' => 'payment_statuses',
                'alias' => 'PaymentStatus',
                'type' => 'INNER',
                'conditions' => array(
                    'PaymentStatus.id = Cart.payment_status_id'
                )
            )
        );

		$options['conditions'] = array(
			"Cart.$by" => $id,
			"PaymentStatus.payable" => $payable,
		);
        
        $res = $this->find('first', $options);

		if($res){
			$this->_indexById($res['CartItem'], 'cart_product_id');

			$products = array();
			foreach ($res['CartItem'] as $item) {
				$proTmp = $this->CartItem->CartProduct->getInfoById($item['cart_product_id']);
				$res['CartItem'][$proTmp['CartProduct']['id']]['CartProduct'] = $proTmp['CartProduct'];
			}
		}

		return $res;
    }
    
    public function getUserCarts($idUser){
        $options['joins'] = array(
            array(
                'table' => 'payment_statuses',
                'alias' => 'PaymentStatus',
                'type' => 'INNER',
                'conditions' => array(
                    'Cart.payment_status_id = PaymentStatus.id'
                )
            ),
            array(
                'table' => 'cart_items',
                'alias' => 'CartItem',
                'type' => 'INNER',
                'conditions' => array(
                    'Cart.id = CartItem.cart_id'
                )
            ),
            array(
                'table' => 'cart_products',
                'alias' => 'CartProduct',
                'type' => 'INNER',
                'conditions' => array(
                    'CartProduct.id = CartItem.cart_product_id'
                )
            )
        );
        
        $options['conditions']['Cart.user_id'] = $idUser;
        
        $options['fields'] = array('Cart.*', 'CartItem.*', 'PaymentStatus.*', 'CartProduct.*');
        
        $carts = $this->find('all', $options);
        
        $res = array();
        foreach($carts as $cart){
            $res[$cart['Cart']['id']]['Cart'] = $cart['Cart'];
            $res[$cart['Cart']['id']]['PaymentStatus'] = $cart['PaymentStatus'];
            $res[$cart['Cart']['id']]['CartItem'][$cart['CartItem']['id']] = $cart['CartItem'];
            $cartProduct = $this->CartItem->CartProduct->getInfoById($cart['CartProduct']['id']);
            $res[$cart['Cart']['id']]['CartItem'][$cart['CartItem']['id']]['CartProduct'] = $cartProduct['CartProduct'];
        }
        
        return $res;
    }

}