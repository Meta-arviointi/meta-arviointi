<div class="row">
    <div class="twelveol">
<?php 
$links = array(
        array('text' => __('Opiskelijat'), 'url' => array('controller' => 'students')),
        array('text' => __('Toimenpiteet'), 'url' => array('controller' => 'actions'), 'options' => array('class' => 'selected'))
);
echo $this->element('tab-menu', array('links' => $links)); 
?>
    </div>
</div>
<div class="row">
    <div class="twelvecol last">
    <?php 
     /* DEBUG */
    echo '<pre>';
//  debug($actions);
//  debug($course_memberships);
    echo '</pre>';

    // Selection for assistant group
    echo $this->Form->create(false, array('id' => 'StudentIndexFilters', 'type' => 'get'));
    echo $this->Form->label('group', 'Vastuuryhmä');
    echo $this->Form->select('group_id', $user_groups, array('div' => false, 'empty' => array(0 => 'Kaikki'), 'default' => $group_id));
    echo $this->Form->input('filter', array('div' => false, 'label' => __('Suodata'), 'id' => 'TextFilterKeyword'));
    echo $this->Form->end();
    
    ?>

    <table class="data-table" id="ActionsList">
        <tr class="table-header">
            <th><?php echo __('Tyyppi'); ?></th>
            <th><?php echo __('Harjoitus'); ?></th>
            <th><?php echo __('Opiskelija'); ?></th>
            <th><?php echo __('Lisännyt'); ?></th>
            <th><?php echo __('Käsitelty'); ?></th>
        </tr>
        <?php /* <tr> paginaatio poistettu käytöstä (toistaiseksi)
            <th><?php echo $this->Paginator->sort('last_name', 'Sukunimi'); ?></th>
            <th><?php echo $this->Paginator->sort('first_name', 'Etunimi'); ?></th>
            <th><?php echo $this->Paginator->sort('student_number', 'Opiskelijanumero'); ?></th>
            <th>Assistentti</th>
            <th>Toimenpiteitä</th>
        </tr> --> */?>
        <?php
        if ( !empty($actions) ) { // check if not empty
            foreach($actions as $action) {
                $action_title = null;
                $student = $action['CourseMembership']['Student'];
                // If Action belongs to several Exercises
                if ( count($action['Exercise']) > 1 ) {
                    foreach($action['Exercise'] as $exercise) {
                        $action_title = $action_title . 'H' . $exercise['exercise_number'] . ', ';
                    }
                    // Remove last two characters (',' and ' ')
                    $action_title = substr($action_title, 0, -2);

                } else { // only one exercise
                        $action_title = 'H' . $action['Exercise'][0]['exercise_number'];
                }
                echo '<tr class="table-content">';
                echo '<td>' . $this->Html->link($action['ActionType']['name'], 
                    array(
                        'controller' => 'course_memberships',
                        'action' => 'view',
                        $action['CourseMembership']['id'],
                        '?' => array('scroll_to' => 'action'.$action['Action']['id'])
                        )
                    ) . '</td>';

                echo '<td>' . $action_title . '</td>';
                echo '<td>' . $this->Html->link($student['last_name'] . ', ' . $student['first_name'], 
                    array('controller' => 'course_memberships', 'action' => 'view', $action['CourseMembership']['id'])) . '</td>';
                echo '<td>' . $action['User']['name'] . '</td>';
                $handler = isset($users[$action['Action']['handled_id']]) ? $users[$action['Action']['handled_id']] : null;
                if ( !empty($handler) ) {
                    echo '<td class="handled">' . $handler . '</td>';
                } else {
                    echo '<td class="empty-cell">' . __('Ei käsitelty') . '</td>';
                }
                echo '</tr>';
            }
        } else { // print "nothing available"
            echo '<tr><td class="empty" colspan="5">' . __('Ei toimenpiteitä') . '</td><tr>';
        }

        ?>
    </table>
    </div>
</div>
