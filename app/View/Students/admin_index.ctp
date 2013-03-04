<script type="text/javascript">
    $(document).ready(function() {
        $('#CreateManyLink').addClass('disabled');
        var n = $('#CreateManyCourseMemberships').find('input[type="checkbox"]:checked').length;
        if ( n > 0 ) {
            $('#CreateManyLink').removeClass('disabled');
        }

        $('#CreateManyLink').click(function(event) {
            event.preventDefault();
            if ( $(this).hasClass('disabled')) {
                return false
            } else {
                return true;
            }
        });

        $('#CreateManyCourseMemberships').find('input[type="checkbox"]').each(function() {
            var chkbox = this;
            $(chkbox).click(function() {
                var n = $('#CreateManyCourseMemberships').find('input[type="checkbox"]:checked').length;
                if ( n == 0 ) {
                    $('#CreateManyLink').addClass('disabled');
                } else {
                    $('#CreateManyLink').removeClass('disabled');
                }
            })
        });

    });
</script>
<div class="row">
    <div class="twelveol">
<?php 
$links = array(
        array('text' => __('Kurssit'), 'url' => array('controller' => 'courses')),
        array('text' => __('Assistentit'), 'url' => array('controller' => 'users')),
        array('text' => __('Opiskelijat'), 'url' => array('controller' => 'students'), 'options' => array('class' => 'selected')),
        array('text' => __('Viestipohjat'), 'url' => array('controller' => 'action_email_templates'))
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
    echo $this->Form->create(false, array('id' => 'CreateManyCourseMemberships',
            'url' => array('controller' => 'actions', 'action' => 'add'),
            'inputDefaults' => array(
                'label' => false,
                'div' => false
            )
        )
    );
    echo $this->Html->link(__('Lisää valitut kurssille'),array(
            'controller' => 'course_memberships',
            'action' => 'create_many'
            ),
            array('class' => 'modal-link',
                'id' => 'CreateManyLink'
            )
    );
?>
    <table class="data-table" id="StudentsList">
        <tr>
            <th></th><!--checkboxes -->
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
            echo '<td>' . $this->Form->checkbox('Student.'.$student['Student']['id'], array(
                                'value' => $student['Student']['id'],
                                'hiddenField' => false
                            )
                        ) . '</td>';
            echo '<td>'. $this->Html->link(__($student['Student']['last_name']),
                array(
                    'admin' => false,
                    'controller' => 'students',
                    'action' => 'edit',
                    $student['Student']['id']
                ),
                array('class' => 'modal-link')
            ).'</td>';
            echo '<td>'. $this->Html->link(__($student['Student']['first_name']),
                array(
                    'admin' => false,
                    'controller' => 'students',
                    'action' => 'edit',
                    $student['Student']['id']
                ),
                array('class' => 'modal-link')
            ).'</td>';
            echo '<td>'. $student['Student']['student_number'].'</td>';
            echo '<td>'. $student['Student']['email'].'</td>';
            echo '<td>';
            if ( count($student['CourseMembership']) > 1 ) {
                foreach( $student['CourseMembership'] as $cm ) {
                    $course_name = $cm['Course']['name'];
                    echo $this->Html->link($course_name, array(
                            'admin' => false,
                            'controller' => 'course_memberships',
                            'action' => 'view',
                            $cm['id']
                        )
                    );
                    echo '<br/>';
                }
            } else { // only one OR zero CourseMembership
                if ( !empty($student['CourseMembership']) ) {
                    $cm = $student['CourseMembership'][0];
                    $course_name = $cm['Course']['name'];
                    echo $this->Html->link($course_name, array(
                            'admin' => false,
                            'controller' => 'course_memberships',
                            'action' => 'view',
                            $cm['id']
                        )
                    );
                } else {
                    echo __('Ei kursseilla');
                }

            }
            echo '</td>';
            if ( !empty($admin) ) {
            }
            echo '</tr>';
        }
         
        ?>
    </table>
    <?php echo $this->Html->link(__('Lisää uusi opiskelija'), 
        array('admin' => false, 'action' => 'add', 'controller' => 'students'), array('class' => 'modal-link')); ?>
    </div>
</div>
<script>
    $('#TextFilterKeyword').keyup(function() {
        $('#StudentsList tr.table-content').hide();
        $('#StudentsList tr.table-content:has(td:contains('+$(this).val()+'))').show();
    });
</script>

