<div class="navbar navbar-fixed-top">
	<div class="navbar-inner">
		<div class="container-fluid">
			<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
		        <span class="icon-bar"></span>
		        <span class="icon-bar"></span>
		        <span class="icon-bar"></span>
			</a>
			<!-- <a class="brand" href="/cba/admin"><?php  print Configure::read('ProjectAcronym'); ?></a> -->
			<div class="nav-collapse">
				<ul class="nav">
					<li class="dropdown <?php print (($this->params->plugin == 'paper') ? 'active' : ''); ?>">
						<?php echo $this->Html->link(
							'<i class="icon-home icon-white"></i>' . ' <b class="caret"></b>',
							'#',
							array('class' => 'dropdown-toggle', 'data-toggle' => "dropdown", 'escape' => false)
						);  ?>
						<ul class="dropdown-menu">
							<li>
								<?php echo $this->Html->link(
									__('Event Site'),
									array('plugin' => false, 'admin' => true, 'controller' => 'papers', 'action' => 'admin_index'),
									array('escape' => false)
								); ?>
							</li>
						</ul>
					</li>

					<li class="divider-vertical"></li>

					<li class="<?php print (($this->params->plugin == 'paper') ? 'active' : ''); ?>">
						<?php echo $this->Html->link(
							'<i class="icon-cog icon-white"></i> ' . __('Dashboard'),
							array('plugin' => false, 'admin' => true, 'controller' => 'pages', 'action' => 'dashboard'),
							array('escape' => false)
						); ?>
					</li>

					<li class="dropdown <?php print (($this->params->action == 'profile_edit' || $this->params->action == 'profile_resetPassword') ? 'active' : ''); ?>">
						<?php echo $this->Html->link(
							'<i class="icon-calendar icon-white"></i> ' . __('Event') . ' <b class="caret"></b>',
							'#',
							array('class' => 'dropdown-toggle ', 'data-toggle' => "dropdown", 'escape' => false)
						);  ?>
						<ul class="dropdown-menu">
							<li>
								<?php echo $this->Html->link(
									'<i class="icon-user"></i> ' . __('Register'), 
									array('plugin' => null, 'controller' => 'profiles', 'action' => 'edit', 'admin' => true),
									array('escape' => false)
								); ?>
							</li>
							
							<li>
								<?php echo $this->Html->link(
									'<i class="icon-asterisk"></i> ' . __('Sign up for activities'), 
									array('plugin' => null, 'controller' => 'users', 'action' => 'resetPassword', 'admin' => true),
									array('escape' => false)
									); 
								?>
							</li>
						</ul>
					</li>

					<!-- Papers -->
					<li class="dropdown <?php print (($this->params->action == 'profile_edit' || $this->params->action == 'profile_resetPassword') ? 'active' : ''); ?>">
						<?php echo $this->Html->link(
							'<i class="icon-file icon-white"></i> ' . __('Papers') . ' <b class="caret"></b>',
							'#',
							array('class' => 'dropdown-toggle ', 'data-toggle' => "dropdown", 'escape' => false)
						);  ?>
						<ul class="dropdown-menu">
							<li>
								<?php echo $this->Html->link(
									'<i class="icon-user"></i> ' . __('Register'), 
									array('plugin' => null, 'controller' => 'profiles', 'action' => 'edit', 'admin' => true),
									array('escape' => false)
								); ?>
							</li>
							
							<li>
								<?php echo $this->Html->link(
									'<i class="icon-asterisk"></i> ' . __('Sign up for activities'), 
									array('plugin' => null, 'controller' => 'users', 'action' => 'resetPassword', 'admin' => true),
									array('escape' => false)
									); 
								?>
							</li>
						</ul>
					</li>

					<!-- Acquired Services -->
					<li class="<?php print (($this->params->plugin == 'paper') ? 'active' : ''); ?>">
						<?php echo $this->Html->link(
							'<i class="icon-shopping-cart icon-white"></i> ' . __('Acquired Services'),
							array('plugin' => false, 'admin' => true, 'controller' => 'papers', 'action' => 'admin_index'),
							array('escape' => false)
						); ?>
					</li>
 

					<li class="dropdown <?php print (($this->params->action == 'profile_edit' || $this->params->action == 'profile_resetPassword') ? 'active' : ''); ?>">
						<?php echo $this->Html->link(
							__('Profile') . ' <b class="caret"></b>',
							'#',
							array('class' => 'dropdown-toggle ', 'data-toggle' => "dropdown", 'escape' => false)
						);  ?>
						<ul class="dropdown-menu">
							<li>
								<?php echo $this->Html->link(
									'<i class="icon-user"></i> ' . __('Edit My Personal Data'), 
									array('plugin' => null, 'controller' => 'profiles', 'action' => 'edit', 'admin' => true),
									array('escape' => false)
								); ?>
							</li>
							
							<li>
								<?php echo $this->Html->link(
									'<i class="icon-asterisk"></i> ' . __('Reset Password'), 
									array('plugin' => null, 'controller' => 'users', 'action' => 'resetPassword', 'admin' => true),
									array('escape' => false)
									); 
								?>
							</li>
						</ul>
					</li>
					
				</ul>
					
				<p class="navbar-text pull-right">
					<strong><?php print $this->Session->read('Auth.User.email'); ?></strong> <span>|</span>
					<?php echo $this->Html->link(
							'<i class="icon-off icon-white"></i> ' . __("Log out"),
							array('plugin' => null, 'admin' => false, 'prefix' => false, 'controller' => false, 'action' => 'logout'),
							array('escape' => false)
					); ?>
				</p>

			</div><!--/.nav-collapse -->
		</div>
	</div>
</div>