<div class="exercises form">
<?php 
	$course_id = $this->Session->read('Course.course_id');
	$next_number = isset($next_number) ? $next_number : 0;
    echo $this->Form->create('Exercise'); ?>
    <fieldset>
        <legend><?php echo __('Uusi harjoitus'); ?></legend>
    <?php
    echo $this->Form->input('exercise_number', array('label' => 'Harj. numero', 'value' => $next_number));
	echo $this->Form->input('exercise_name', array('label' => 'Harj. nimi'));
	echo $this->Form->input('starttime', array('label' => 'Tehtävän Alkamispäivä', 'type' => 'text', 'class' => 'datetimepicker', 'id' => 'ExerciseStarttime'));
	echo $this->Form->input('endtime', array('label' => 'Tehtävän Loppumispäivä', 'type' => 'text', 'class' => 'datetimepicker', 'id' => 'ExerciseEndtime'));
	echo $this->Form->input('review_starttime', array('label' => 'Arvioinnin Alkamispäivä', 'type' => 'text', 'class' => 'datetimepicker', 'id' => 'ReviewStarttime'));
	echo $this->Form->input('review_endtime', array('label' => 'Arvioinnin Loppumispäivä', 'type' => 'text', 'class' => 'datetimepicker', 'id' => 'ReviewEndtime'));
	echo $this->Form->hidden('course_id', array('value' => $course_id));
    ?>
    </fieldset>
<?php echo $this->Form->end(__('Lisää harjoitus')); ?>
</div>

<script>
	$('#ExerciseStarttime, #ExerciseEndtime, #ReviewStarttime, #ReviewEndtime').datetimepicker(window.datepickerDefaults)
</script>
