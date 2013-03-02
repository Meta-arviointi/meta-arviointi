<?php
echo $this->Form->create('Course');
echo $this->Form->input('id', array('type' => 'hidden'));
echo $this->Form->input('User', array(
		'label' => 'Assistentit',
		'multiple' => 'checkbox',
		'options' => $users
	)
);
echo $this->Form->end(__('Tallenna'));
?>
