<h2>Muokkaa toimenpidettä</h2>
<h3><?php echo $action_types[$this->data['Action']['action_type_id']]; ?></h3>
<?php
//debug($this->data);
echo $this->Form->create('Action');
echo $this->Form->input('id', array('type' => 'hidden'));
echo $this->Form->input('user_id', array('type' => 'hidden'));
echo $this->Form->input('student_id', array('type' => 'hidden'));
echo $this->Form->input('action_type_id', array('type' => 'hidden'));
echo $this->Form->input('handled_id', array('type' => 'hidden', 'value' => ''));
echo $this->Form->label('handled_id', __('Käsitelty'));
echo $this->Form->checkbox('handled_id', array(
    'hiddenField' => false, // hidden value set manually above to be ''
    'label' => __('Käsitelty'),
    'empty' => array(null),
    'value' => empty($this->data['Action']['handled_id']) ? 
        $this->Session->read('Auth.User.id') : $this->data['Action']['handled_id']
    )
);
if ( !empty($this->data['Action']['handled_id']) ) {
    echo '(' . __('Käsitellyt') . ': ' . $users[$this->data['Action']['handled_id']] . ' - ' 
        . date('j.n.Y G:i', strtotime($this->data['Action']['handled_time'])) . ')';
}
echo $this->Form->input('description', array(
        'label' => __('Selite')
    )
);
if ( !empty($this->data['Action']['deadline']) ) {
    echo $this->Form->input('deadline', array(
        'label'         => __('Aikaraja'), 
        'default'       => date('d.m.Y H:i', strtotime($this->data['Action']['deadline'])), 
        'timeFormat'    => 24, 
        'dateFormat'    => 'DMY',
        'interval'      => 15,
        'minYear'       => date('Y'),
        'maxYear'       => date('Y') + 1,
        'monthNames'    => false,
        'separator'     => '.'
    ));
}

echo '<span class="timestamp">Luotu: ' . date('j.n.Y G:i', strtotime($this->data['Action']['created'])) 
    . '</span>';
echo '<br>';
if ( !empty($this->data['Action']['modified']) ) {
    echo '<span class="timestamp">Viimeksi muokattu: ' 
        . date('j.n.Y G:i', strtotime($this->data['Action']['modified'])) . '</span>';   
}
echo $this->Form->end(__('Tallenna'));
