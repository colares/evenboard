<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
	/**
     * Components are packages of logic that are shared between controllers.
     * If you find yourself wanting to copy and paste things between controllers,
	 * you might consider wrapping some functionality in a component.
     *
     * @var string
     */
    public $components = array(
        'Session',
        'RequestHandler',
        'Paginator',
        'Auth' => array(
            'authorize' => array(
                'Actions' => array('actionPath' => 'controllers')
            ),
            'authenticate' => array(
                'Form' => array(
                    'fields' => array('username' => 'email', 'password' => 'password')
                )
            )
        )
    );


    /**
     * Helpers are the component-like classes for the presentation layer of your application.
     * They contain presentational logic that is shared between many views, elements, or layouts.
     *
     * @var string
     */
    public $helpers = array(
        'Html',
        'Form',
        'Session',
        'Js',
        'BForm',
        // 'BPaginator',
        // 'Util',
		// 'IndexList',
        'Paginator',
		'Time'
    );

    /**
     * Set of handlers used in add action
     * @var array
     * @see add
     */
    public $addHandler = array('_basicAdd');

    /**
     * Set of handlers used in edit action
     * @var array
     * @see add
     */
    public $editHandler = array('_basicEdit');

    /**
     * Set of handlers 
     * @var array
     */
    public $prepareForm = array();

    /**
     * Default redirects
     * @var array
     */
	/*
		TODO Default Redirect
	*/
    private $__defaultRedirects = array(
        'add' => 'index',
        'delete' => 'index',
        'edit' => 'index'
    );
    public $redirects = array();


	/**
	 * I guess there is no need be set inside each controller.
	 * @var Entity name
	 */
	public $entity;

	/**
     * Executed before every action in the controller.
     * @return void
     */
    public function beforeFilter() {

        $this->Auth->allow();

        // Switch the layout according to the prefix 
        $this->__switchLayoutByPrefix();


        // Authorization setup, using the default
        $this->Auth->authError = __('You are not authorized to access that location.');
        // You are not allowed access to this page or perform this action.
        //$this->redirect('/p/servicos');
        // Custom fields setup
        //$this->Auth->fields = array('username' => 'email', 'password' => 'password');
        // Login / Logout stuffs setup
        $this->Auth->loginError = __('Username or password are incorrect.');
        $this->Auth->loginAction = array('controller' => 'users', 'action' => 'login', 'admin' => false, 'plugin' => false);
        $this->Auth->logoutRedirect = array('controller' => 'registrations', 'plugin' => 'registration', 'action' => 'store');
        $this->Auth->loginRedirect = array('controller' => false, 'action' => 'login', 'admin' => false, 'plugin' => false);

        $this->redirects = array_merge($this->__defaultRedirects, $this->redirects);

		if (!isset($this->entity)) {
			$this->entity = Inflector::singularize($this->name); 
		}
    }


    /**
     * Switch the layout according to the prefix 
     *
     * @return void
     * @author Thiago Colares
     */
    private function __switchLayoutByPrefix() {
        if (isset($this->params['prefix'])) {
            switch ($this->params['prefix']) {
                case 'admin':
                    $this->layout = 'admin';
                    break;
                default:
                    $this->layout = 'default';
                    break;
            }
        } else {
            $this->layout = 'default';
        }

    }


    /**
     * Executes a set o methods defined in $addHandler
     * @access public
     * @author Ernesto Barros Amorim
     */
    public function add() {
        if ($this->request->is('post') || $this->request->is('put')) {
            $res = $this->execute($this->addHandler);
            if ($res['success']) {
				/*
					TODO "Operation successfully" should become a better message
				*/
                $this->Session->setFlash(__('Operation successfully'), 'default', array('class' => 'alert alert-success fade in'));

                $this->redirect($this->redirects['add']);
            } else {
				/*
					TODO Append Error message $res['message']
				*/
				/*
					TODO Encapsulate setFlash
				*/
                $this->Session->setFlash(__('Error occurred while processing your data.') . ' ' . $res['message'], 'default', array('class' => 'alert alert-error fade in'));
			}
        } else {
            $res = $this->execute(array_merge($this->prepareForm, array('_jQueryValidationRules')));
            if (!$res['success']) {
                $this->Session->setFlash(__('Error occurred while preparing form.') . ' ' . $res['message'], 'default', array('class' => 'alert alert-error fade in'));
                $this->redirect($this->redirects['error']);
            }
        }
    }

    /**
     * Executes a set o methods defined in $addHandler, admin usage
     * @access public
     * @author Ernesto Barros Amorim
     */
    public function admin_add() {
        $this->add();
    }

    /**
     * Executes a set o methods defined in $editHandler
     * @access public
     * @author Ernesto Barros Amorim
     */
    public function edit($id=null) {
        if (!empty($this->request->data)) {
            $this->execute(array_merge($this->prepareForm, array('_jQueryValidationRules')));
            $res = $this->execute($this->editHandler);
            if ($res['success']) {
                $this->Session->setFlash(__('Operation successfully'), 'default', array('class' => 'alert alert-success fade in'));
                $this->redirect($this->redirects['edit']);
            } else {
                $this->Session->setFlash(__('Error occurred while processing your data'), 'default', array('class' => 'alert alert-error fade in'));
			}
        } else {
            $this->execute(array_merge($this->prepareForm, array('_jQueryValidationRules', '_checkId', '_initFormValues')), $id);
        }
    }

    /**
     * Executes a set o methods defined in $editHandler, admin usage
     * @access public
     * @author Thiago Colares
     */
    public function admin_edit($id = null) {
        $this->edit($id);
    }


    /**
     * Deletes an existent register
     * @param mixed $id
     * @acesss public
     */
    public function delete($id=null) {
        
        if (isset($this->request->data['rowId']) && $id == null) {
            $errors = 0;
            foreach ($this->request->data['rowId'] as $key => $item) {
                $res = $this->{$this->entity}->delete($key);
                if (!$res['success'])
                    $errors++;
            }
        } else
            $res = $this->{$this->entity}->delete($id);
        $this->redirect('index2');
    }

    /**
     * Simple add routine
     * @access protected
     */
    protected function _basicAdd() {
        return $this->{$this->entity}->add($this->request->data);
    }

    /**
     * Simple edit routine
     * @access protected
     */
    protected function _basicEdit() {
        return $this->{$this->entity}->edit($this->request->data);
    }

    /**
     * Execute a set of methods
	 * 
     * @param array $functions An array of methods to be executed
     * @author Ernesto Barros Amorim
     */
    public function execute($functions = array(), $data = null) {
        $result = array('success' => true);
        foreach ($functions as $item) {
            $result = $this->{$item}($data);
            if (!$result['success']) {
            	break;
			}
        }
        return $result;
    }

    /**
     * Checks if the ID provided for editing is valid
     * @param integer $id
     * @return array
     * @author Ernesto Barros Amorim
     */
    protected function _checkId($id = null) {
		if ($id == null) {
			return array('success' => false, 'message' => __('Object reference ID can not be null.'));
		} else {
			$options['conditions'] = array($this->entity.'.id' => $id);
			$result = (bool) $this->{$this->entity}->find('count', $options);
			if ($result) {
				return array('success' => true);
			} else {
				return array('success' => false, 'message' => __('Inexistent object reference ID.'));
			}
		}
    }

    /**
     * Retrieves validate rules in a jQuery Validator format
     * @access protected
     * @return array
     * @author Ernesto Barros Amorim
     */
    protected function _jQueryValidationRules() {
        $jQueryValidationRules = $this->{$this->entity}->getJsRules();
        $this->set(compact('jQueryValidationRules'));
        return array('success' => true);
    }

    /**
     * Method called in edit to fill the $this->request->data values
     * @access protected
     * @return array
     */
    protected function _initFormValues($id=null) {
        $this->request->data = $this->{$this->entity}->findById($id);
        return array('success' => true);
    }

    /**
     * Check if the user is already logged in
     * ainda em desuso!!!
     * @return void
     * @author 
     **/
    protected function _isAlreadyLoggedIn() {
        if ($this->Session->read('Auth.User')) {
            $role = $this->Role->read(null, $this->Auth->user('role_id'));
            CakeSession::write('Auth.User.role', $role['Role']['alias']);

            $url = null;
            switch ($role['Role']['alias']) {
                case 'admin':
                case 'manager':
                    $this->Session->setFlash(__('If you pretend to register an other person, user "Add Register (Consolidate)" at Administrative Panel.'), 'default', array('class' => 'notice'));
                    $url = array('controller' => 'pages', 'action' => 'dashboard', 'admin' => true, 'plugin' => false);
                    break;
                case 'registered':
                    $this->Session->setFlash(__('You has already registered!'), 'default', array('class' => 'notice'));
                    $url = array('controller' => 'pages', 'action' => 'dashboard', 'admin' => true, 'plugin' => false);
                    break;
                default:
                    $url = $this->Auth->redirect();
                    break;
            }
            $this->redirect($url);
        }
        return array('success' => true);
    }

    // --------------------------------------------------------------------------------
    // --------------------------------------------------------------------------------
    // THE WIZARD SECTION
    // --------------------------------------------------------------------------------
    // --------------------------------------------------------------------------------
    // todo BUILD a plugin!
    // --------------------------------------------------------------------------------
    // --------------------------------------------------------------------------------


    /**
     * undocumented function
     *
     * @return void
     * @author 
     **/
    public function addToTheWizard () {
        // todo put it in TheWizard plugin bootstrap
        if (!$this->Session->check('TheWizard')) {
            $this->Session->write('TheWizard', array());
        }

        $item = $this->request->data;
        // todo encrypt password
        // todo build an execute() action

        if ($this->Session->check('TheWizard')) {
            $this->Session->write('TheWizard', array_merge($this->Session->read('TheWizard'), $item));
        } else {
            $this->Session->write('TheWizard', $item);
        }

        return array('success' => true);
    }

    public function addTheWizard() {    
        if(!empty($this->request->data)) {

            $res = $this->execute($this->addHandler);
            if ($res['success']) {
                /*
                    TODO "Operation successfully" should become a better message
                */
                $this->Session->setFlash(__('Operation successfully'), 'default', array('class' => 'alert alert-success fade in'));

                $this->redirect($this->redirects['addTheWizard']);
            } else {
                /*
                    TODO Append Error message $res['message']
                */
                /*
                    TODO Encapsulate setFlash
                */
                $this->Session->setFlash(__('Error occurred while processing your data.') . ' ' . $res['message'], 'default', array('class' => 'alert alert-error fade in'));
            }
        } else {
            $res = $this->execute(array_merge($this->prepareForm, array('_jQueryValidationRules')));
            if (!$res['success']) {
                $this->Session->setFlash(__('Error occurred while preparing form.') . ' ' . $res['message'], 'default', array('class' => 'alert alert-error fade in'));
                $this->redirect($this->redirects['error']);
            }
        }
    }

    /**
     * Remove all TheWizard related data
     *
     * @return void
     * @author 
     **/
    public function emptyTheWizard () {
        if ($this->Session->check('TheWizard')) {
            $this->Session->delete('TheWizard');
        }
        return array('success' => true);
    }

    /**
     * Remove all TheWizard related data
     *
     * @return void
     * @author 
     **/
    public function setDataFromTheWizard () {
        if ($this->Session->check('TheWizard')) {
            $this->request->data['CartItem'] = $this->Session->read('TheWizard.CartItem');
        }
        return array('success' => true);
    }


            

}
