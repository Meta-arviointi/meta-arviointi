<div class="row">
    <div class="twelvecol last">
    <h1>Lisää uusi opiskelija kurssille</h1>
    <?php

    echo $this->Form->create('CourseMembership');
    echo $this->Form->input('Student.first_name', array('label' => __('Etunimi')));
    echo $this->Form->input('Student.last_name', array('label' => __('Sukunimi')));
    echo $this->Form->input('Student.student_number', array('label' => __('Opiskelijanumero')));
    echo $this->Form->input('Student.email', array('label' => __('Sähköposti')));
    echo $this->Form->input('Course.id', array(
            'type' => 'hidden',
            'value' => $course_id
        )
    );

    echo $this->Form->input('User', array(
            'options' => $users,
            'label' => __('Vastuuryhmä')
        )
    );
    echo $this->Form->end('Tallenna');
    ?>
    </div>
</div>