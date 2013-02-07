<?php
// KORJAUSPYYNTÖ
echo $this->Form->create('Action', array(
    'class' => 'student-action-form', 
    'id' => 'request-action-form', 
    'url' => array('controller' => 'actions', 'action' => 'add_action')
));
echo $this->Form->input('student_id', array('type' => 'hidden', 'default' => $course_membership['Student']['id']));
echo $this->Form->input('user_id', array('type' => 'hidden', 'default' => $this->Session->read('Auth.User.id')));
echo $this->Form->input('action_type_id', array('type' => 'hidden', 'default' => '1'));
echo $this->Form->input('Exercise', array(
        'label' => __('Harjoitukset'),
        'options' => $exercises,
        'multiple' => 'checkbox'
    )
);
// If $print_handled set to true, add handled-checbox to form
if ( $print_handled ) {
    echo $this->Form->input('handled_id', array('type' => 'hidden', 'value' => ''));
    echo $this->Form->label('handled_id', __('Käsitelty'));
    echo $this->Form->checkbox('handled_id', array(
        'hiddenField' => false, // hidden value set manually above to be '',,
        'value' => $this->Session->read('Auth.User.id')
        )
    );    
}

$default_deadline_date = date('d.m.Y', strtotime('+ 7 day', strtotime(date('d.m.Y'))));
echo $this->Form->input('deadline_date', array(
    'label'         => __('Uusi aikaraja'),
    'class'         => 'datetimepicker',
    'type'          => 'text',
    'default'       => $default_deadline_date
));
echo $this->Form->input('deadline_time', array(
        'label'         => __('Kello'),
        'type'          => 'time',
        'timeFormat'    => 24,
        'interval'      => 15,
        'separator'     => ':',
        'selected'       => '00:00:00'
    )
);
echo $this->Form->input('description', array('label' => false, 'rows' => 3));
echo $this->Form->submit(__('Lisää'), array('before' => '<a href="#" class="collapse-toggle cancel">' . __('Peruuta') . '</a>'));
echo $this->Form->end();
