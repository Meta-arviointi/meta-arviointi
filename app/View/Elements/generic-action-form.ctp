<?php
$action = $action_data['Action'];

echo $this->Form->create('Action', array(
    'class' => 'generic-action-form', 
    'id' => 'generic-action-form', 
    'url' => array('controller' => 'actions', 'action' => 'add_action')
));

if (isset($action['id']) ) {
    echo $this->Form->input('id', array('type' => 'hidden', 'default' => $action['id']));
}
// Student id mandatory
echo $this->Form->input('student_id', array('type' => 'hidden', 'default' => $action_data['Student']['id']));

// If editing, take user_id from data. If creating new, take user_id from session
if (isset($action['user_id']) ) {
    echo $this->Form->input('user_id', array('type' => 'hidden', 'default' => $action['user_id']));
} else {
    echo $this->Form->input('user_id', array('type' => 'hidden', 'default' => $this->Session->read('Auth.User.id')));
}

// If $action_type_id is set, this means an ajax
// call from Acionts/edit, where we can't directly manipulate $action array.
if ( isset($action_type_id) ) {
    echo $this->Form->input('action_type_id', array('type' => 'hidden', 'default' => $action_type_id));
}
else { // basic
    echo $this->Form->input('action_type_id', array('type' => 'hidden', 'default' => $action['action_type_id']));
}

// Exercise checboxes
echo $this->Form->input('Exercise', array(
        'label' => __('Harjoitukset'),
        'options' => $exercises,
        'multiple' => 'checkbox',
        'selected' => isset($list_action_exercises) ? $list_action_exercises : null
    )
);
// If action is handled, display who and when
if ( isset($action['handled_id']) ) {
    echo $this->Form->label('handled_id', __('Käsitelty'));
    echo $this->Form->checkbox('handled_id', array(
        'hiddenField' => false,
        'value' => $action['handled_id'],
        'checked' => true
        )
    );
    echo '<div class="meta"><span>(' . __('Käsitellyt') . ': ' . $users[$action['handled_id']] . ' - ' 
        . date('j.n.Y G:i', strtotime($action['handled_time'])) . ')</span></div>';  
} else if ( $print_handled ) { // not yet handled, add checkbox with default value ''
    echo $this->Form->input('handled_id', array('type' => 'hidden', 'value' => ''));
    echo $this->Form->label('handled_id', __('Käsitelty'));
    echo $this->Form->checkbox('handled_id', array(
        'hiddenField' => false, // hidden value set manually above to be '',,
        'value' => $this->Session->read('Auth.User.id')
        )
    );
}

// if EXTRA or REQUEST form, add deadline
if ( ($action['action_type_id'] == 1 || (isset($action_type_id) && $action_type_id == 1)) ||
    ($action['action_type_id'] == 4 || (isset($action_type_id) && $action_type_id == 4)) ) {

    $deadline = isset($action['deadline']);

    $default_deadline_date = $deadline ? 
        date('d.m.Y', strtotime($action['deadline'])) : // if
            date('d.m.Y', strtotime('+ 7 day', strtotime(date('d.m.Y')))); // else
    echo $this->Form->input('deadline_date', array(
        'label'         => __('Aikaraja'),
        'id'            => 'action-deadline-date',
        'class'         => 'datepicker',
        'type'          => 'text',
        'default'       => $default_deadline_date
    ));
    echo $this->Form->input('deadline_time', array(
            'label'         => __('Kello'),
            'type'          => 'time',
            'timeFormat'    => 24,
            'interval'      => 15,
            'separator'     => ':',
            'selected'       => isset($deadline) ? 
                date('H:i:s', strtotime($action['deadline'])) :
                    '00:00:00'
        )
    );
}

echo $this->Form->input('description', array('label' => false, 'rows' => 3, 'default' => $action['description']));
if ( isset($action['created']) ) {
    echo '<span class="timestamp">Luotu: ' . date('j.n.Y G:i', strtotime($action['created'])) 
    . '</span>';
    echo '<br>';
}
if ( isset($action['modified']) && 
    $action['modified'] != $action['created'] ) {
    
    echo '<span class="timestamp">Viimeksi muokattu: ' 
        . date('j.n.Y G:i', strtotime($action['modified'])) . '</span>';
}
echo $this->Form->submit(__('Tallenna'), array('before' => '<a href="#" class="collapse-toggle cancel">' . __('Peruuta') . '</a>'));
echo $this->Form->end();
?>
<script>
    $('#action-deadline-date').datepicker(window.datepickerDefaults)
</script>

