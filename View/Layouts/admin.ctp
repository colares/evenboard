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
	      body {
	        padding-top: 60px;
	        padding-bottom: 40px;
	      }
	    </style>

    	<title><?php echo $title_for_layout; ?></title>

	    <?php
	    	$this->Html->css('bootstrap', null, array('inline' => false));
			$this->Html->css('bootstrap-responsive', null, array('inline' => false));
			
			echo $this->fetch('meta');
			echo $this->fetch('css');
			echo $this->fetch('script');
	    ?>
	</head>

	<body>

	    <div id="wrapper">
	        <div id="nav-container">
	            <div class="span12">
	                <?php echo $this->element("admin/navigation"); ?>
	            </div>
	        </div>

	        <div id="main" class="container">
				<div class="row">
					<div class="span12">
						<?php 
							echo $this->Session->flash();
							$flashAuth = strip_tags($this->Session->flash('auth'));
							if (!empty($flashAuth)){
								echo $this->Html->div('alert alert-error', $flashAuth,array('id'=>'flashMessage'));
							}
							echo $this->fetch('content');
						?>
					</div>
				</div>
				<div class="clear">&nbsp;</div>
			</div>
        
	        <div class="push"></div>
	    </div>
	    <?php //echo $this->element('footer'); ?>
		<?php echo $this->element('sql_dump'); ?>

		<?php
	        echo $this->Html->script(array(
			    'jquery',            
				'bootstrap',
				
				// TinyMCE
				'tiny_mce/tiny_mce.js',
				'tiny_mce_buttons.js',
	        ));		
		?>
    </body>
</html>