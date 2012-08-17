<div>
    <h2><?php print __('My Personal Data'); ?></h2>

	<?php
		echo $this->BForm->create('Profile', 'form-horizontal', array('type' => 'file'));
		echo $this->Form->hidden('Profile.id', array('value' => $this->request->data['Profile']['id']));
		echo $this->element('Account.profile/form_part_personal');
		echo $this->element('Account.profile/form_part_address_phone');
		// echo $this->element('profile/form_part_extras');

		echo $this->BForm->end('Save Changes', null, null, array(
				$this->Html->link(__('Cancel', true), array(
					'plugin' => false,
					'action' => 'dashboard',
					'profile' => true,
					'controller' => 'systems'
		        ), array(
		            'class' => 'btn',
		        ))
			)
		);
	?>

</div>