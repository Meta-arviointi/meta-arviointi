<?php

echo $this->Form->input('User', array(
        'disabled' => false,
        'options' => $users,
        'empty' => __('Ei vastuuryhmää'),
        'label' => __('Vastuuryhmä')
    )
);
echo $this->Form->end("Tallenna");

?>
