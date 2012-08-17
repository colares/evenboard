<div class="alert alert-info">		
    <h3><?php print __('Standards and Rules') . ':'  ?></h3>
	<?php
		$rules = Configure::read('PaperSubmissionRules');
		print '<ul>';
		foreach($rules as $rule){
			//print_r($rule);
			print '<li>' . $this->Html->link('<i class="icon-download-alt"></i> ' . $rule['file']['title'], $rule['file']['url'], array('escape' => false));
			print ((isset($rule['updated'])) ? ' <span class="label label-warning">' . __('Atualizado em') . ' ' . $rule['updated'] . '</span>' : '') . '</li>';
		}
		print '</ul>';
	?>
</div>
