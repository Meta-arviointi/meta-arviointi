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
	?>
	<script>
		window.baseUrl = '<?php echo $this->Html->url("/"); ?>';
	</script>
	<?php
		echo $this->Html->script('css3-mediaqueries');
		echo $this->Html->script('jquery-1.9.0.min');
		echo $this->Html->script('jquery-ui-1.9.2.custom.min');
		echo $this->Html->script('jquery.scrollTo.min');
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
								array('id' => 'logout-link', 'class' => 'header-button')
							);
							echo '</div>';

							echo '<div id="mail-indicator">';
							echo '<a href="#" class="header-button">';
							echo count($email_notifications);
							echo '</a>';
							echo '<div id="new-email-notifications">';
							if(!empty($email_notifications)) {
								foreach($email_notifications as $msg) {
									$href = $this->Html->url(array(
										'controller' 	=> 'CourseMemberships',
										'action' 		=> 'view',
										$msg['course_membership_id']
									));
									echo '<a href="'.$href.'" class="email-notification">';
									echo '<span class="from">' . $msg['sender'] . '</span>';
									echo '<span class="subject">' . $msg['subject'] . '</span>';
									echo '<span class="timestamp">' . $msg['sent_time'] . '</span>';
									echo '</a>';
								}
							}
							echo '</div>';
							echo '</div>';
						} 
					?>
					<h1>Meta-arviointi</h1>
				</div>
			</div>
		</div>
	</div>

	<div class="modal">
		<div class="modal-overlay"></div>
		<div class="modal-container">
			<div class="modal-content"></div>
			<a href="#" class="modal-close">Sulje</a>
		</div>
	</div>

	<?php
		//echo $this->element('sql_dump');
	?>
</body>
</html>
