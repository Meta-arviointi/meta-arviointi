<div id="course-selection-dropdown">
<?php
    echo $this->Form->create(false, array(
            'type' => 'get',
            'action' => $this->params['action'].'_rdr',
            'id' => 'UserCourseSelection'
        )
    );
    $course_id = $this->Session->read('Course.course_id');
    echo $this->Form->input('course_id', array(
            'options' => $users_courses,
            'default' => isset($users_courses[$course_id]) ? $course_id : "",
            'empty' => 'Valitse kurssi',
            'label' => __('Kurssi'),
        )
    );
    echo $this->Form->end();
?>
</div>
