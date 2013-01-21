<h2>Muokkaa kommenttia</h2>
<?php
echo $this->Form->create('CourseMembership');
echo $this->Form->input('id', array('type' => 'hidden'));
echo $this->Form->input('comment', array('label' => false));
echo $this->Form->end(__('Tallenna'));
?>