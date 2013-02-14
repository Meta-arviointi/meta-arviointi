<?php
$action = !empty($action_data['Action']) ? $action_data['Action'] : null;

echo $this->Form->create('Action', array(
    'class' => 'generic-action-form', 
    'id' => 'generic-action-form', 
    'url' => array('controller' => 'actions', 'action' => 'save')
));

if (isset($action['id']) ) {
    echo $this->Form->input('id', array('type' => 'hidden', 'default' => $action['id']));
}
// CourseMembership.id mandatory
echo $this->Form->input('course_membership_id', array('type' => 'hidden', 'default' => $action_data['CourseMembership']['id']));

// If editing, take user_id from data. If creating new, take user_id from session
if (isset($action['user_id']) ) {
    echo $this->Form->input('user_id', array('type' => 'hidden', 'default' => $action['user_id']));
} else {
    echo $this->Form->input('user_id', array('type' => 'hidden', 'default' => $this->Session->read('Auth.User.id')));
}

// If $action_type_id is set, this means an ajax
// call from Acionts/edit
if ( isset($action_type_id) ) {
    echo $this->Form->input('action_type_id', array('type' => 'hidden', 'default' => $action_type_id));
}
else if ( isset($action['action_type_id']) ) { // basic
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

// If new requested form ($action_type_id is set) is requested, print deadline if
// requested action_type_id is 1 or 2.
// If this is NOT new request ($action_type_id = 0), check if original action is
// type of 1 or 4.
// If these conditions don't evaluate true, then it's action type which doesn't need deadline
// TODO: these are hard-coded values, types 1 and 4 need deadline-textinput field
if ( (isset($action_type_id) && ($action_type_id == 1 || $action_type_id == 4)) || 
     (!isset($action_type_id) && ($action['action_type_id'] == 1 || $action['action_type_id'] == 4)) ) {

    $deadline = isset($action['deadline']);

    $default_deadline_date = $deadline ? 
        date('d.m.Y H:i', strtotime($action['deadline'])) : // if deadline already set
            date('d.m.Y H:i', strtotime('+ 7 day', strtotime(date('d.m.Y H:i')))); // else = today + 7 days
    echo $this->Form->input('deadline', array(
        'label'         => __('Aikaraja'),
        'id'            => 'action-deadline',
        'class'         => 'datetimepicker',
        'type'          => 'text',
        'default'       => $default_deadline_date
    ));
}

echo $this->Form->input('description', array('label' => false, 'rows' => 3, 'default' => isset($action) ? $action['description'] : null));

if ( isset($action['created']) ) {
    echo '<span class="timestamp">Luotu: ' . date('j.n.Y G:i', strtotime($action['created'])) 
    . '</span>';
    echo '<br>';
}
// show modified if it differs from created
if ( isset($action['modified']) && 
    $action['modified'] != $action['created'] ) {
    
    echo '<span class="timestamp">Viimeksi muokattu: ' 
        . date('j.n.Y G:i', strtotime($action['modified'])) . '</span>';
}
echo $this->Form->submit(__('Tallenna'));
echo $this->Form->end();
?>
<script>
    $('#action-deadline').datetimepicker(window.datepickerDefaults)
</script>

