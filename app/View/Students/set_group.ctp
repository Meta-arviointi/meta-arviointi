<h1>Valitse uusi vastuuryhmä</h1>
<?php
echo $this->Form->create('User');
echo $this->Form->input('Student.id', array('type' => 'hidden', 'default' => $student_id));
echo $this->Form->input('id', array(
        'label' => __('Assistentti'),
        'empty' => __('Valitse uusi vastuuryhmä'),
        'options' => $users
    )
);
echo $this->Form->end('Tallenna');