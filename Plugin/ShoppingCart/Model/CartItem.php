<?php

App::uses('ShoppingCartAppModel', 'ShoppingCart.Model');

class CartItem extends ShoppingCartAppModel {

    public $name = 'CartItem';
    var $useTable = 'cart_items';
    public $belongsTo = array(
        'Cart' => array(
            'className' => 'ShoppingCart.Cart'
        ),
        'CartProduct' => array(
            'className' => 'ShoppingCart.CartProduct'
        ),
    );


    /**
     * Returns all Keywords of a given Product
     * @param integer $id
     * @param string $separator
     * @return array|string
     */
    public function getCartItems($id = null, $separator = null) {
        $this->recursive = 1;

        $res = $this->find('all', array(
            'conditions' => array(
                'CartItem.cart_id' => $id
            )
                ));

        if (empty($tags))
            return '';
        elseif ($separator == null)
            return $tags;
        else
            return implode($separator, $tags);
    }

    public function getItem(){
        
    }

    /**
     * Retorna quantos produtos foram comprados
     *
     * @return void
     * @author 
     **/
    public function getHowManyProduct($productId = null, $paymentStatuses = array(1,2,3,4,5,10,11)) {
        $options['joins'] = array(
            array(
                'table' => 'carts',
                'alias' => 'Cart',
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

        $options['conditions'][] = array(
            'CartItem.cart_product_id' => $productId,
            'Cart.payment_status_id' => $paymentStatuses
        );

        return $this->find('count', $options);
    }

    /**
     * Return all bought products
     *
     * @return void
     * @author 
     **/
    public function getBoughtProducts($userId = null, $options = array(), $paymentStatuses = array(1,2,3,4,5,10,11)) {

        $defaultOptions['joins'] = array(
            array(
                'table' => 'carts',
                'alias' => 'Cart',
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

        if (!empty($paymentStatuses)) {
            $defaultOptions['conditions']['Cart.payment_status_id'] = $paymentStatuses;
        }
        $defaultOptions['conditions']['Cart.user_id'] = $userId;
        $defaultOptions['fields'] = array('CartItem.*','CartProduct.*');


        if (isset($options['joins'])) {
            $defaultOptions['joins'] = array_merge($defaultOptions['joins'], $options['joins']);
        }

        if (isset($options['conditions'])) {
            $defaultOptions['conditions'] = array_merge($defaultOptions['conditions'], $options['conditions']);
        }

        if (isset($options['fields'])) {
            $defaultOptions['fields'] = array_merge($defaultOptions['fields'], $options['fields']);
        }
debug($defaultOptions);
        return $this->find('all', $defaultOptions);
    }

}