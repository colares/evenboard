<?php

App::uses('ShoppingCartAppModel', 'ShoppingCart.Model');

class CartProduct extends ShoppingCartAppModel {

    public $name = 'CartProduct';

	/**
	 * Fetch product details
	 *
	 * @param string $id product id. The cart_products foreign_key
	 * @return void
	 * @author Thiago Colares
	 */
	public function get($product = null){
		$res = $this->find('first',array(
			'conditions' => array(
				'model' => $product['model'],
				'plugin' => $product['plugin'],
				'foreign_id' => $product['id']             
			)
		));
		return $this->getInfo($res);
	}
	
	
	/**
	 * Fecth data from the real product
	 *
	 * undocumented function
	 *
	 * @param string $product 
	 * 	'CartProduct' => array(
	 * 		'id' => '3',
	 * 		'model' => 'ActivityVariation',
	 * 		'alias' => 'ActivityVariation',
	 * 		'table' => 'activity_variations',
	 * 		'plugin' => 'Schedule',
	 * 		'foreign_id' => '3'
	 * )
	 * @return void
	 * @author Thiago Colares
	 */
	public function getInfo($product = null){
		// ClassRegistry
		// 	loads the file, adds the instance to the a 
		//	object map and returns the instance. This is an easy and convenient 
		//	way to access models.
		// 		${$product['model']}
		if(isset($product['CartProduct']['plugin']) && !empty($product['CartProduct']['plugin'])){
			$Model = ClassRegistry::init($product['CartProduct']['plugin'] . '.' . $product['CartProduct']['model']);
		} else {
			$Model = ClassRegistry::init($product['CartProduct']['model']);
		}
		$res = $Model->cart__getInfo($product['CartProduct']['foreign_id']);

		// Appending
		foreach ($res as $key => $value) {
			$product['CartProduct'][$key] = $value;
		}
		return $product;
	}
	
	
	/**
	 * Fecth data from the real product
	 *
	 * undocumented function
	 *
	 * @param string $productId 
	 * @return void
	 * @author Thiago Colares
	 */
	public function getInfoById($productId = null){
		$product = $this->findById($productId);
		return $this->getInfo($product);
	}
	

	/**
	 * Fecth all AVAILABLER products from a given type
	 *
	 * @param string $product 
	 * 	'CartProduct' => array(
	 * 		'model' => 'ActivityVariation',
	 * 		'alias' => 'ActivityVariation',
	 * 		'table' => 'activity_variations',
	 * 		'plugin' => 'Schedule',
	 * )
	 * @return void
	 * @author Thiago Colares
	 */
	public function getAvailableProducts($product = array(), $productOptions = array()){

		// Get the model instance
		if(isset($product['CartProduct']['plugin']) && !empty($product['CartProduct']['plugin'])){
			$Model = ClassRegistry::init($product['CartProduct']['plugin'] . '.' . $product['CartProduct']['model']);
		} else {
			$Model = ClassRegistry::init($product['CartProduct']['model']);
		}

		// Preparing to find
		$options['joins'] = array(
            array(
                'table' => 'cart_products',
                'alias' => 'CartProduct',
                'type' => 'INNER',
                'conditions' => array(
                    'CartProduct.foreign_id = ' . $product['CartProduct']['model'] . '.id'
                )
            )
        );
		$options['conditions']['CartProduct.model'] = $product['CartProduct']['model'];
		$options['conditions']['CartProduct.alias'] = $product['CartProduct']['alias'];
		$options['conditions']['CartProduct.plugin'] = $product['CartProduct']['plugin'];

		$options['fields'] = array('CartProduct.*', $product['CartProduct']['model'] . '.*');

		$options = $this->_mergeFindOptions($options, $productOptions);
		return $Model->find('all', $options);
	}

}
