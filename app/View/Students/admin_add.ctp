<div class="row">
	<div class="twelvecol last">
	<h1>Lisää uusia opiskelijoita (CSV tiedosto)<br/>TAI<br/>lisää uusi opiskelija</h1>
	<?php
	echo $this->Form->create('Student', array('type' => 'file'));
	echo $this->Form->file('tmp_file');
	echo $this->Form->end('Lähetä');

	echo '<br/>';
	echo '<br/>';

	echo $this->Form->create('Student');
	echo $this->Form->input('first_name');

	echo $this->Form->input('last_name');
	echo $this->Form->input('student_number');
	echo $this->Form->input('email');

	echo $this->Form->submit('Tallenna');
//	echo $this->Html->link('Peruuta', array('action' => 'index'));
	echo $this->Form->end();
	?>
	</div>
</div>