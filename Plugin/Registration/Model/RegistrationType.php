<?php

class RegistrationType extends RegistrationAppModel {
    public $name = 'RegistrationType';
	public $order = 'RegistrationType.weight ASC';
    
    public $hasMany = array(
        'Registration' => array(
            'className' => 'Registration.Registration',
            'dependent' => true
        )
    );


	//  	public function putRegistrationTypes() {
	// 	$options['joins'] = array(
	// 	    array(
	// 	        'table' => 'registration_types',
	// 	        'alias' => 'RegistrationType',
	// 	        'type' => 'INNER',
	// 	        'conditions' => array(
	// 	            'Registration.registration_type_id = RegistrationType.id'
	// 	        )
	// 	    ),
	// 	    array(
	// 	        'table' => 'registration_periods',
	// 	        'alias' => 'RegistrationPeriod',
	// 	        'type' => 'INNER',
	// 	        'conditions' => array(
	// 	            'Registration.registration_period_id = RegistrationPeriod.id'
	// 	        )
	// 	    )
	// 	);
	// 
	// 	$options['fields'] = array('Registration.id', 'Registration.price', 'RegistrationType.name', 'RegistrationPeriod.end_date', 'RegistrationPeriod.begin_date');
	// 	$options['order'] = array('RegistrationPeriod.begin_date DESC');
	// 
	// 	return $this->Registration->find('all', $options);
	// }


}