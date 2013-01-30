<div class="courses form">
<?php 
    echo $this->Form->create('Course'); ?>
    <fieldset>
        <legend><?php echo __('Uusi kurssi'); ?></legend>
    <?php
	echo $this->Form->input('name', array('label' => 'Kurssin nimi'));
	echo $this->Form->input('starttime', array('label' => 'Alkamispäivä', 'type' => 'text', 'class' => 'datepicker', 'id' => 'CourseStarttime'));
	echo $this->Form->input('endtime', array('label' => 'Loppumispäivä', 'type' => 'text', 'class' => 'datepicker', 'id' => 'CourseEndtime'));
    ?>
    </fieldset>
<?php echo $this->Form->end(__('Lisää kurssi')); ?>
</div>

<script>
	$('#CourseStarttime, #CourseEndtime').datepicker(window.datepickerDefaults)
</script>
