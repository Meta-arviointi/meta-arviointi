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
			etunimi.sukunimi@uta.fi
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
		<a href='#' class="button float-right">Lisää toimenpide</a>

		<h2>Toimenpiteet</h2>

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

		<h3>Lisää toimenpide</h3>
		<?php
		echo $this->Form->create('Notification', array('url' => array('controller' => 'students', 'action' => 'add_notification')));
		echo $this->Form->input('student_id', array('type' => 'hidden', 'default' => $student['Student']['id']));
		echo $this->Form->input('content', array('label' => false));
		echo $this->Form->submit('Lisää');
		echo $this->Form->end();
		?>
	</div>
	<div class="threecol">
		<h2>Kommentit</h2>
	</div>
	<div class="threecol last">
		<h2>Sähköposti</h2>
	</div>
</div>