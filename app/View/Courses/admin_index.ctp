<div class="row">
    <div class="twelvecol last">
        <?php
            echo __('Opiskelijat');
            echo ' | ';
            echo $this->Html->link(__('Toimenpiteet'), array('action' => 'index_actions'));
        ?>
        <hr>
    </div>
</div>
<div class="row">
    <div class="twelvecol last">
    <?php 
     /* DEBUG */
    echo '<pre>';
    //debug($students);
    echo '</pre>';

    // Selection for assistent groups
    echo $this->Form->create(false, array('id' => 'CoursesList', 'type' => 'get'));
    echo $this->Form->label('group', 'Kurssit');
    echo $this->Form->select('course_id', $course_groups, array('empty' => array(0 => 'Kaikki kurssit'), 'default' => 0));
    echo $this->Form->end();
    ?>

    <table class="data-table">
        <tr>
            <th><?php echo __('Kurssi'); ?></th>
            <th><?php echo __('Alku pvm'); ?></th>
            <th><?php echo __('Loppu pvm'); ?></th>
            <th><?php echo __('tila'); ?></th>
        </tr>
        <?php
        foreach($courses as $course) {
            echo '<tr>';
            echo '<td>'.$this->Html->link($course['Course']['name'], 
                array('controller' => 'course', 'action' => 'view', $course['Course']['id'])).'</td>';
            $this->Time->Format('d-m-Y');
            echo '<td>'.$this->Html->link($this->Time->Format('d-m-Y', $course['Course']['starttime']), 
                array('controller' => 'course', 'action' => 'view', $course['Course']['id'])).'</td>';
            echo '<td>'.$this->Html->link($this->Time->Format('d-m-Y', $course['Course']['endtime']), 
                array('controller' => 'course', 'action' => 'view', $course['Course']['id'])).'</td>';
            if (strtotime($course['Course']['starttime']) > time()) {
                echo '<td>Tulossa</td>';
            }
	    if (strtotime($course['Course']['endtime']) < time()) {
                echo '<td>Päättynyt</td>';
            }
            if (strtotime($course['Course']['starttime']) < time() && strtotime($course['Course']['endtime']) > time()) {
                echo '<td>Käynnissä</td>';
            }

        }
        ?>
    </table>
    <?php echo $this->Html->link('Lisää uusi kurssi', array('action' => 'admin_add', 'controller' => 'courses'), array('class' => 'modal-link', 'id' => 'add-course-link')); ?>
    </div>
</div>
