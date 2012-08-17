<?php echo $content_for_layout; ?>

<?php if(Configure::read('ProjectLogo')){ ?>
<p>
	<?php 
		$fullUrl = $this->Html->url("/img/" . Configure::read('ProjectLogo'), true);
		echo $this->Html->image($fullUrl, array('fullBase' => true, 'title' => 'Logo ' . Configure::read('ProjectTitle'), 'alt' => 'Logo ' . Configure::read('ProjectTitle')));
	?>
</p>
<?php } // if logo ?>

<p>
	<?php
		print Configure::read('ProjectFullTitle') . " | " . Configure::read('Description') . " | " . Configure::read('Period');
	?>
</p>


<p>Este é um e-mail automático disparado pelo sistema. Favor não respondê-lo, pois esta conta não é monitorada. <br><?php print $this->Html->link('Veja nossos Contatos.', Configure::read('ContactUsURL')) ?></p>

<small style="color: #BFBFBF">O <strong><?php print Configure::read('ProjectFullTitle') ?></strong> é gerenciado com <?php print $this->Html->link('Apimenti Eventos', 'http://www.apimenti.com.br') ?><small>