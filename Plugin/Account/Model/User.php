<?php

App::uses('AccountAppModel', 'Account.Model');
App::uses('AuthComponent', 'Controller/Component');

/**
 * A user is a software agent, who uses the computer or network service.
 *
 * @author Thiago Colares
 */
class User extends AccountAppModel {
    public $name = 'User';
	public $belongsTo = array('Account.Role');
	public $actsAs = array('Acl' => array('type' => 'requester'));

    /**
     * Handlers executed by add method
     * @var array
     */
    var $addHandlers = array(
        '_addUser',
        '_addRole',
        '_addProfile',
        '_addCart',
        '_finalSave'
        // '_sendWelcomeEmail'
        // '_putRoleHandler',
        // 'addAdminPaymentHandler',
        // 'addAdminLogHandler',
        // 'formatTransactionDate',
        // 'finalSaveHandler',
        // 'saveRegistrationItemHandler',
        // 'savePaymentLog',
        // 'sendWelcomeConsolidationLinkHandler'
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

    public $hasOne = array(
        'Profile' => array(
            'className'    => 'Account.Profile',
            'foreignKey' => 'id',
            'dependent'    => true
        )
    );

    /**
     * Defines hasMany associations
     * @var array
     */
    public $hasMany = array(
        'Cart' => array(
            'dependent' => true,
            'className' => 'ShoppingCart.Cart'
        )
    );

	/**
	 * Set of code that is trigget before save
	 *
	 * @return boolean
	 * @author Thiago Colares
	 */
	public function beforeSave() {		
		// Password hashing is no longer automatic since CakePHP 2.0.0
	    if (isset($this->data[$this->alias]['password'])) {
	        $this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['password']);
	    }
        
        unset($this->data[$this->alias]['confirm_password']);
        
	    return true;
	}

	/**
	 * Array with validation rules
	 *
	 * @var string
	 */
    public $validate = array(
		'email' => array(
			'email' => array(
				'rule' => 'email',
				'message' => 'Por favor, utilize o formato contato@exemplo.com.br'
			),
			'isUnique' => array(
				'rule' => 'isUnique',
		        'message' => 'This email is already in use'
			),
			'notempty' => array(
                'rule' => array('notempty'),//, array('on' => 'create')),
                'message' => 'Este campo deve ser preenchido.',
                // 'on' => 'create'
            ),
		),
        'role' => array(
            'valid' => array(
                'rule' => array('inList', array('admin', 'author')),
                'message' => 'Please enter a valid role',
                'allowEmpty' => false
            )
        ),
        'password' => array(
            'identicalFieldValues' => array(
                'rule' => array('identicalFieldValues', 'confirm_password'),
                'message' => 'As senhas devem ser idênticas.'
            ),
            'notempty' => array(
                'rule' => array('notempty'),//, array('on' => 'create')),
                'message' => 'Este campo deve ser preenchido.',
                // 'on' => 'create'
            ),
        ),
        'confirm_password' => array(
            'notempty' => array(
                'rule' => array('notempty', array('on' => 'create')),
                'message' => 'Este campo deve ser preenchido.',
                'on' => 'create'
            ),
        )
    );
    
    /**
     *
     * @param array $field 
     * @param string $compare_field field that will be compared
     * @return boolean 
     * @author Rafael Ávila
     */
    function identicalFieldValues($field=array(), $compare_field=null) {
        if (!isset($this->data[$this->name][$compare_field]))
                return true;
        
        foreach ($field as $key => $value) {
            $v1 = $value;
            $v2 = $this->data[$this->name][$compare_field];
            if ($v1 !== $v2) {
                return false;
            } else {
                continue;
            }
        }
        return true;
    }
    
    function getStatus(){
        $status[0] = __('Inactive');
        $status[1] = __('Active');
        
        return $status;
    }


    /**
     * Add user about to be registered
     *
     * @package account
     * @return array Success array
     * @author 
     **/
    protected function _addUser($data = null){
        $this->tempData['User'] = $data['User'];
        unset($this->tempData['User']['id']);
        if($this->tempData['User']) {
            return array('success' => true);
        } else {
            return array('success' => false);
        }
    }

    /**
     * Add user role
     *
     * @package account
     * @author 
     **/
    protected function _addRole() {
        
        $Role = ClassRegistry::init('Account.Role');
        $role = $Role->findByAlias('registered');
        if($role) {
            $this->tempData['User']['role_id'] = $role['Role']['id'];
            return array('success' => true);
        } else {
            return array('success' => false, 'error' => __('The choosed Role could not be found!'));
        }
    }


    /**
     * Add user profile
     *
     * @package account
     * @author 
     **/
    protected function _addProfile($data = null) { 
        $this->tempData['Profile'] = $data['Profile'];
        if($this->tempData['Profile']) {
            return array('success' => true);
        } else {
            return array('success' => false);
        }
    }

    /**
     * Add user products
     *
     * @package account
     * @author 
     **/
    protected function _addCart($data = null) { 

        // First, add new Cart
        $Cart = ClassRegistry::init('ShoppingCart.Cart');
        $Cart->newCart(null, $this->tempData);

        // Then, add CartItems
        $Cart->addItems($data['CartItem'], $this->tempData);

        $cartCreated = false;
        if(!empty($this->tempData['CartItem']) && !empty($this->tempData['Cart'])) {
            $cartCreated = true;
        }

        // Little adjustment :)
        $this->tempData['Cart'] = array(array_merge($this->tempData['Cart'], array('CartItem' => $this->tempData['CartItem'])));//, 'CartItem' => $this->tempData['CartItem']);
        // unset($this->tempData['Cart']);
        unset($this->tempData['CartItem']);    

        if($cartCreated) {
            return array('success' => true);
        } else {
            return array('success' => false, 'message' => __('Cart could not be created.'));
        }
    }

}

?>