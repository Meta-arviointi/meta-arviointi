<?php

echo $this->Form->create('Student');
echo $this->Form->input('id', array('type' => 'hidden'));
echo $this->Form->input('first_name');

echo $this->Form->input('last_name');
echo $this->Form->input('student_number');

echo $this->Form->submit('Tallenna');
echo $this->Html->link('Peruuta', array('action' => 'view', $this->data['Student']['id']));
echo $this->Form->end();

?>