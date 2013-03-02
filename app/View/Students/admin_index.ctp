<div class="row">
    <div class="twelveol">
<?php 
$links = array(
        array('text' => __('Kurssit'), 'url' => array('controller' => 'courses')),
        array('text' => __('Assistentit'), 'url' => array('controller' => 'users')),
        array('text' => __('Opiskelijat'), 'url' => array('controller' => 'students'), 'options' => array('class' => 'selected'))
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
    //debug($students[1]);
    echo '</pre>';
?>
    <table class="data-table" id="StudentsList">
        <tr>
            <th><?php echo __('Sukunimi'); ?></th>
            <th><?php echo __('Etunimi'); ?></th>
            <th><?php echo __('Opiskelijanumero'); ?></th>
            <th><?php echo __('Sähköposti'); ?></th>
            <th><?php echo __('Kurssit'); ?></th>
            <?php if ( $admin ) { echo '<th>'. __('Toiminnot') . '</th>'; }?>
        </tr>
        <?php
        foreach( $students as $student ) {
            echo '<tr>';
            echo '<td>'. $this->Html->link(__($student['Student']['last_name']),
                array(
                    'admin' => false,
                    'controller' => 'students',
                    'action' => 'view',
                    $student['Student']['id']
                )
            ).'</td>';
            echo '<td>'. $this->Html->link(__($student['Student']['first_name']),
                array(
                    'admin' => false,
                    'controller' => 'students',
                    'action' => 'view',
                    $student['Student']['id']
                )
            ).'</td>';
            echo '<td>'. $student['Student']['student_number'].'</td>';
            echo '<td>'. $student['Student']['email'].'</td>';
            echo '<td>';
            if ( count($student['CourseMembership']) > 1 ) {
                foreach( $student['CourseMembership'] as $cm ) {
                    $course_name = $cm['Course']['name'];
                    echo $this->Html->link($course_name, array(
                            'controller' => 'course_memberships',
                            'action' => 'view',
                            $cm['id']
                        )
                    );
                    echo '<br/>';
                }    
            } else { // only one CourseMembership
                $cm = $student['CourseMembership'][0];
                $course_name = $cm['Course']['name'];
                echo $this->Html->link($course_name, array(
                        'controller' => 'course_memberships',
                        'action' => 'view',
                        $cm['id']
                    )
                );
            }
            
            echo '</td>';
            echo '</tr>';
        }
         
        ?>
    </table>
    <?php echo $this->Html->link(__('Lisää uusi opiskelija'), 
        array('action' => 'admin_add', 'controller' => 'students'), array('class' => 'modal-link')); ?>
    </div>
</div>
<script>
    $('#TextFilterKeyword').keyup(function() {
        $('#StudentsList tr.table-content').hide();
        $('#StudentsList tr.table-content:has(td:contains('+$(this).val()+'))').show();
    });
</script>

