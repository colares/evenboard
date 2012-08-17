<?php

App::uses('AccountAppController', 'Account.Controller');

/**
 * Profiles Controller
 */
class ProfilesController extends AccountAppController {

    /**
     * Controller name
     *
     * @var string
     * @access public
     */
    public $name = 'Profiles';

    /**
     * Models used by Controller
     * @access public
     */
    public $uses = array('Account.Profile', 'Account.DocumentType');

    // public $editHandler = array('setUserId');

    /**
     * undocumented function
     *
     * @return void
     * @author 
     **/
    public function add() {
        $this->addHandler = array('addToTheWizard');
        $this->redirects['add'] = array('controller' => 'users', 'action' => 'saveTheWizard', 'prefix' => false, 'plugin' => false);
        parent::add();
    }

    /**
     * Get all document types for select combo box
     *
     * @return void
     * @author 
     **/
    public function getDocumentTypes() {
        return $this->DocumentType->find('list');
    }

    /**
     * Set User Id to admin_edit method
     *
     * @return void
     * @author 
     **/
    public function admin_edit() {
        $this->redirects['edit'] = array('controller' => 'pages', 'action' => 'dashboard', 'prefix' => 'admin', 'admin' => true, 'plugin' => false);
        parent::admin_edit($this->Session->read('Auth.User.id'));
    }
}