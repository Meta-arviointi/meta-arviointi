<div class="row">
	<div class="twelvecol last">
		<?php
			echo $this->Html->link(__('Opiskelijat'), array('action' => 'index'));
			echo ' | ';
			echo __('Toimenpiteet')
		?>
		<hr>
	</div>
</div>
<div class="row">
	<div class="twelvecol last">
	<?php 
	 /* DEBUG */
	echo '<pre>';
//	debug($actions);
//	debug($course_memberships);
	echo '</pre>';

	// Selection for assistent groups
	/*echo $this->Form->create(false, array('id' => 'StudentIndexFilters', 'type' => 'get'));
	echo $this->Form->label('group', 'Vastuuryhmä');
	echo $this->Form->select('group_id', $user_groups, array('empty' => array(0 => 'Kaikki'), 'default' => $group_id));
	echo $this->Form->end();
	*/
	?>

	<table class="data-table">
		<tr>
			<th><?php echo __('Tyyppi'); ?></th>
			<th><?php echo __('Harjoitus'); ?></th>
			<th><?php echo __('Opiskelija'); ?></th>
			<th><?php echo __('Lisännyt'); ?></th>
		</tr>
		<!--<tr> paginaatio poistettu käytöstä (toistaiseksi)
			<th><?php echo $this->Paginator->sort('last_name', 'Sukunimi'); ?></th>
			<th><?php echo $this->Paginator->sort('first_name', 'Etunimi'); ?></th>
			<th><?php echo $this->Paginator->sort('student_number', 'Opiskelijanumero'); ?></th>
			<th>Assistentti</th>
			<th>Toimenpiteitä</th>
		</tr> -->
		<?php
		foreach($actions as $action) {
			echo '<tr>';
			echo '<td>' . $action['ActionType']['name'] . '</td>';
			echo '<td>' . $action['Exercise']['exercise_number'] . '</td>';
			echo '<td>' . $this->Html->link($action['Student']['last_name'] . ', ' . $action['Student']['first_name'], array('controller' => 'course_memberships', 'action' => 'view', $course_memberships[$action['Student']['id']])) . '</td>';
			echo '<td>' . $action['User']['name'] . '</td>';
			echo '</tr>';
		}
		?>
	</table>
	</div>
</div>
