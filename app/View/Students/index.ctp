<script type="text/javascript">
$(document).ready(function() {
	$('#StudentGroup').change(function() {
		window.location = '/meta-arviointi/students/index/' + $(this).val();
	});
})
</script>
<div class="row">
	<div class="twelvecol last">
	<?php echo $this->Html->link('Lisää uusi opiskelija', array('action' => 'add'), array('class' => 'button')); ?>



	<?php 
	 /* DEBUG */
	//echo '<pre>';
	// var_dump($students);
	//var_dump($user_groups);
	//var_dump($students[1]);
	//echo '</pre>';

	// Selection for assistent groups
	echo $this->Form->create('Student');
	echo $this->Form->label('group', 'Vastuuryhmä');
	echo $this->Form->select('group', $user_groups, array('empty' => array(0 => 'Kaikki'), 'default' => $group_id));
	echo $this->Form->end();
	?>

	<table class="data-table">
		<tr>
			<th><?php echo $this->Paginator->sort('last_name', 'Sukunimi'); ?></th>
			<th><?php echo $this->Paginator->sort('first_name', 'Etunimi'); ?></th>
			<th><?php echo $this->Paginator->sort('student_number', 'Opiskelijanumero'); ?></th>
			<th>Assistentti</th> <!-- TODO: Assistentti Paginator-sorttuas -->
			<th>Toimenpiteitä</th>
		</tr>
		<?php
		foreach($students as $student) {
			echo '<tr>';
			echo '<td>'.$this->Html->link($student['Student']['last_name'], array('action' => 'view', $student['Student']['id'])).'</td>';
			echo '<td>'.$this->Html->link($student['Student']['first_name'], array('action' => 'view', $student['Student']['id'])).'</td>';
			echo '<td>'.$student['Student']['student_number'].'</td>';

			/* If student belongs to a group, print assistant name */
			if ( isset($student['Group']['User']) ) {
				echo '<td>'.$student['Group']['User']['name'].'</td>';
			} else {
				/* If not in any group, leave cell empty */
				echo '<td></td>';

			}
			
			// Jos ei tietokantataulut ole vielä kunnossa kommentoi yo. if ja laita vain "Asseri Assistentti":
			//echo '<td>Asseri Assistentti</td>';
			echo '<td>'.count($student['Note']).'</td>';
			echo '</tr>';
		}
		?>
	</table>
	</div>
</div>
