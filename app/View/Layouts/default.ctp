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
		echo $this->Less->import('meta');

		echo $this->Html->script('css3-mediaqueries');
		echo $this->Html->script('jquery-1.8.2.min');
		echo $this->Coffee->import('application');
	?>
</head>
<body>
	<div class="container">
		<?php echo $this->Session->flash(); ?>
		<?php echo $this->fetch('content'); ?>
	</div>

	<div id="header">
		<div class="container">
			<div class="row">
				<div class="twelvecol">
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
