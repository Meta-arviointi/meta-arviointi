<div id="course-selection-dropdown">
<?php
    echo $this->Form->create(false, array(
            'type' => 'get',
            'action' => $this->params['action'].'_rdr',
            'id' => 'UserCourseSelection'
        )
    );
    echo $this->Form->input('course_id', array(
            'options' => $users_courses,
            'default' => $this->Session->read('Course.course_id'),
            'label' => __('Kurssi'),
        )
    );
    echo $this->Form->end();
?>
</div>
