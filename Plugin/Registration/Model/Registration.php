<?php

App::uses('RegistrationAppModel', 'Registration.Model');
App::uses('CakeEmail', 'Network/Email');

class Registration extends RegistrationAppModel {
	public $actsAs = array('ShoppingCart.CartProduct' => array('plugin' => 'Registration'));
    public $belongsTo = array(
        'Registration.RegistrationPeriod',
        'Registration.RegistrationType'
    );

	/**
     * Handlers executed by add method
     * @var array
     */
    var $addHandlers = array(
        '_addUser',
        '_addRole',
        '_addProfile',
        '_addRegistration',
        '_finalSave',
        '_sendWelcomeEmail'
        
		//'_putRoleHandler',
		// 'addAdminPaymentHandler',
		// 'addAdminLogHandler',
		// 'formatTransactionDate',
		// 'finalSaveHandler',
		// 'saveRegistrationItemHandler',
		// 'savePaymentLog',
		// 'sendWelcomeConsolidationLinkHandler'
    );

	public function cartTotal($idRegistration=null) {
        $options = array();

        $options['joins'] = array(
            array(
                'table' => 'cart_products',
                'alias' => 'CartProduct',
                'type' => 'INNER',
                'conditions' => array(
                    'Registration.id = CartProduct.foreign_id'
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

        $options['conditions']['CartProduct.model'] = 'Registration';
        $options['conditions']['CartProduct.alias'] = 'Registration';

        if ($idRegistration != null)
            $options['conditions']['Registration.id'] = $idRegistration;

        return $this->find('count', $options);
    }


    /**
     * Used by ShoppingCart
     *
     * @package registration
     * @author 
     **/
    public function getAvailableProducts() {

        $product = array('CartProduct' => array(
            'model' => 'Registration',
            'alias' => 'Registration',
            'table' => 'registrations',
            'plugin' => 'Registration'
        ));

        $options['joins'] = array(
            array(
                'table' => 'registration_types',
                'alias' => 'RegistrationType',
                'type' => 'INNER',
                'conditions' => array(
                    'Registration.registration_type_id = RegistrationType.id'
                )
            ),
            array(
                'table' => 'registration_periods',
                'alias' => 'RegistrationPeriod',
                'type' => 'INNER',
                'conditions' => array(
                    'Registration.registration_period_id = RegistrationPeriod.id'
                )
            )
        );

        $now = date("Y-m-d H:i:s");

        $options['conditions']['RegistrationPeriod.begin_date <'] = $now;
        $options['conditions']['RegistrationPeriod.end_date >='] = $now;
        $options['conditions']['RegistrationType.off_site'] = 0;

        $options['fields'] = array('Registration.id', 'Registration.price', 'RegistrationType.name', 'RegistrationPeriod.end_date');

        $CartProduct = ClassRegistry::init('ShoppingCart.CartProduct');
        $availableProducts = $CartProduct->getAvailableProducts($product, $options);
        

        // $CartItem = ClassRegistry::init('ShoppingCart.CartItem');
        // $CartItem->getHowManyBoughtProduct(array(2));
        
        // $items = $this->find('all', $options);

  //       $result = array();

  //       $max = Configure::read('EventMaxRegisters');
  //       foreach ($items as $item) {
  //           $total = $this->cartTotal($item['Registration']['id']);
  //           $result[] = array(
  //               'id' => $item['Registration']['id'],
  //               'price' => $item['Registration']['price'],
  //               'type' => $item['RegistrationType']['name'],
  //               'full' => $total >= $max ? true : false,
        //      'end_date' => $item['RegistrationPeriod']['end_date'],
  //               'registered' => $this->__isRegisteredUser()
  //           );
  //       }
  //       return $result;

        return $availableProducts;		
    }




    /**
     * Add registration data
     *
     * @package registration
     * @return array Success array
     * @author 
     **/
    protected function _addRegistration($data = null){
        if (!empty($_SESSION['CartItem']['Registration'])) {
            // now it only possible to register one
            $this->tempData['Registration'] = $_SESSION['CartItem']['Registration'][0];
            return array('success' => true);
            
        } elseif (!empty($data['Registration'])) {
            $this->tempData['Registration'] = $data['Registration'];
            return array('success' => true);
        } else {
            return array('success' => false, 'message' => __('Registration option could not be found.'));
        }
    }


    /**
     * After registration, perform the first login
     *
     * @return void
     * @author 
     **/
    protected function _firstLoginAndRedirect() {
        $this->Session->setFlash(
                __('Welcome! We see you very soon in <strong>%s</strong>!', Configure::read('ProjectFullTitle')) . '<br>' .
                __("Registration was successful. <strong>Now you must to make the payment to ensure your entry.</strong>"), 'default', array('class' => 'alert alert-success')
        );

        $user = $this->User->findById($this->User->getInsertID());
        $this->Auth->login($user['User']);
        $this->redirect('/profile/carts/add');
    }


    /**
     * Saves the entity and its dependents
     * @return array
     */
    protected function _finalSave() {

        $User = ClassRegistry::init('Account.User');
        $res = $User->saveAll($this->tempData, array('deep' => true));

        if ($res === true)
            return array('success' => true);
        else{
            // @todo fetch all associed models errors
            $errors = '';
            return array('success' => false, 'message' => 'validation error', 'type' => 'error', 'errors' => $errors);
        }
            
    }


    /**
     * Send a welcome e-mail to user
     *
     * @package registration
     * @return array Success array
     * @author 
     **/
    protected function _sendWelcomeEmail() {
        $email = new CakeEmail('gmail');

        $email->viewVars(array(
            'name' => $this->tempData['Profile']['name']
        ));

        $res = $email->template('Registration.Emails/html/registration/welcome', 'default')
                ->emailFormat('html')
                ->from(array(Configure::read('NoReplyEmail') => Configure::read('ProjectTitle')))
                ->to($this->tempData['User']['email'])
                ->subject('[' . Configure::read('ProjectTitle') . '] ' . __('Welcome! See Instructions to Complete Your Registration'))
                ->send();
        return array('success' => true);
    }







	/*
		TODO put it cart
	*/
    private function __isRegisteredUser() {
		return false;
        // if ($this->Auth->loggedIn()) {
        // 
        //     $options['joins'] = array(
        //         array(
        //             'table' => 'cart_items',
        //             'alias' => 'CartItem',
        //             'type' => 'INNER',
        //             'conditions' => array(
        //                 'Cart.id = CartItem.cart_id'
        //             )
        //         ),
        //         array(
        //             'table' => 'cart_products',
        //             'alias' => 'CartProduct',
        //             'type' => 'INNER',
        //             'conditions' => array(
        //                 'CartItem.cart_product_id = CartProduct.id'
        //             )
        //         ),
        //         array(
        //             'table' => 'payment_statuses',
        //             'alias' => 'PaymentStatus',
        //             'type' => 'INNER',
        //             'conditions' => array(
        //                 'Cart.payment_status_id = PaymentStatus.id'
        //             )
        //         )
        //     );
        // 
        //     $options['conditions']['Cart.user_id'] = $this->Auth->user('id');
        //     $options['conditions']['PaymentStatus.payable'] = 0;
        //     $options['conditions']['CartProduct.plugin'] = 'Registration';
        //     $options['conditions']['CartProduct.model'] = 'Registration';
        // 
        //     $res = $this->Cart->find('count', $options);
        // 
        //     return $res != 0;
        // } else {
        //     return false;
        // }
    }



    /**
     * Used by Cart to retrieve some information
     * @param int $id
     * @return array
     * @access public
     */
    public function cart__getInfo($id=null) {
        $this->recursive = 0;
        $res = $this->findById($id);

        return array(
            'name' => Configure::read('ProjectTitle'),
            'description' => __('Enrollment in Event'),
            'price' => $res['Registration']['price']
        );
    }

}