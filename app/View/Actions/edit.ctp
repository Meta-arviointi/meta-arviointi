<h2>Muokkaa toimenpidettä</h2>
<h3><?php echo $action_types[$this->data['Action']['action_type_id']]; ?></h3>
<?php
//debug($this->data);
//debug($exercises);

// Variables
$action = $this->data['Action'];
$action_exercises = $this->data['Exercise'];

$list_action_exercises = null;
// Check if action belongs to multiple action_exercises
// and list the ID's. ID's are used in checboxes below.
if ( count($action_exercises) > 1 ) {
    foreach($action_exercises as $exercise) {
        $list_action_exercises[] = $exercise['id'];
    }
} else { // only one exercise
    $list_action_exercises = $action_exercises[0]['id'];
}

echo $this->Form->create('Action');
echo $this->Form->input('id', array('type' => 'hidden'));
echo $this->Form->input('user_id', array('type' => 'hidden'));
echo $this->Form->input('student_id', array('type' => 'hidden'));
echo $this->Form->input('action_type_id', array('type' => 'hidden'));
echo $this->Form->input('handled_id', array('type' => 'hidden', 'value' => ''));
echo $this->Form->label('handled_id', __('Käsitelty'));
echo $this->Form->checkbox('handled_id', array(
    'hiddenField' => false, // hidden value set manually above to be '',,
    'value' => empty($this->data['Action']['handled_id']) ? 
        $this->Session->read('Auth.User.id') : $action['handled_id']
    )
);
if ( !empty($this->data['Action']['handled_id']) ) {
    echo '<div class="meta"><span>(' . __('Käsitellyt') . ': ' . $users[$action['handled_id']] . ' - ' 
        . date('j.n.Y G:i', strtotime($action['handled_time'])) . ')</span></div>';
}
echo $this->Form->input('Exercise', array(
        'label' => __('Harjoitukset'),
        'options' => $exercises,
        'multiple' => 'checkbox',
        'selected' => $list_action_exercises
    )
);
echo $this->Form->input('description', array(
        'label' => __('Selite')
    )
);
if ( !empty($this->data['Action']['deadline']) ) {
    echo $this->Form->input('deadline', array(
        'label'         => __('Aikaraja'), 
        'default'       => date('d.m.Y H:i', strtotime($action['deadline'])),
        'timeFormat'    => 24, 
        'dateFormat'    => 'DMY',
        'interval'      => 15,
        'minYear'       => date('Y'),
        'maxYear'       => date('Y') + 1,
        'monthNames'    => false,
        'separator'     => '.'
    ));
}

echo '<span class="timestamp">Luotu: ' . date('j.n.Y G:i', strtotime($action['created'])) 
    . '</span>';
echo '<br>';
if ( !empty($this->data['Action']['modified']) ) {
    echo '<span class="timestamp">Viimeksi muokattu: ' 
        . date('j.n.Y G:i', strtotime($action['modified'])) . '</span>';
}
echo $this->Form->end(__('Tallenna'));
