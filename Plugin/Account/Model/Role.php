<?php
/**
 * A role specifies, by ARO relationship, the permissions for each user
 *
 * @package default
 * @author Thiago Colares
 */
class Role extends AccountAppModel {
	public $name = 'Role';
    public $actsAs = array('Acl' => array('type' => 'requester'));
    
    function parentNode() {
        return null;
    }
}

