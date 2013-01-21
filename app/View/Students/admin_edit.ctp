<?php
echo '<h1>' . _('Muokkaa opiskelijan tietoja') . '</h1>';
echo $this->Form->create('Student');
echo $this->Form->input('id', array('type' => 'hidden'));
echo $this->Form->input('first_name', array('label' => __('Etunimi')));

echo $this->Form->input('last_name', array('label' => __('Sukunimi')));
echo $this->Form->input('student_number', array('label' => __('Opiskelijanumero')));
echo $this->Form->input('email', array('label' => __('E-mail')));

echo $this->Form->submit('Tallenna');
echo $this->Form->end();

?>