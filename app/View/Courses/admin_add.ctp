<div class="courses form">
<?php 
    echo $this->Form->create('Course'); ?>
    <fieldset>
        <legend><?php echo __('Uusi kurssi'); ?></legend>
    <?php
    // Include User.id so User is automatically linked to new course
    echo $this->Form->input('User.id', array('type' => 'hidden', 'value' => $this->Session->read('Auth.User.id')));
	echo $this->Form->input('name', array('label' => 'Kurssin nimi'));
	echo $this->Form->input('starttime', array('label' => 'Kurssi alkaa', 'type' => 'text', 'class' => 'datetimepicker', 'id' => 'CourseStarttime'));
	echo $this->Form->input('endtime', array('label' => 'Kurssi loppuu', 'type' => 'text', 'class' => 'datetimepicker', 'id' => 'CourseEndtime'));
    ?>
    </fieldset>
<?php echo $this->Form->end(__('Lisää kurssi')); ?>
</div>

<script>
	$('#CourseStarttime, #CourseEndtime').datetimepicker(window.datepickerDefaults)
</script>
