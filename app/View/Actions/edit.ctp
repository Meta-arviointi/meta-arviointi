<h2>Muokkaa toimenpidettä</h2>
<?php
//debug($this->data);
echo $this->Form->create('Action');
echo $this->Form->input('id', array('type' => 'hidden'));
echo $this->Form->input('user_id', array('type' => 'hidden'));
echo $this->Form->input('student_id', array('type' => 'hidden'));
echo $this->Form->input('action_type_id', array('type' => 'hidden'));
echo $this->Form->input('handled_id', array(
    'options' => array($this->Session->read('Auth.User.id') => __('Käsitelty')),
    'empty' => array(null => __('Ei käsitelty')),
    'default' => $this->data['Action']['handled_id']
    )
);
echo $this->Form->input('description');
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

echo '<span class="timestamp">Luotu: ' . date('d.m.Y H:i', strtotime($this->data['Action']['created'])) 
    . '</span>';
echo '<br>';
if ( !empty($this->data['Action']['modified']) ) {
    echo '<span class="timestamp">Viimeksi muokattu: ' 
        . date('d.m.Y H:i', strtotime($this->data['Action']['modified'])) . '</span>';   
}
echo $this->Form->end(__('Tallenna'));
?>