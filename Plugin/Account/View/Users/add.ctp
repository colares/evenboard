<?php //debug($this->Session->read()); ?>
<?php
	echo $this->element('ShoppingCart.cart/order_summary');
    echo $this->BForm->create('User');
    echo $this->element('Account.user/form_access');
    echo $this->element('Account.profile/form_part_personal');
    echo $this->element('Account.profile/form_part_address_phone');
	print $this->BForm->end();
?>