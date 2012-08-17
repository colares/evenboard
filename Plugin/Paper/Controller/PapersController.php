<?php

App::uses('CakeEmail', 'Network/Email');
App::uses('PaperAppController', 'Paper.Controller');

/**
 * Paper  Controller
 *
 * PHP version 5
 */
class PapersController extends PaperAppController {

    /**
     * Controller name
     *
     * @var string
     * @access public
     */
    public $name = 'Papers';

    /**
     * Models used by the Controller
     *
     * @var array
     * @access public
     */
    public $uses = array('Paper.Paper', 'Paper.ResearchLine');

    // todo check if user != admin, deve ter escolhido alguma opção
    public $prepareForm = array('_getPaperTypes', '_getResearchLines', '_getProfile');


    /**
     * Get all avalilavle paper types
     *
     * @return void
     * @author Thiago Colares
     */
    protected function _getPaperTypes() {

        $options = array();

        if (!empty($this->allowedPaperTypes)) {
            $options['conditions']['PaperType.id'] = $this->allowedPaperTypes;
        }

        $this->set('paperTypes', $this->Paper->PaperType->find('list', $options));
        return array('success' => true);
    }

    /**
     * Get all avalilable research lines
     *
     * @return void
     * @author Thiago Colares
     */
    protected function _getResearchLines($return = false) {
        $researchLines = $this->Paper->PaperResearchLine->ResearchLine->find('list');
        if ($return) {
            return $researchLines;
        } else {
            $this->set(compact('researchLines'));
            return array('success' => true);
        }
    }

    /**
     * Get profile data
     *
     * @return array
     * @author Thiago Colares
     */
    protected function _getProfile() {
        $Profile = ClassRegistry::init('Account.Profile');
        $this->set('profile', $Profile->findById($this->Session->read('Auth.User.id')));
        return array('success' => true);
    }
}