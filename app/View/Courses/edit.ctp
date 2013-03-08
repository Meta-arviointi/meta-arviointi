<div class="row">
    <div class="ninecol">
        <h1><?php echo __('Muokkaa kurssin tietoja') ?></h1>
        <?php
            echo $this->Form->create('Course');
            echo $this->Form->input('id', array('hidden' => true));
            
            echo $this->Form->input('name', array('label' => 'Kurssin nimi'));
            echo $this->Form->input('starttime', array(
                    'label' => 'Alkamispäivä',
                    'type' => 'text',
                    'class' => 'datetimepicker',
                    'id' => 'CourseStarttime'
                )
            );
            echo $this->Form->input('endtime', array(
                    'label' => 'Loppumispäivä',
                    'type' => 'text',
                    'class' => 'datetimepicker',
                    'id' => 'CourseEndtime'
                )
            );
            echo $this->Form->end(__('Tallenna'));
        ?>
    </div>
</div> 
<script>
    $('#CourseStarttime, #CourseEndtime').datetimepicker(window.datepickerDefaults)
</script>
