<?php

/**
 * CartProduct behavior
 *
 * Allow bound any product-like entity to ShoppingCart plugin
 *
 */
App::uses('CartProduct', 'ShoppingCart.Model');

class CartProductBehavior extends ModelBehavior {

    /**
     * Used to set CartProduct model
     *
     * @var string
     */
    protected $CartProduct;

    /**
     * Sets up the configuration for the model, and loads CartProduct models
     *
     * @param Model $model
     * @param array $config
     * @return void
     */
    public function setup(Model $model, $settings = array()) {

        $this->CartProduct = new CartProduct();
        $this->settings = $settings;
        $this->model = $model;
        if (!isset($this->settings['plugin']))
            $this->settings['plugin'] = null;
    }

    /**
     * Creates a new CartProduct bound to this record
     *
     * @param Model $model
     * @param boolean $created True if this is a new record
     * @return void
     */
    public function afterSave(Model $model, $created) {

        $data['CartProduct']['model'] = $model->name;
        $data['CartProduct']['alias'] = $model->alias;
        $data['CartProduct']['plugin'] = $this->settings['plugin'];
        $data['CartProduct']['foreign_id'] = $model->id; // last inserted id

        if ($created) {
            $this->CartProduct->create();
            $this->CartProduct->save($data);
        }
    }

    /**
     * Destroys the CartProduct bound to the deleted record
     *
     * @param Model $model
     * @return void
     */
    public function afterDelete(Model $model) {
        $this->CartProduct->deleteAll(array('CartProduct.foreign_id' => $model->id));
    }

	/**
	 * Count how many products had been acquired
	 *
	 * @return void
	 * @author Thiago Colares
	 */
	public function countAcquired($options = array()) {
		if(empty($options)) {
			$options['joins'] = array(
				array(
					'table' => 'cart_products',
					'alias' => 'CartProduct',
					'type' => 'INNER',
					'conditions' => array(
						$this->model->name . '.id = CartProduct.foreign_id'
					)
				),
				array(
					'table' => 'cart_items',
					'alias' => 'CartItem',
					'type' => 'INNER',
					'conditions' => array(
						'CartItem.cart_product_id = CartProduct.id'
					)
				)
			);
			$options['conditions']['CartProduct.model'] = $this->model->name;
			$options['conditions']['CartProduct.alias'] = $this->model->alias;
			$options['conditions']['CartProduct.plugin'] = $this->settings['plugin'];
		}		
        return $this->model->find('count', $options);
		
	}

}