<div class="row">
	<div class="twelvecol last">
	<?php echo $this->Html->link('Lisää uusi opiskelija', array('action' => 'add'), array('class' => 'button')); ?>

	<table class="data-table">
		<tr>
			<th>Sukunimi</th>
			<th>Etunimi</th>
			<th>Opiskelijanumero</th>
			<th>Toimenpiteitä</th>
		</tr>
		<?php
		foreach($students as $student) {
			echo '<tr>';
			echo '<td>'.$this->Html->link($student['Student']['last_name'], array('action' => 'view', $student['Student']['id'])).'</td>';
			echo '<td>'.$this->Html->link($student['Student']['first_name'], array('action' => 'view', $student['Student']['id'])).'</td>';
			echo '<td>'.$student['Student']['student_number'].'</td>';
			echo '<td>'.count($student['Notification']).'</td>';
			echo '</tr>';
		}
		?>
	</table>
	</div>
</div>