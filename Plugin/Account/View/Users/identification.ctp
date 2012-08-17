<h2>Ainda não me cadastrei</h2>
<div class="registration form">
    <?php
    $options = array('plugin' => false, 'controller' => 'users', 'action' => 'add');
    echo $this->BForm->create('User', 'form-horizontal', $options);
    echo $this->element('Account.user/form_access');
    echo $this->Form->end(__('Continue'), null, array('div' => false, 'class' => 'btn btn-success btn-large'));
    ?>
</div>

<br style="clear:both">
<h2>Já me cadastrei</h2>