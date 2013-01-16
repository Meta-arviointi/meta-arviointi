<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title>Meta-arviointi</title>
	<?php
		echo $this->Html->meta('icon');
		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');

		echo $this->Html->css('/fonts/stylesheet');
		echo $this->Html->css('1140');
		echo $this->Html->css('smoothness/jquery-ui-1.9.2.custom.min');
		echo $this->Less->import('meta');

		echo $this->Html->script('css3-mediaqueries');
		echo $this->Html->script('jquery-1.9.0.min');
		echo $this->Html->script('jquery-ui-1.9.2.custom.min');
		echo $this->Coffee->import('application');
	?>
</head>
<body>
	<div class="container">
		<div class="row">
			<div class="twelvecol">
				<?php echo $this->Session->flash(); ?>
			</div>
		</div>
		<?php echo $this->fetch('content'); ?>
	</div>

	<div id="header">
		<div class="container">
			<div class="row">
				<div class="twelvecol">
					<?php
						if($this->Session->read('Auth.User')) {
							echo '<div id="login-details">';
							echo '<span class="logged-user">' . $this->Session->read('Auth.User.name') . '</span>';
							echo $this->Html->link(
								__('Kirjaudu ulos'),
								array('controller' => 'users', 'action' => 'logout', 'course_id' => false),
								array('id' => 'logout-link')
							);
							echo '</div>';
						} 
					?>
					<h1>Meta-arviointi</h1>
				</div>
			</div>
		</div>
	</div>

	<?php
		//echo $this->element('sql_dump');
	?>
</body>
</html>
