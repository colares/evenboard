<?php print __('Main Store Title'); ?>
<?php
	$registrations = $this->requestAction('/registration/registrations/getRegistrations'); 
	
	/*
		TODO Main product
	*/
	print $this->BForm->create('Registration');

	/*
		TODO On plugin instalation, bind it to ShoppinCart
	*/
	echo $this->element("Registration.registration/shelf");

	print $this->BForm->end();
?>