<div class="row">
    <div class="twelvecol last">
    
    <?php
    if ( isset($this->data['Student']) ) {
        echo "<h1>".__('Lisää opiskelija kurssille')."</h1>";
        echo $this->Form->create('CourseMembership', array(
                'inputDefaults' => array(
                    'disabled' => true
                )
            )
        );
        echo $this->Form->input('Student.id', array('type' => 'hidden', 'disabled' => false));
    } else {
        echo "<h1>".__('Lisää uusi opiskelija kurssille')."</h1>";
        echo $this->Form->create('CourseMembership');
    }
    echo $this->Form->input('Student.first_name', array('label' => __('Etunimi')));
    echo $this->Form->input('Student.last_name', array('label' => __('Sukunimi')));
    echo $this->Form->input('Student.student_number', array('label' => __('Opiskelijanumero')));
    echo $this->Form->input('Student.email', array('label' => __('Sähköposti')));
    echo $this->Form->input('Course.id', array(
            'type' => 'hidden',
            'disabled' => false,
            'value' => $course_id
        )
    );

    echo $this->Form->input('User', array(
            'disabled' => false,
            'options' => $users,
            'empty' => __('Ei vastuuryhmää'),
            'label' => __('Vastuuryhmä')
        )
    );
    echo $this->Form->end('Tallenna');
    ?>
    </div>
</div>