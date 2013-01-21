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
	<?php echo $this->Html->link('Lisää uusi kurssi', array('action' => 'add', 'controller' => 'students'), array('class' => 'button', 'id' => 'add-course-link')); ?>
	<?php echo $this->Html->link('Lisää assistentti', array('action' => 'add', 'controller' => 'students'), array('class' => 'button', 'id' => 'add-user-link')); ?>
	<?php echo $this->Html->link('Lisää opiskelija', array('action' => 'add', 'controller' => 'students'), array('class' => 'button', 'id' => 'add-student-link')); ?>
	<?php echo $this->Html->link('Lisää opiskelijat CSV-tiedostosta', array('action' => 'add', 'controller' => 'students'), array('class' => 'button', 'id' => 'add-student_csv-link')); ?>

    <?php 
     /* DEBUG */
    echo '<pre>';
    //debug($students);
    echo '</pre>';

    // Selection for assistent groups
    echo $this->Form->create(false, array('id' => 'course_group', 'type' => 'get'));
    echo $this->Form->label('group', 'Kurssi');
    echo $this->Form->select('course_id', array('empty' => array(0 => 'Kaikki kurssit'), 'default' => 0));
    echo $this->Form->end();
    ?>

    <table class="data-table">
        <tr>
            <th><?php echo __('Kurssi'); ?></th>
            <th><?php echo __('Alku pvm'); ?></th>
            <th><?php echo __('Loppu pvm'); ?></th>
            <th><?php echo __('tila'); ?></th>
        </tr>
        <!--<tr> paginaatio poistettu käytöstä (toistaiseksi)
            <th><?php echo $this->Paginator->sort('last_name', 'Sukunimi'); ?></th>
            <th><?php echo $this->Paginator->sort('first_name', 'Etunimi'); ?></th>
            <th><?php echo $this->Paginator->sort('student_number', 'Opiskelijanumero'); ?></th>
            <th>Assistentti</th>
            <th>Toimenpiteitä</th>
        </tr> -->
        <?php
        foreach($students as $student) {
            echo '<tr>';
            echo '<td>'.$this->Html->link($student['Student']['last_name'], 
                array('controller' => 'course_memberships', 'action' => 'view', $student['CourseMembership'][0]['id'])).'</td>';
            echo '<td>'.$this->Html->link($student['Student']['first_name'], 
                array('controller' => 'course_memberships', 'action' => 'view', $student['CourseMembership'][0]['id'])).'</td>';
            echo '<td>'.$student['Student']['student_number'].'</td>';

            /* If student belongs to a group, print assistant name */
            if ( isset($student['Group'][0]['User']) ) {
                echo '<td>'.$student['Group'][0]['User']['name'].'</td>';
            } else {
                /* If not in any group, leave cell empty */
                echo '<td></td>';

            }
            // Jos ei tietokantataulut ole vielä kunnossa kommentoi yo. if ja laita vain "Asseri Assistentti":
            //echo '<td>Asseri Assistentti</td>';
            echo '<td>'.count($student['Action']).'</td>';
            echo '</tr>';
        }
        ?>
    </table>
    </div>
</div>
