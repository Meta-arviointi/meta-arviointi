<div class="row">
	<div class="twelvecol last">
	<?php
	echo $this->Form->create('Student');
	echo $this->Form->input('first_name');

	echo $this->Form->input('last_name');
	echo $this->Form->input('student_number');
	echo $this->Form->input('email');

	echo $this->Form->submit('Tallenna');
	echo $this->Html->link('Peruuta', array('action' => 'index'));
	echo $this->Form->end();
	
	echo $this->Form->create('Student', array('type' => 'file'));
	echo $this->Form->file('tmp_file');
	echo $this->Form->end('upload');
	?>
	</div>
</div>
