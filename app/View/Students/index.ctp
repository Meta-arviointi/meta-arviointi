<div class="row">
	<div class="twelvecol last">
	<?php echo $this->Html->link('Lisää uusi opiskelija', array('action' => 'add'), array('class' => 'button')); ?>



	<?php 
	/* DEBUG
	echo '<pre>';
	//var_dump($user_groups);
	//var_dump($groups);
	var_dump($users);
	echo '</pre>';
	*/

	// Selection for assistent groups (not ready yet!)
	echo $this->Form->create('Student', array('action' => 'filter', 'type' => 'get')); // Tähän jotain AJAXia?
	echo $this->Form->label('Vastuuryhmä');
	echo $this->Form->select('group', $user_groups, array('empty' => array(0 => 'Kaikki')));
	echo $this->Form->end();
	?>

	<table class="data-table">
		<tr>
			<th><?php echo $this->Paginator->sort('last_name', 'Sukunimi'); ?></th>
			<th><?php echo $this->Paginator->sort('first_name', 'Etunimi'); ?></th>
			<th><?php echo $this->Paginator->sort('student_number', 'Opiskelijanumero'); ?></th>
			<th>Assistentti</th>
			<th>Toimenpiteitä</th>
		</tr>
		<?php
		foreach($students as $student) {
			echo '<tr>';
			echo '<td>'.$this->Html->link($student['Student']['last_name'], array('action' => 'view', $student['Student']['id'])).'</td>';
			echo '<td>'.$this->Html->link($student['Student']['first_name'], array('action' => 'view', $student['Student']['id'])).'</td>';
			echo '<td>'.$student['Student']['student_number'].'</td>';
			echo '<td>Asseri Assistentti</td>';
			echo '<td>'.count($student['Notification']).'</td>';
			echo '</tr>';
		}
		?>
	</table>
	</div>
</div>