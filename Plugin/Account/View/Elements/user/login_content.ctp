<div class="users form">
	<h2><?php print __('Área do Participante'); ?></h2>
	<p class="muted">Já se inscreveu? Então acesse a <strong>Área do Participante</strong> para realizar pagamento, se inscrever em atividades etc.</p>
	<?php
	echo $this->Form->create('User', array('url' => '/login', 'inputDefaults' => array('label' => false, 'div' => false), 'class' => 'form-stacked')); 

		echo $this->BForm->input('User.email', array('label' => __('E-mail'), 'class' => 'span3'));
		echo $this->BForm->input('User.password', array('label' => __('Password'), 'class' => 'span3'));

		echo $this->BForm->end(null,null,
			array($this->Form->submit(__('Entrar') , array('class' => 'btn btn-primary btn-large', 'div' => false))),
			array(
				$this->Html->link(__('Forgot Password?'), array(
					'plugin' => false,
					'prefix' => false,
					'controller' => 'users',
					'action' => 'requestResetPassword',
				), array(
					'class' => 'btn',
				))
			)
		);

		echo $this->Form->end();
	?>
	
</div>