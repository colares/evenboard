<div class="alert alert-info">		
    <h3><?php print __('How It Works') . ':'; ?></h3>
<!-- <p><strong>Para ser avaliado</strong>, seu trabalho deve obrigatoriamente seguir o modelo a seguir:</p> -->
    <ul>
		<?php
			$limitDate = date('d/m/Y', Configure::read('PaperSubmissionLimitDate') ) . ', ' . date('H:i', Configure::read('PaperSubmissionLimitDate') );
		?>
		<li>Um trabalho cadastrado poderá ser modificado até <?php print $limitDate; ?>;
		<li>Para modificar um trabalho, basta clicar em <strong>Editar</strong>, ao lado do trabalho desejado na lista abaixo;</li>
		<li>Ao fim da avaliação, você receberá uma notificação por e-mail.</li>
    </ul>
</div>