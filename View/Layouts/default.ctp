<!DOCTYPE html>
<html lang="en">
	<head>
	    <meta charset="utf-8">
	    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	    <meta name="description" content="">
	    <meta name="author" content="">

	    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
	    <!--[if lt IE 9]>
	      <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
	    <![endif]-->

		<style type="text/css">

	    </style>

    	<title><?php echo $title_for_layout; ?></title>

	    <?php
	    	$this->Html->css('bootstrap', null, array('inline' => false));
			$this->Html->css('bootstrap-responsive', null, array('inline' => false));
			$this->Html->css('jquery.countdown', null, array('inline' => false));
			$this->Html->css('bootstrap.min', null, array('inline' => false));

			echo $this->fetch('meta');
			echo $this->fetch('css');
			echo $this->fetch('script');

	        echo $this->Html->script(array(
			    'jquery',            
				'bootstrap',
				'jquery.countdown',
				'jquery.countdown-pt-BR',
				'bootstrap-transition'
	        ));		
		
	    ?>
	</head>

	<body style="background: url('/img/retina_wood.png') repeat-x top;">
		<div class="container" id="main">
			<div class="row">
	<div class="span12">
		<p style="padding-top:10px"><?php print $this->Html->image('/img/title.png'); ?>
			<?php 
				print $this->Html->link('Dashboard', '/pages/home', array('style' => 'color: #FFFFFF; font-size:120%;'));
				print '&nbsp;&nbsp&nbsp;&nbsp&nbsp;&nbsp';
				print $this->Html->link('Pitch Countdown', '/pages/pitch', array('style' => 'color: #FFFFFF; font-size:120%;')); ?>
		</p>

	</div>
</div>
<br>
			<?php 
				echo $this->Session->flash();
				$flashAuth = strip_tags($this->Session->flash('auth'));
				if (!empty($flashAuth)){
					echo $this->Html->div('alert alert-error', $flashAuth,array('id'=>'flashMessage'));
				}
				echo $this->fetch('content');
			?>
		</div>
		<?php echo $this->element('footer'); ?>
    </body>
</html>