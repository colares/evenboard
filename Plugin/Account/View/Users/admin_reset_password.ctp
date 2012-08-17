<div class="users form">
    <h2><?php print __('Reset password'); ?></h2>
	<?php	
		print $this->BForm->create('User');
		print $this->Form->hidden('User.id', array('value' => $userId));
		print $this->BForm->input(
			'User.password',
			array('label' => __('New Password')),
			array('tip' => __('At least 6 characters! Do not use blank spaces.'))
		);
		
		print $this->BForm->input(
			'User.confirm_password',
			array('label' => __('Confirm'), 'type' => 'password'),
			array('tip' => __('Please, repeat your new password to make sure I\'ve typed it right.'))
		);

		print $this->BForm->end('Save Password', null, null, array(
			$this->Html->link(__('Cancel', true), array(
				'plugin' => false,
				'action' => 'dashboard',
				'controller' => 'systems'
	        ), array(
	            'class' => 'btn',
	        ))
		));
    ?>
</div>