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
	<?php echo $this->Html->link('Lisää uusi opiskelija', array('action' => 'add', 'controller' => 'students'), array('class' => 'button', 'id' => 'add-student-link')); ?>

    <?php 
     /* DEBUG */
    echo '<pre>';
    //debug($students);
    echo '</pre>';

    // Selection for assistent groups
    echo $this->Form->create(false, array('id' => 'StudentIndexFilters', 'type' => 'get'));
    echo $this->Form->label('group', 'Vastuuryhmä');
    echo $this->Form->select('group_id', $user_groups, array('div' => false, 'empty' => array(0 => 'Kaikki'), 'default' => $group_id));
    echo $this->Form->input('filter', array('div' => false, 'label' => __('Suodata'), 'id' => 'TextFilterKeyword'));
    echo $this->Form->end();
    ?>

    <table class="data-table" id="StudentsList">
        <tr class="table-header">
            <th><?php echo __('Sukunimi'); ?></th>
            <th><?php echo __('Etunimi'); ?></th>
            <th><?php echo __('Opiskelijanumero'); ?></th>
            <th><?php echo __('Assistentti'); ?></th> <!-- TODO: Assistentti Paginator-sorttuas -->
            <th><?php echo __('Toimenpiteitä'); ?></th>
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
            echo '<tr class="table-content">';
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
<script>
    $('#TextFilterKeyword').keyup(function() {
        $('#StudentsList tr.table-content').hide();
        $('#StudentsList tr.table-content:has(td:contains('+$(this).val()+'))').show();
    });
</script>
