<div class="row">
	<div class="twelvecol last">
		<?php
		echo $this->Html->link('Takaisin listaukseen', array('action' => 'index'), array('class' => 'button'));
		echo '<br>';
		echo '<br>';
		echo $student['Student']['last_name'];
		echo $student['Student']['first_name'];
		echo '<br>';
		echo $student['Student']['student_number'];
		echo '<br>';
		echo '<br>';
		echo $this->Html->link('Poista', array('action' => 'delete', $student['Student']['id']), array('class' => 'button'));
		echo ' ';
		echo $this->Html->link('Muokkaa', array('action' => 'edit', $student['Student']['id']), array('class' => 'button'));
		echo '<br>';
		echo '<br>';
		?>
	</div>
</div>
<div class="row">
	<div class="sixcol">
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