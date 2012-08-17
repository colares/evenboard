<?php

/**
 * Application model for Cake.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
App::uses('Model', 'Model');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class AppModel extends Model {

    /**
     * Data Source reference
     * @var object
     */
    var $dataSource;

    /**
     * Recursive
     * @var integer
     */
    var $recursive = -1;

    /**
     * Data used in deep save
     * @var array
     */
    var $tempData = array();

    /**
     * Set of handlers used in add method
     * @var array
     * @see add
     */
    public $addHandlers = array('_addEntity', '_finalSave');

    /**
     * Set of handlers used in edit method
     * @var array
     * @see edit
     */
    public $editHandlers = array();

    /**
     * Set of handlers used in remove method
     * @var type 
     */
    public $deleteHandlers = array();
    
    public $searchFields = array();

    /**
     * Keeps date fields that will be converted from d/m/Y to datetime before saving
     * @var array
     */
    public $dateFields = array();

    /**
     * Begins a new transaction
     * @access protected
     */
    protected function _begin() {
        $this->dataSource = $this->getDataSource();
    }

    /**
     * Commits changes in database
     * @access protected
     */
    protected function _commit() {
        $this->dataSource->commit($this);
    }

    /**
     * Rollbacks changes in database
     * @access protected
     */
    protected function _rollback() {
        $this->dataSource->rollback($this);
    }

    /**
     * 
     * @param array $functions An array of methods to be executed
     * @author Ernesto Barros Amorim
     */
    public function execute($functions = array(), $data = null) {
        $result = array('success' => true);
        foreach ($functions as $item) {
            $result = $this->{$item}($data);
            if (!$result['success']){
				break;
			}
        }
        return $result;
    }

    /**
     * Executes a set of actions defined in $addHandler
     * @access public
     */
    public function add($data = null) {
        $this->_begin();
        $res = $this->execute($this->addHandlers, $data);
        if ($res['success']) {
            $this->_commit();
		}
        return $res;
    }

    protected function _addEntity($data = null) {
        $this->tempData[$this->name] = $data[$this->name];
        return array('success' => true);
    }

    protected function _addAllEntities($data = null) {
        $this->tempData = $data;
        return array('success' => true);
    }

    /**
     * Check if the user is already logged in
     *
     * @package default
     * @author 
     **/
    protected function _isAlreadyLoggedIn(){
        debug($_SESSION);
        die();
        if ($this->Session->read('Auth.User')) {
            $role = $this->Role->read(null, $this->Auth->user('role_id'));
            CakeSession::write('Auth.User.role', $role['Role']['alias']);

            $url = null;
            switch ($role['Role']['alias']) {
                case 'admin':
                case 'manager':
                    $this->Session->setFlash(__('If you pretend to register an other person, user "Add Register (Consolidate)" at Administrative Panel.'), 'default', array('class' => 'notice'));
                    $url = array('plugin' => false, 'controller' => 'systems', 'action' => 'dashboard', 'prefix' => 'admin', 'admin' => true);
                    break;
                case 'registered':
                    $this->Session->setFlash(__('You has already registered!'), 'default', array('class' => 'notice'));
                    $url = array('plugin' => false, 'controller' => 'systems', 'action' => 'dashboard', 'prefix' => 'profile', 'profile' => true);
                    break;
                default:
                    $url = $this->Auth->redirect();
                    break;
            }
            $this->redirect($url);
        }
        return array('success' => true);
    
    }

    /**
     * Executes a set of actions defined in $editHandler
     * @access protected
     */
    public function edit($data=null) {
        $this->_begin();

        $res = $this->execute($this->editHandlers, $data);
        if ($res['success']) {
			$this->_commit();
		}
        return $res;
    }

    /**
     * Retrieves validate rules in a jQuery Validator format
     * @access protected
     * @return array
     * @author Ernesto Barros Amorim
     */
    public function getJsRules() {
        if (!isset($this->validate))
            return array($this->name => array());

        $Rules = array();
        foreach ($this->validate as $field => $rule) {

            if (isset($rule['notEmpty']))
                $Rules[$this->name][$field]['required'] = true;

            if (isset($rule['maxLength']))
                $Rules[$this->name][$field]['maxlength'] = $rule['maxLength']['rule'][1];

            if (isset($rule['minLength']))
                $Rules[$this->name][$field]['minlength'] = $rule['minLength']['rule'][1];

            if (isset($rule['isNumeric']))
                $Rules[$this->name][$field]['number'] = true;
        }

        foreach ($this->hasOne as $key => $item) {
            $Rules = array_merge($Rules, $this->{$key}->getJsRules());
        }

        foreach ($this->hasMany as $key => $item) {
            $Rules = array_merge($Rules, $this->{$key}->getJsRules());
        }

        return $Rules;
    }

    /**
     * Searchs all items regarding some arguments. 
     * @access public
     * @return array
     */
    public function search() {

        $this->entity = $this->name;
        $this->conditions = $this->_getSearchConditions();
        $this->joins = $this->_getSearchJoins();
        $this->orders = $this->_getSearchOrder();
        $this->limit = $this->_getSearchLimit();
        $this->maxLimit = $this->_getSearchMaxLimit();
        $this->offset = $this->_getSearchOffset();
        $this->fields = $this->_getSearchFields();
        $this->group = $this->_getSearchGroup();

//        $this->setVar('entity', $this->name);
//        $this->setVar('conditions', $this->_getSearchConditions());
//        $this->setVar('joins', $this->_getSearchJoins());
//        $this->setVar('orders', $this->_getSearchOrder());
//        $this->setVar('limit', $this->_getSearchLimit());
//        $this->setVar('maxLimit', $this->_getSearchMaxLimit());
//        $this->setVar('offset', $this->_getSearchOffset());
//        $this->setVar('fields', $this->_getSearchFields());
//        $this->setVar('group', $this->_getSearchGroup());
//
//        $this->setVar('baseAdditionalElements', $this->additionalElements);
        $this->getFieldsForSearchFunction = '_extractSearchFields';

        return $this->baseSearch();
    }

    /**
     * Search Conditions
     * @see search
     * @return array
     * @access protected 
     */
    protected function _getSearchConditions() {
        return array();
    }

    /**
     * Search Joins
     * @see search
     * @return array
     * @access protected 
     */
    protected function _getSearchJoins() {
        return array();
    }

    /**
     * Search Order
     * @see search
     * @return array
     * @access protected 
     */
    protected function _getSearchOrder() {
        return $this->name . '.id ASC';
    }

    /**
     * Search Limit
     * @see search
     * @return array
     * @access protected 
     */
    protected function _getSearchLimit() {
        return Configure::read('PAGESIZE');
    }

    /**
     * Search Max Limit
     * @see search
     * @return array
     * @access protected 
     */
    protected function _getSearchMaxLimit() {
        return PHP_INT_MAX;
    }

    /**
     * Search Offset
     * @see search
     * @return array
     * @access protected 
     */
    protected function _getSearchOffset() {
        return 0;
    }

    /**
     * Search Fields
     * @see search
     * @return array
     * @access protected 
     */
    protected function _getSearchFields() {
        return isset($this->searchFields) ? $this->searchFields : $this->name . '.*';
    }

    /**
     * Search Group
     * @see search
     * @return array
     * @access protected 
     */
    protected function _getSearchGroup() {
        return null;
    }

    protected function _extractSearchFields($fields=array()) {
        if (!is_array($fields))
            return $fields;
        else {
            $result = array();
            foreach ($fields as $item) {
                $result[] = $item['field'];
            }

            return $result;
        }
    }

    /**
     * Find all items regarding some arguments. 
     * @access protected
     * @return array
     */
    public function baseSearch($data=null) {

        if (!isset($this->getFieldsForSearchFunction))
            $this->getFieldsForSearchFunction = '_extractSearchFields';

        $items = array();
        try {
            $options = array(
                'conditions' => $this->conditions,
                'joins' => $this->joins,
                'order' => $this->orders,
                'offset' => $this->offset,
                'limit' => $this->limit,
                'maxLimit' => $this->maxLimit,
                'fields' => $this->{$this->getFieldsForSearchFunction}($this->fields),
                'group' => $this->group
            );

            $items = $data == null ? $this->find('all', $options) : $data;

            $idItems = array();
            foreach ($items as $key => $item) {
                $idItems[$item[$this->name]['id']] = $key;

                foreach ($this->fields as $field) {
                    $parts = explode('.', $field['field']);
                    if (isset($field['renderer']))
                        $items[$key][$parts[0]][$parts[1]] = $this->{$field['renderer']}($item);
                }
            }

            if (count($idItems) > 0 && isset($this->additionalSearchElements)) {
                foreach ($this->additionalSearchElements as $item) {
                    $this->{$item}($idItems, $items);
                }
            }

            $success = true;
        } catch (Exception $e) {
            $message = $e->getMessage();
            $success = false;
        }

        return compact('items', 'total', 'success', 'message');
    }

    public function getSearchOptions() {

        $this->fields = $this->_getSearchFields();

        if (!isset($this->getFieldsForSearchFunction))
            $this->getFieldsForSearchFunction = '_extractSearchFields';

        $options = array(
            'conditions' => $this->_getSearchConditions(),
            'joins' => $this->_getSearchJoins(),
            'order' => $this->_getSearchOrder(),
            'offset' => $this->_getSearchOffset(),
            'limit' => $this->_getSearchLimit(),
            'maxLimit' => $this->_getSearchMaxLimit(),
            'fields' => $this->{$this->getFieldsForSearchFunction}($this->fields),
            'group' => $this->_getSearchGroup()
        );

        return $options;
    }

    /**
     * Removes registers and related data
     * @access public
     * @return array
     */
    public function delete($id=null) {

        $this->_begin();
        $res = $this->execute($this->deleteHandlers);

        if ($res['success']) {
            $res = parent::delete($id, true);

            if ($res === true) {
                $this->_commit();
                return array('success' => true);
            }
        }

        return array('success' => false);
    }

    /**
     * Saves the entity and its dependents
     * @param string $indexed If want to pass $data['ModelName'] instead of usual $data.
     *  When saving multiple records of same model the records arrays should be just numerically indexed without the model key. 
     * @return array
     */
    protected function _finalSave() {
        $res = $this->saveAssociated($this->tempData, array('deep' => true));

        if ($res === true)
            return array('success' => true);
        else{
            // @todo fetch all associed models errors
            $errors = '';
            return array('success' => false, 'message' => 'validation error', 'type' => 'error', 'errors' => $errors);
        }
            
    }

    /**
     * Checks if a date is valid for a given format
     * @todo make it works for all formats
     * @param string $format
     * @return boolean
     * @access protected
     */
    protected function _checkDateValidity($format='d/m/Y') {
        $parts = explode('/', $this->data['Profile']['birth_date']);
        if (count($parts) != 3)
            return false;

        return checkdate($parts[1], $parts[0], $parts[2]);
    }

    /**
     * Set of actions that run before saving
     * @return boolean
     * @access public
     */
    public function beforeSave() {
        foreach ($this->dateFields as $item) {
            $this->data[$this->name][$item] = $this->_date4Db($this->data[$this->name][$item]);
        }
        return parent::beforeSave();
    }

    /**
     * Set of actions that run after find
     * @return boolean
     * @access public
     */
    public function afterFind($arg) {
        foreach ($this->dateFields as $field) {
            foreach ($arg as $key => $item) {
                if (isset($item[$this->name][$field]))
                        $arg[$key][$this->name][$field] = $this->_date4View($arg[$key][$this->name][$field]);
            }
        }
        return parent::afterFind($arg);
    }

    /**
     * Converts date value from BRAZILIAN format to DATETIME
     * @param string $format
     * @return string
     * @access protected
     */
    protected function _date4Db($date) {
        $date = str_replace('/', '-', $date);
        if (!empty($date))
            return date('Y-m-d', strtotime($date));
        else
            return null;
    }

    /**
     * Converts date value from DATETIME format to a custom one
     * @param date $date
     * @param string $format
     * @return string
     * @access protected
     */
    protected function _date4View($date, $format = 'd/m/Y') {
        if (!empty($date))
            return date($format, strtotime($date));
        else
            return null;
    }

}
