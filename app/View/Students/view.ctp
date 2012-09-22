<?php
echo $this->Html->link('Takaisin listaukseen', array('action' => 'index'));
echo '<br>';
echo '<br>';
echo $student['Student']['last_name'];
echo $student['Student']['first_name'];
echo '<br>';
echo $student['Student']['student_number'];
echo '<br>';
echo '<br>';
echo $this->Html->link('Poista', array('action' => 'delete', $student['Student']['id']));
echo ' | ';
echo $this->Html->link('Muokkaa', array('action' => 'edit', $student['Student']['id']));
echo '<br>';
echo '<br>';
?>

<h2>Merkinnät</h2>

<table>
<?php
foreach($student['Notification'] as $notification) {
	echo '<tr>';
	echo '<td>'.date('d.m.Y H:i:s', strtotime($notification['created'])).'</td>';
	echo '<td>'.$notification['content'].'</td>';
	echo '</tr>';
}
?>
</table>

<h3>Lisää merkintä</h3>
<?php
echo $this->Form->create('Notification', array('url' => array('controller' => 'students', 'action' => 'add_notification')));
echo $this->Form->input('student_id', array('type' => 'hidden', 'default' => $student['Student']['id']));
echo $this->Form->input('content');
echo $this->Form->submit('Lisää uusi merkintä');
echo $this->Form->end();
?>