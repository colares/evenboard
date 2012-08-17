<?php $registrations = $this->requestAction('/registration/registrations/getRegistrations'); ?>
<?php print debug($this->Session->read('TheWizard.CartItem.0')); ?>
<?php print debug($this->request->data); ?>
<table class="table table-bordered" id="registration-select-table">
	<thead>
		<tr id='head'>
			<th scope="Row" width="10"></th>
			<th>Tipo de Inscrição</th>
			<th width="60"><?php print __('Price'); ?> *</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($registrations as $key => $item) { ?>
			<tr>
				<!-- <td>
					<input <?php print $item['full']||$item['registered']==true?'disabled="true"':''?> type="radio" name="data[Registration][id]" value="<?php print $item['CartProduct']['id'] ?>" <?php if(isset($this->request->data['Registration']['id']) && $this->request->data['Registration']['id'] == $item['id']){print 'checked';} ?>/>
				</td> -->
				<!-- <td><?php print $item['type'] ?> <?php print $item['full']==true?'<span class="label label-important">Esgotado</span>':''; ?> <?php print $item['registered']==true?'<span class="label label-important">Você já está registrado no evento.</span>':''; ?></td>
				<td><?php print $item['price'] ?></td> -->
				<td>
					<?php
						$checked = '';
						if(
							$this->Session->check('TheWizard.CartItem.0.CartProduct.id')
							&&
							$this->request->data['CartProduct']['id'] == $item['CartProduct']['id']
						) {
							print 'checked';
						}
					?>
					<input type="radio" name="data[CartItem]" value='<?php print json_encode($item); ?>' />
				</td>
				<td><?php print $item['RegistrationType']['name'] ?></td>
				<td><?php print $item['Registration']['price']; ?></td>
			</tr>	
		<?php } ?>
	</tbody>
</table>