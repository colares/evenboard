<?php

App::uses('RegistrationAppController', 'Registration.Controller');

/**
 * Registrations Controller
 */
class RegistrationsController extends RegistrationAppController {

    /**
     * Controller name
     *
     * @var string
     * @access public
     */
    public $name = 'Registrations';

    /**
     * Models used by Controller
     * @access public
     */
    public $uses = array('Registration.Registration', 'Registration.RegistrationType');

//    public $prepareForm = array('_putRegistrationTypes');
	
    // todo check if user != admin, deve ter escolhido alguma opção
    public $prepareForm = array('_documentTypesCombo', '_checkRegistration');


    /**
     * Return all available registrations
     *
     * @return void
     * @author 
     **/
    public function getRegistrations() {
        return $this->Registration->getAvailableProducts();

    }

    /**
     * load registration previously chosen
     * by now, is not possible to two more then 1 registration
     *
     * @return void
     * @author 
     **/
    public function loadPrevWizard(){
        if($this->Session->check('TheWizard.CartItem.Registration.0')) {
            $this->request->data['CartProduct']['id'] = $this->Session->read('TheWizard.CartItem.0.CartProduct.id');
        }
        return array('success' => true);
    }

    /**
     * Append product to session cart
     *
     * @return void
     * @author 
     **/
    public function addToTheWizard(){
        // todo put it at cart bootstrap
        if (!$this->Session->check('CartItem')) {
            $this->Session->write('CartItem', array());
        }
        $cartItem = json_decode($this->request->data['CartItem'], TRUE);
        $this->Session->write('TheWizard.CartItem.0', $cartItem);
        return array('success' => true);
    }

    /**
     * self registration implementation of its store
     *
     * @package default
     * @author 
     **/
	public function store(){
        $this->prepareForm = array('loadPrevWizard');
        $this->addHandler = array('addToTheWizard');
        $this->redirects['addTheWizard'] = array('controller' => 'users', 'action' => 'add', 'prefix' => false, 'plugin' => false);;
        parent::addTheWizard();
    }


    /**
     * undocumented function
     *
     * @return void
     * @author 
     **/
    public function loadPrevRegistrations() {
        $CartItem = ClassRegistry::init('ShoppingCart.CartItem');
        $options = array();
        // $options['conditions']['CartProduct.model'] = 'Registration';
        $regs = $CartItem->getBoughtProducts(
            $this->Session->read('Auth.User.id'),
            $options,
            null
        );

        debug($regs);
        return array('success' => true);
    }

    /**
     * self registration implementation of its store
     *
     * @package default
     * @author 
     **/
    public function admin_store(){
        $this->prepareForm = array('loadPrevRegistrations');
        $this->addHandler = array('addToTheWizard');
        $this->redirects['addTheWizard'] = array('controller' => 'users', 'action' => 'add', 'prefix' => false, 'plugin' => false);;
        parent::addTheWizard();
    }


    /**
     * Set document types combo source to view
     *
     * @package registration
     * @author 
     **/
    protected function _documentTypesCombo() {
        $this->loadModel('DocumentType');
        $items = $this->DocumentType->find('list');

        if ($items) {
            $this->set('documentTypeOptions', $items);
            $success = true;
        } else
            $success = false;

        return array('success' => $success);
    }


    /**
     * Check if at least one registration has been choosed
     *
     * @package registration
     * @author 
     **/
    protected function _checkRegistration() {
        if (
            isset($_SESSION['CartItem']['Registration']) && !empty($_SESSION['CartItem']['Registration'])
            ||
            isset($this->request->data['Registration']) || !empty($this->request->data['Registration'])
        ) {
            return array('success' => true);
        } else {
            $this->redirects['error'] = '/registrations/store';
            return array('success' => false, 'message' => __('Registration option could not be found. Please, select one.'));

        }
    }


}