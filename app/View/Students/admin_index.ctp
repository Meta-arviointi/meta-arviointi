<script type="text/javascript">
    $(document).ready(function() {
        $('.SelectManyLink').addClass('disabled');
        var n = $('#SelectManyCourseMemberships').find('input[type="checkbox"]:checked').length;
        if ( n > 0 ) {
            $('.SelectManyLink').removeClass('disabled');
        }

        $('.SelectManyLink').click(function(event) {
            event.preventDefault();
            if ( $(this).hasClass('disabled')) {
                return false
            } else {
                return true;
            }
        });

        $('#SelectManyCourseMemberships').find('input[type="checkbox"]').each(function() {
            var chkbox = this;
            $(chkbox).click(function() {
                var n = $('#SelectManyCourseMemberships').find('input[type="checkbox"]:checked').length;
                if ( n == 0 ) {
                    $('.SelectManyLink').addClass('disabled');
                } else {
                    $('.SelectManyLink').removeClass('disabled');
                }
            })
        });

        $('#DeleteManyStudents').click(function(e) {
            e.preventDefault;
            if ( confirm('Haluatko varmasti poistaa opiskelijat lopullisesti järjestelmästä? Kaikki opiskelijaan liittyvät tiedot (esim. toimenpiteet) poistetaan!') ) {
                $('#DeleteManyStudentsForm').find("form").append(($('#SelectManyCourseMemberships').find('input[type="checkbox"]:checked')).attr('type','hidden'));
                $('#DeleteManyStudentsForm').find("form").submit();   
            }
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

    echo $this->Form->create(false, array('id' => 'StudentAdminIndexFilters', 'class' => 'filter-form', 'type' => 'get', 'data-target' => 'StudentsListAdmin'));
    echo $this->Form->input('course', array('label' => __('Kurssi'), 'options' => $courses, 'empty' => array('' => 'Kaikki')));
    echo $this->Form->end();


    ?>

    <hr class="row">
    <?php 
    
    echo '<div class="table-tools">';
    echo $this->Html->link(__('Lisää valitut kurssille'),array(
            'controller' => 'course_memberships',
            'action' => 'create_many'
            ),
            array('class' => 'SelectManyLink button modal-link',
                'id' => 'CreateManyCourseMemberships'
            )
    );

    echo '<div class="post-button" id="DeleteManyStudentsForm">';
    echo $this->Form->postButton(__('Poista opiskelijat järjestelmästä'),
            array(
                'controller' => 'students',
                'action' => 'delete_many'
            ),
            array(
                'class' => 'SelectManyLink button',
                'id' => 'DeleteManyStudents'
            )
            
    );
    echo '</div>';
    echo '</div>';
    echo $this->Form->create(false, array('id' => 'SelectManyCourseMemberships',
            'url' => array('controller' => 'actions', 'action' => 'add'),
            'inputDefaults' => array(
                'label' => false,
                'div' => false
            )
        )
    );
?>
    <table class="data-table" id="StudentsListAdmin">
        <thead>
            <tr>
                <th></th><!--checkboxes -->
                <th><?php echo __('Sukunimi'); ?></th>
                <th><?php echo __('Etunimi'); ?></th>
                <th><?php echo __('Opiskelijanumero'); ?></th>
                <th><?php echo __('Sähköposti'); ?></th>
                <th><?php echo __('Kurssit'); ?></th>
                <?php if ( $admin ) { echo '<th>'. __('Toiminnot') . '</th>'; }?>
            </tr>
        </thead>
        <tbody>
        <?php
        foreach( $students as $student ) {
            $student_courses = array();
            foreach($student['CourseMembership'] as $cm) {
                $student_courses[] = $cm['course_id'];
            }

            echo '<tr ';
            echo 'data-course="' . implode(",", $student_courses) . '"';
            echo '>';
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
                    $course_name = $all_courses[$cm['course_id']];
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
                    $course_name = $all_courses[$cm['course_id']];
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
        </tbody>
    </table>
    <?php echo $this->Html->link(__('Lisää uusi opiskelija'), 
        array('admin' => false, 'action' => 'add', 'controller' => 'students'), 
            array('class' => ' button modal-link')
        ); ?>
    </div>
</div>
<script>
    $('#TextFilterKeyword').keyup(function() {
        $('#StudentsList tr.table-content').hide();
        $('#StudentsList tr.table-content:has(td:contains('+$(this).val()+'))').show();
    });
</script>

