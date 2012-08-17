<?php

/**
 * A user profile is a collection of personal data associated to a specific user.
 *
 * @package default
 * @author Thiago Colares
 */
class Profile extends AccountAppModel {

    public $name = 'Profile';

    public function getProfileTitles() {
        return array(
            'Sr.' => 'Sr. (Senhor)',
            'Sra.' => 'Sra. (Senhora)',
            'Dr.' => 'Dr. (Doutor)',
            'Dra' => 'Dra. (Doutora)',
            'V. Ex.ª' => 'V. Ex.ª (Vossa Excelência)',
            'V. Em.ª' => 'V. Em.ª (Vossa Eminência)',
            'V. Mag.ª' => 'V. Mag.ª (Vossa Magnificência)'
        );
    }

    public $editHandlers = array('_addEntity', '_finalSave');


    public function validateName() {
        $res = explode(' ', $this->data['Profile']['name']);

        return count($res) > 1;
    }

    public $validate = array(
        'name' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'You have not entered your name.'
            ),
            'validateName' => array(
                'rule' => 'validateName',
                'message' => 'You have not entered your last name.'
            )
        ),
        'main_doc' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'You have not entered your CPF.'
            ),
            'isUnique' => array(
                'rule' => array('checkUnique', array('main_doc', 'document_type_id')),
                'message' => 'This document is already in use'
            ),
            'cpfIsNumeric' => array(
                'rule' => 'checkCpfIsNumeric',
                'message' => 'Please supply your CPF only with numbers.'
            ),
            'cpfLength' => array(
                'rule' => 'checkCpfLength',
                'message' => 'Inform the CPF with 11 digits.'
            ),
//            'passportLength' => array(
//                'rule' => 'checkPassportLength',
//                'message' => 'Inform the passport with 8 digits.'
//            ),
//            'minLength' => array(
//                'rule' => array('minLength', 11),
//                'message' => 'Inform the CPF with 11 digits.'
//            ),
//            'maxLength' => array(
//                'rule' => array('maxLength', 11),
//                'message' => 'Inform the CPF with 11 digits.'
//            )
        ),
        'document_type_id' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'You have not entered your document type.'
            )
        ),
        'zipcode' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'You have not entered your zipcode.'
            ),
            'numeric' => array(
                'rule' => 'numeric',
                'message' => 'Only number are allowed.'
            ),
            'minLength' => array(
                'rule' => array('minLength', 8),
                'message' => 'Zipcode must be at least 8 characters.'
            ),
            'maxLength' => array(
                'rule' => array('maxLength', 8),
                'message' => 'Zipcode must be no larger than 8 characters..'
            )
        ),
        'address' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'You have not entered your address.'
            ),
        ),
        'address_number' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'You have not entered your number.'
            ),
            'numeric' => array(
                'rule' => 'numeric',
                'message' => 'Only number are allowed.'
            ),
            'maxLength' => array(
                'rule' => array('maxLength', 5),
                'message' => 'Number must be no larger than 5 characters..'
            )
        ),
        'state' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'You have not entered your state.'
            ),
        ),
        'city' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'You have not entered your city.'
            ),
        ),
        'neighborhood' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'You have not entered your neighborhood.'
            ),
        ),
        'mobile' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'You have not entered your mobile.'
            ),
            'numeric' => array(
                'rule' => 'numeric',
                'message' => 'Only number are allowed on Phone Field.'
            ),
            'minLength' => array(
                'rule' => array('minLength', 8),
                'message' => 'Mobile must be at least 8 characters.'
            ),
            'maxLength' => array(
                'rule' => array('maxLength', 8),
                'message' => 'Mobile must be no larger than 8 characters.'
            )
        ),
        'mobile_ddd' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'You have not entered your ddd.'
            ),
            'numeric' => array(
                'rule' => 'numeric',
                'message' => 'Only number are allowed on DDD field.'
            ),
            'minLength' => array(
                'rule' => array('minLength', 2),
                'message' => 'DDD must be at least 2 characters.'
            ),
            'maxLength' => array(
                'rule' => array('maxLength', 2),
                'message' => 'DDD must be no larger than 2 characters.'
            )
        )
    );

    /**
     * Checks if values of a set of fields are unique in database
     * @param array $data Set of values that will be tested
     * @param array $fields Fields used in isUnique check
     * @return boolean
     * @access public
     * @author Ernesto Barros Amorim
     * @todo Colocar este método no AppModel
     */
    public function checkUnique($data, $fields) {
        return $this->isUnique($fields, false);
    }

    /**
     * Checks if the value entered for CPF is numeric
     * @return boolean
     * @access public
     * @author Ernesto Barros Amorim
     */
    public function checkCpfIsNumeric() {
        if ($this->data['Profile']['document_type_id'] == 1 && !is_numeric($this->data['Profile']['main_doc']))
            return false;
        else
            return true;
    }
    
    /**
     * Checks if the value entered for CPF has exactly 11 digits
     * @return boolean
     * @access public
     * @author Ernesto Barros Amorim
     */
    public function checkCpfLength() {
        $numberLength = strlen($this->data['Profile']['main_doc']);
        if ($this->data['Profile']['document_type_id'] == 1 && $numberLength!=11)
            return false;
        else
            return true;
    }
    
    /**
     * Checks if the value entered for passport has exactly 8 digits
     * @return boolean
     * @access public
     * @author Ernesto Barros Amorim
     */
    public function checkPassportLength() {
        $numberLength = strlen($this->data['Profile']['main_doc']);
        if ($this->data['Profile']['document_type_id'] == 2 && $numberLength!=8)
            return false;
        else
            return true;
    }

}