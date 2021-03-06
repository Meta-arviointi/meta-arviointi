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
		echo $this->Html->script('jquery-ui-timepicker-addon.js');
		echo $this->Html->script('jquery.scrollTo.min');
		echo $this->Html->script('jquery.ba-bbq.min');
		echo $this->Html->script('jquery.tablesorter.min');
		echo $this->Coffee->import('ajaxfilters');
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
				<div class="fivecol">
					<h1><?php echo $this->Html->link(__('Meta-arviointi'), array(
							'admin' => false,
							'controller' => 'students',
							'action' => 'index'
							),
							array('class' => 'root-link')
						) ?>
					</h1>
					<?php 
						if ( $course_selection && isset($users_courses)) {
							echo '<div id="static-course"><span id="course-name">'.
									$all_courses[$this->Session->read('Course.course_id')].
								'</span></div>';
							echo '<div id="course-selection-toggle">';
							echo '<a class="header-button" href="#">Omat kurssit</a>';
							echo '<div id="course-selection-box">';

							echo $this->element('course-selection', array('users_courses', $users_courses));
							echo '</div>';
							echo '</div>';
						}
					?>
				</div>
				<div class="sevencol last">
					<?php
						if($this->Session->read('Auth.User')) {
							echo '<div id="login-details">';
							$profile_link_classes = 'header-button';
							$viewing_profile = false;
							if(
								$this->request->params['controller'] == 'users' &&
								$this->request->params['action'] == 'view' &&
								$this->request->params['pass'][0] == $this->Session->read('Auth.User.id')
							) {
								$viewing_profile = true;
							}

							if($viewing_profile) { $profile_link_classes .= ' selected'; }
							echo $this->Html->link(
								$this->Session->read('Auth.User.name'),
								array(
									'admin' => false,
									'controller' => 'users',
									'action' => 'view',
									$this->Session->read('Auth.User.id')
									),
								array('id' => 'my-profile-link', 'class' => $profile_link_classes)
							);
							echo $this->Html->link(
								__('Kirjaudu ulos'),
								array('admin' => false, 'controller' => 'users', 'action' => 'logout', 'course_id' => false),
								array('id' => 'logout-link', 'class' => 'header-button')
							);
							echo '</div>';

							echo '<div id="admin-link">';
							$admin_link_classes = 'header-button';
							if(!empty($this->request->params['admin'])) $admin_link_classes .= ' selected';
							echo $this->Html->link(__('Järjestelmä'), array(
									'admin' => true,
									'controller' => 'courses',
									'action' => 'index'
								), 
								array(
									'class' => $admin_link_classes
								)
							);
							echo '</div>';
							if ( !empty($all_courses) ) {
								echo '<div id="courses-link">';
								$courses_link_classes = 'header-button';
								if(empty($this->request->params['admin']) && !$viewing_profile) $courses_link_classes .= ' selected';
								echo $this->Html->link(__('Kurssi'), array(
										'admin' => false,
										'controller' => 'students',
										'action' => 'index'
									), 
									array(
										'class' => $courses_link_classes
									)
								);
								echo '</div>';	
							}
							echo '<div id="mail-indicator">';
							echo '<a href="#" class="header-button">';
							echo count($email_notifications);
							echo '</a>';
							echo '<div id="new-email-notifications">';
							if(!empty($email_notifications)) {
								foreach($email_notifications as $msg) {
									$href = $this->Html->url(array(
										'admin' 		=> false,
										'controller' 	=> 'CourseMemberships',
										'action' 		=> 'view',
										$msg['course_membership_id']
									));
									echo '<a href="'.$href.'" class="email-notification">';
									echo '<span class="from">' . $msg['sender'] . '</span>';
									echo '<span class="subject">' . $msg['subject'] . '</span>';
									echo '<span class="timestamp">' . date('d.m.Y H:i:s', strtotime($msg['sent_time'])) . '</span>';
									echo '</a>';
								}
							}
							echo '</div>';
							echo '</div>';
						} 
					?>
				</div>
				
			</div>
		</div>
	</div>

	<?php
		if($this->Session->read('Auth.User')) { ?>
			<div id="chat" class="<?php echo $this->Session->read('Chat.window_state'); ?>">
				<div class="chat-header">
					<a href="#" id="chat-toggle"></a>
					Chat
				</div>
				<div class="chat-viewport">
					<div class="chat-messages">
						<?php foreach($chat_messages as $msg) {
							echo '<div class="chat-message" data-msg-id="'.$msg['ChatMessage']['id'].'">';
							echo '<span class="timestamp">'.date('H:i', strtotime($msg['ChatMessage']['created'])).'</span><span class="user">'.$msg['User']['first_name'].' '.$msg['User']['last_name'].'</span>';
							echo '<p class="chat-message-content">'.$msg['ChatMessage']['content'].'</p>';
							echo '</div>';
						} ?>
					</div>
				</div>
				<input type="text" name="chat-input" id="chat-input">
			</div>
		<?php } ?>
	<div class="modal">
		<div class="modal-overlay"></div>
		<div class="modal-container">
			<div class="modal-content"></div>
			<a href="#" class="modal-close">Sulje</a>
		</div>
	</div>

	<?php
		echo $this->element('sql_dump');
	?>
</body>
</html>
