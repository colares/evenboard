<?php

App::uses('AuthComponent', 'Controller/Component');

/**
 * A user is a software agent, who uses the computer or network service.
 *
 * @author Thiago Colares
 */
class User extends AppModel {
    public $name = 'User';
	public $belongsTo = array('Role');
	public $actsAs = array('Acl' => array('type' => 'requester'));

    public $hasOne = array(
        'Profile' => array(
            'className'    => 'Profile',
            'foreignKey' => 'id',
            'dependent'    => true
        )
    );

    /*
     * @todo make it dynamically
     */
    public $hasMany = array(
        'Cart' => array(
            'className'    => 'ShoppingCart.Cart',
            'foreignKey' => 'user_id'
        )
    );
	
	/**
	 * Used to determine a Parent -> Child relationship
	 *
	 * @return mixed
	 * @author Thiago Colares
	 */
    function parentNode() {
        if (!$this->id && empty($this->data)) {
            return null;
        }
        if (isset($this->data['User']['role_id'])) {
            $roleId = $this->data['User']['role_id'];
        } else {
            $roleId = $this->field('role_id');
        }
        if (!$roleId) {
            return null;
        } else {
            return array('Role' => array('id' => $roleId));
        }
    }
}