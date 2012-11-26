<?php 
	 /* DEBUG */
	echo '<pre>';
	//var_dump($student);
	echo '</pre>';
?>
<div class="row">
	<div class="twelvecol last">
		<?php
		echo $this->Html->link('&larr; Takaisin listaukseen', array('action' => 'index'), array('escape' => false));
		?>
	</div>
</div>
<hr class="row">
<div class="row">
	<div class="ninecol">
		<?php

		echo '<h1>';
		echo '<strong>' . trim($student['Student']['first_name']) . ' ' . trim($student['Student']['last_name']) . '</strong> ' . $student['Student']['student_number'];
		echo '</h1>';
		?>
		<p>
			<?php echo $student['Student']['email'] ?>
		</p>
		<p>Kommentti: <?php echo empty($student['CourseMembership'][0]['comment']) ? '-' : $student['CourseMembership'][0]['comment'] ?></p>

	</div>
	<div class="threecol last">
		<?php
		echo $this->Html->link('Muokkaa', array('action' => 'edit', $student['Student']['id']), array('class' => 'button float-right'));
		echo $this->Html->link('Poista', array('action' => 'delete', $student['Student']['id']), array('class' => 'button float-right'), 'Haluatko varmasti poistaa opiskelijan järjestelmästä?');
		?>
	</div>
</div>
<hr class="row">
<div class="row student-entries">
	<div class="sixcol">

		<h2>Toimenpiteet</h2>

		<?php
		echo $this->Form->create('Action', array('id' => 'add-action-form', 'url' => array('controller' => 'students', 'action' => 'add_action')));
		echo '<a href="#" class="decollapse-toggle">' . __('Lisää toimenpide') . '</a>';
		echo '<div class="collapsable">';
		echo $this->Form->input('student_id', array('type' => 'hidden', 'default' => $student['Student']['id']));
		echo $this->Form->input('user_id', array('type' => 'hidden', 'default' => $this->Session->read('Auth.User.id')));


		echo $this->Form->input('type', array('label' => __('Tyyppi'), 'options' => $action_types));
		echo $this->Form->input('exercise_id', array('label' => __('Harjoitus'), 'options' => $exercises));
		$default_deadline = date('Y-m-d') . ' 16:00:00';
		$default_deadline = date('Y-m-d H:i:s', strtotime('+ 7 day', strtotime($default_deadline)));
		echo $this->Form->input('deadline', array(
			'label' 		=> __('Aikaraja'), 
			'default' 		=> $default_deadline, 
			'timeFormat' 	=> 24, 
			'dateFormat' 	=> 'DMY',
			'interval' 		=> 15,
			'minYear'		=> date('Y'),
			'maxYear'		=> date('Y') + 1,
			'monthNames'	=> false,
			'separator'		=> '.'
		));
		echo $this->Form->input('comment', array('label' => false, 'rows' => 3));
		echo $this->Form->submit(__('Lisää'), array('before' => '<a href="#" class="collapse-toggle cancel">' . __('Peruuta') . '</a>'));
		echo '</div>';
		echo $this->Form->end();

		foreach($student['Action'] as $action) {
			$action_title = $action['ActionType']['name'];
			if(!empty($action['exercise_id'])) $action_title = 'H' . $action['exercise_id'] . ': ' . $action_title;
			echo '<div class="action">';
			echo '<h3>' . $action_title . '</h3>';
			if(!empty($action['description'])) echo '<p class="comment">'.$action['description'].'</p>';
			echo '<div class="meta">';
			echo '<span class="timestamp">'.date('d.m.Y H:i:s', strtotime($action['created'])).'</span> - ';
			echo '<span class="by">' . $action['User']['name'] . '</span>';
			echo '</div>';
			echo '</div>';
		}
		?>
	</div>
	<div class="threecol">
		<h2>Kommentit</h2>
		<p>Yleinen kommentti siirretty opiskelijan tietojen alle.</p>
	</div>
	<div class="threecol last">
		<h2>Sähköposti</h2>
	</div>
</div>
