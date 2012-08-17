<?php

App::uses('AccountAppController', 'Account.Controller');

/**
 * Users Controller
 */
class UsersController extends AccountAppController {

    /**
     * Controller name
     *
     * @var string
     * @access public
     */
    public $name = 'Users';

    /**
     * Models used by Controller
     * @access public
     */
    public $uses = array('Account.User','Account.Role');

    public $prepareForm = array();

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('add', 'logout');
    }

 	public function getRegistrations() {
		return $this->Registration->getRegistrations();
	}

    /**
     * load profile data previously filled
     *
     * @return array Success array
     * @author 
     **/
    public function loadPrevWizard(){
        if($this->Session->check('TheWizard.Profile')) {
            $this->request->data['Profile'] = $this->Session->read('TheWizard.Profile');
        }
        if($this->Session->check('TheWizard.User')) {
            $this->request->data['User'] = $this->Session->read('TheWizard.User');
        }
        return array('success' => true);
    }


    /**
     * Do not reload password and its confirmation
     *
     * @return void
     * @author 
     **/
    /*
     * @todo Encrypt the confirm_password before it goes to session
     */
    public function emptyPassword() {
        if($this->Session->check('TheWizard.User.password')) {
            $this->Session->delete('TheWizard.User.password');
        }
        if($this->Session->check('TheWizard.User.confirm_password')) {
            $this->Session->delete('TheWizard.User.confirm_password');
        }
        return array('success' => true);
    }


	public function add() {
        $this->prepareForm = array('emptyPassword', 'loadPrevWizard');
        $this->addHandler = array('addToTheWizard', 'setDataFromTheWizard', '_basicAdd', 'emptyTheWizard', 'loginAndRedirect');

        $this->redirects['add'] = array('controller' => 'users', 'action' => 'add', 'prefix' => false, 'plugin' => false);
        parent::add();
	}


    /**
     * Login and redirect user to the panel board page
     *
     * @return void
     * @author 
     **/
    public function loginAndRedirect() {
        $this->Session->setFlash(
            __("Welcome!"),
            'default',
            array('class' => 'alert alert-success')
        );

        $user = $this->User->findById($this->User->getInsertID());
        if ($user) {
            $this->Auth->login($user['User']);
            $this->redirect(array('controller' => 'pages', 'action' => 'dashboard', 'prefix' => 'admin', 'plugin' => false));
        }
        
    }


    /**
     * @todo Put it into the TheWizard plugin
     */
    public function saveTheWizard() {
        $this->request->data = $this->Session->read('TheWizard');
        
        $this->addHandler = array('_basicAdd');
        $this->redirects['addTheWizard'] = '/';
        parent::addTheWizard();
    }

    /**
     * Perform logout from system
     *
     * @return void
     * @author Thiago Colares
     */
    public function logout() {
        $this->Session->setFlash(__('You have successfully logged out.'), 'default', array('class' => 'alert alert-success'));
        $this->redirect($this->Auth->logout());
    }

    /**
     * Perform login on system
     *
     * @return void
     * @author Thiago Colares
     */
    public function login() {
        // Check if is already logged
        if ($this->Session->read('Auth.User')) {
            $this->Session->setFlash(__('You has already logged in.'), 'default', array('class' => 'alert alert-notice'));
            $role = $this->Role->read(null, $this->Auth->user('role_id'));
            CakeSession::write('Auth.User.role', $role['Role']['alias']);

            $url = null;
            switch ($role['Role']['alias']) {
                case 'admin':
                case 'manager':
                    $url = array('controller' => 'pages', 'action' => 'dashboard', 'admin' => true, 'plugin' => false);
                    break;
                case 'registered':
                case 'appraiser':
                case 'main_appraiser':
                    $url = array('controller' => 'pages', 'action' => 'dashboard', 'admin' => true, 'plugin' => false);
                    break;
                default:
                    $url = $this->Auth->redirect();
                    break;
            }
            $this->redirect($url);
        }

        if ($this->request->is('post')) {
            if ($this->Auth->login()) {
                /*
                  TODO Is this the best place / way to set role name?
                 */
                // Use this to check something in admin_index. I guess this is better then check with id!
                $role = $this->Role->read(null, $this->Auth->user('role_id'));
                CakeSession::write('Auth.User.role', $role['Role']['alias']);

                $url = null;
                switch ($role['Role']['alias']) {
                    case 'admin':
                    case 'manager':
                        $url = array('controller' => 'systems', 'action' => 'dashboard', 'prefix' => 'admin', 'admin' => true);
                        break;
                    case 'registered':
                    case 'appraiser':
                    case 'main_appraiser':
                        $url = array('controller' => 'systems', 'action' => 'dashboard', 'prefix' => 'profile', 'profile' => true);
                        break;
                    default:
                        $url = $this->Auth->redirect();
                        break;
                }

                $this->redirect($url);

                //return $this->redirect($this->Auth->redirect());
            } else {
                $this->Session->setFlash($this->Auth->loginError, 'default', array('class' => 'alert alert-error'), 'auth');
            }
        }
    }


    /**
     * Load User id from session
     *
     * @return void
     * @author 
     **/
    protected function _loadUserId() {
        $this->set('userId', $this->Session->read('Auth.User.id'));
        return array('success' => true);
    }


    /**
     * By typing the current password. User can set a new password.
     *
     * @return void
     * @author Thiago Colares
     */
    /*
      TODO Build a handler set! :) / Strategy
     */
    public function admin_resetPassword() {
        $this->prepareForm = array('_loadUserId');

        $this->User->addHandlers = array(
            '_addEntity',
            '_finalSave'
        );
        $this->redirects['add'] = array('controller' => 'pages', 'action' => 'dashboard', 'prefix' => 'admin', 'admin' => true, 'plugin' => false);
        parent::add();

        // if ($this->request->is('post')) {
        //     $user = $this->User->findById($this->Auth->User('id'));
        //     if (!empty($user)) {
        //         if ($this->request->data['User']['password'] == $this->request->data['User']['confirm_password']) {
        //             if (!empty($user['User']['password'])) {
        //                 //if ($user['User']['password'] == AuthComponent::password($this->request->data['User']['current_password'])) {
        //                     if (strlen(trim($this->request->data['User']['password'])) >= 6) {
        //                         $this->request->data['User']['id'] = $this->Auth->User('id');
        //                         if ($this->User->save($this->request->data)) {
        //                             $this->Session->setFlash(__('Password has been reset.', true), 'default', array('class' => 'alert alert-success'));
        //                             $this->redirect(array(
        //                                 'plugin' => false,
        //                                 'action' => 'dashboard',
        //                                 'controller' => 'systems'
        //                             ));
        //                         } else {
        //                             $this->Session->setFlash(__('Password could not be reset. Please, try again.'), 'default', array('class' => 'alert alert-error'));
        //                         }
        //                     } else {
        //                         $this->Session->setFlash(__('Password must have at least 6 characters. Please, try again.'), 'default', array('class' => 'alert alert-error'));
        //                     }
        //                 // } else {
        //                 //     $this->Session->setFlash(__('Current password did not match. Please, try again.'), 'default', array('class' => 'alert alert-error'));
        //                 // }
        //             } else {
        //                 $this->Session->setFlash(__('Password could not be empty! Please, try again.'), 'default', array('class' => 'alert alert-error'));
        //             }
        //         } else {
        //             $this->Session->setFlash(__('New password and its confirmation did not match. Please, try again.'), 'default', array('class' => 'alert alert-error'));
        //         }
        //     } else {
        //         $this->Session->setFlash(__('User could no be found.'), 'default', array('class' => 'alert alert-error'));
        //     }
        // }
    }


}