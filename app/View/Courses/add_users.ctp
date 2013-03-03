<?php
echo $this->Form->create('Course');
echo $this->Form->input('id', array('type' => 'hidden'));

$checked = $this->Form->value('User.User');
echo $this->Form->label(__('Assistentit'));
echo $this->Form->input('User.User', array('type' => 'hidden', 'value' => ''));
foreach($users as $uid => $uname) {
    $disabled = in_array($uid, $user_groups)? true : false;
    echo $this->Form->input("User.User.", array(
            'label' => $uname,
            'id' => "UserUser$uid",
            'type' => 'checkbox',
            'value' => $uid,
            'hiddenField' => false,
            'checked' => (isset($checked[$uid]) ? 'checked' : false),
            'disabled' => $disabled,
            'div' => array(
                'class' => 'checkbox'
            )
        )
    );
    if ( $disabled ) { // include $uid:s in POST
        echo $this->Form->input('User.User.', array(
                'type' => 'hidden',
                'value' => $uid
            )
        );
    }
}
echo $this->Form->end(__('Tallenna'));
echo '<span>Assistenttiä ei voi poistaa kurssilta, jos vastuuryhmässä on opiskelijoita.</span>';
?>
