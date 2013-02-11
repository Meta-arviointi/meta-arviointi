<div class="exercises form">
<?php 
    echo $this->Form->create('Exercise'); ?>
    <fieldset>
        <legend><?php echo __('Uusi harjoitus'); ?></legend>
    <?php
	echo $this->Form->input('exercise_name', array('label' => 'Harjoitus'));
	echo $this->Form->input('starttime', array('label' => 'Tehtävän Alkamispäivä', 'type' => 'text', 'class' => 'datepicker', 'id' => 'ExerciseStarttime'));
	echo $this->Form->input('endtime', array('label' => 'Tehtävän Loppumispäivä', 'type' => 'text', 'class' => 'datepicker', 'id' => 'ExerciseEndtime'));
	echo $this->Form->input('review_starttime', array('label' => 'Arvioinnin Alkamispäivä', 'type' => 'text', 'class' => 'datepicker', 'id' => 'ReviewStarttime'));
	echo $this->Form->input('review_endtime', array('label' => 'Arvioinnin Loppumispäivä', 'type' => 'text', 'class' => 'datetimepicker', 'id' => 'ReviewEndtime'));
    ?>
    </fieldset>
<?php echo $this->Form->end(__('Lisää harjoitus')); ?>
</div>

<script>
	$('#ExerciseStarttime, #ExerciseEndtime, #ReviewStarttime, #ReviewEndtime').datepicker(window.datepickerDefaults)
</script>
