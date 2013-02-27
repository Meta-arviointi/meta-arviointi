 <?php
    if ( !empty($students) ) { // check if not empty
        foreach($students as $student) {
            echo '<tr class="table-content">';
            echo '<td>' . $this->Form->checkbox('CourseMembership.'.$student['CourseMembership'][0]['id'], array(
                        'value' => $student['CourseMembership'][0]['id'],
                        'hiddenField' => false
                    )
                ) . '</td>';
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
                echo '<td><em>' . __('(ei määritelty)') .'</em></td>';

            }
            echo '<td>'.(isset($student['Action']) ? count($student['Action']) : 0).'</td>';
            echo '<td>'. $this->Html->link($this->Html->image('edit-action-icon.png',
                    array('alt' => __('Lisää toimenpide'), 'title' => __('Lisää toimenpide'))),
                    array(
                        'controller' => 'actions',
                        'action' => 'create',
                        $student['CourseMembership'][0]['id']
                    ),
                    array(
                        'id' => 'quick-action',
                        'class' => 'modal-link',
                        'escape' => false
                    )
            ); '</td>';
            echo '</tr>';
        }
    } else { // print "nothing available"
        echo '<tr><td class="empty" colspan="7">' . __('Ei opiskelijoita') . '</td><tr>';
    }

?>