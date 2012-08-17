<h2>Dados Pessoais</h2>
<?php debug($this->Session->read()); ?>
<div class="registration form">
    <?php
    echo $this->BForm->create('Profile');
    echo $this->element('Account.profile/form_personal');
	echo $this->element('Account.profile/form_address_phone');
    echo $this->Form->end(__('Continue'), null, array('div' => false, 'class' => 'btn btn-success btn-large'));
    ?>
</div>

<br style="clear:both">