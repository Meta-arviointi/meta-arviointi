<div class="courses form">
<?php 
    echo $this->Form->create('Course'); ?>
    <fieldset>
        <legend><?php echo __('Lisää uusi kurssi'); ?></legend>
    <?php
	echo $this->Form->input('name');
	echo $this->Form->input('starttime', array('type' => 'text', 'class' => 'datepicker', 'id' => 'CourseStarttime'));
	echo $this->Form->input('endtime', array('type' => 'text', 'class' => 'datepicker', 'id' => 'CourseEndtime'));
    ?>
    </fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>

<script>
	$('#CourseStarttime, #CourseEndtime').datepicker(window.datepickerDefaults)
</script>