Olá <strong><?php print $name ?></strong>,<br>

<p>Bem vindo ou bem vinda ao <?php print Configure::read('ProjectTitle'); ?>! Seu cadastro no nosso <em>website</em> foi realizado com sucesso.</p>

<div style="background-color:#e0e0e0; font-size:150%; padding:30px; text-align:center;">
<p>Siga as instruções contidas no link a seguir para<br>realizar o pagamento e garantir sua participação</p>

<?php 
$fullUrl = $this->Html->url('/profile/payments/', true);
print $this->Html->link($fullUrl, $fullUrl); ?>
</div>
<br>

<p>Veja <?php echo $this->Html->link(__('Prices and Deadlines'),$this->Html->url('/pages/precos/', true), array('target' => '_blank')); ?>

<br>
<p>Atenciosamente,<br>
Equipe <?php print Configure::read('ProjectTitle'); ?>.</p>