<fieldset>
    <legend><?php print __('Access Information'); ?></legend>
	<?php
		print $this->BForm->input('User.email', array('label' => __('Email'),
                'maxlength' => 50,
                'type' => 'email'
            )
        );

        print $this->Form->hidden('User.id');
        
        if(!isset($automaticPassword)) {
        	$automaticPassword = false;
        }
    
        if(!$automaticPassword) {
            print $this->BForm->input('User.password', array('label' => __('Password'), 'type' => 'password'), array('tip' => __('This is not you personal E-mail password.')));

            print $this->BForm->input('User.confirm_password', array('label' => __('Confirm Password'),'type' => 'password'));
        }
	?>
</fieldset>