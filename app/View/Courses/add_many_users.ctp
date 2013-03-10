<script type="text/javascript">
    $(document).ready(function() {
    	// Inlcude Student-checkboxes in form before submit
        $('#CourseAddManyUsersForm').submit(function() {
            // #EditStudentGroups is in /courses/view !
            $(this).append(($('#SelectManyUsers').find('input[type="checkbox"]:checked')).attr('type','hidden'));
            return true;
        });

    });
</script>
<?php
echo '<h2>'.__('Liitä assistentit kurssille').'</h2>';
echo $this->Form->create('Course', array('action' => 'add_many_users'));
echo $this->Form->input('id', array(
		'label' => __('Kurssi'),
		'empty' => __('Valitse kurssi'),
		'options' => $courses
	)
);
echo $this->Form->end('Tallenna');