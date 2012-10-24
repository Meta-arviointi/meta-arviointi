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
		<a href='#' class="button float-right" id="add-notification-link">Lisää toimenpide</a>

		<h2>Toimenpiteet</h2>

		<?php
		echo $this->Form->create('Notification', array('id' => 'add-notification-form', 'url' => array('controller' => 'students', 'action' => 'add_notification')));
		echo $this->Form->input('student_id', array('type' => 'hidden', 'default' => $student['Student']['id']));
		echo $this->Form->input('content', array('label' => false));
		echo $this->Form->submit('Lisää');
		echo $this->Form->end();
		?>

		<table class="data-table">
		<?php
		foreach($student['Notification'] as $notification) {
			echo '<tr>';
			echo '<td>'.date('d.m.Y H:i:s', strtotime($notification['created'])).'</td>';
			echo '<td>'.$notification['content'].'</td>';
			echo '</tr>';
		}
		?>
		</table>
	</div>
	<div class="threecol">
		<h2>Kommentit</h2>
	</div>
	<div class="threecol last">
		<h2>Sähköposti</h2>
	</div>
</div>