<div class="well">
	<h2><?php print __('Order Summary'); ?></h2>
	<table class="table table-bordered table-condensed">
		<tr><th><?php print __('Item'); ?></th><th><?php print __('Price'); ?></td></tr>
		<?php
			$trs = ''; 
			foreach ($this->Session->read('TheWizard.CartItem') as $item) {
				$product = $this->requestAction('/shopping_cart/cart_products/getInfo/', array('product' => $item));
				$trs .= sprintf('<tr><td> %s <p><em> %s </em></p></td><td> %s </td></tr>',
					$product['CartProduct']['name'],
					$product['CartProduct']['description'],
					$product['CartProduct']['price']
				);		
			}
			print $trs;
		?>
	</table>
</div>