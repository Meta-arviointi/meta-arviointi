<script type="text/javascript">
    $(document).ready(function() {
        $('.SelectManyLink').addClass('disabled');
        var n = $('#SelectStudents').find('input[type="checkbox"]:checked').length;
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

        $('#SelectStudents').find('input[type="checkbox"]').each(function() {
            var chkbox = this;
            $(chkbox).click(function() {
                var n = $('#SelectStudents').find('input[type="checkbox"]:checked').length;
                if ( n == 0 ) {
                    $('.SelectManyLink').addClass('disabled');
                } else {
                    $('.SelectManyLink').removeClass('disabled');
                }
            })
        });

        $('.post-button button').click(function(e) {
            e.preventDefault;
            $(this).closest('div').find("form").append(($('#SelectStudents').find('input[type="checkbox"]:checked')).attr('type','hidden'));
            $(this).closest('div').find("form").submit();
        });

    });
</script>
<div class="row">
    <div class="twelveol">
<?php 
$links = array(
        array('text' => __('Opiskelijat'), 'url' => array('controller' => 'students')),
        array('text' => __('Toimenpiteet'), 'url' => array('controller' => 'actions')),
        array('text' => __('Kurssi'), 'url' => array(
                'controller' => 'courses',
                'action' => 'view',
                $this->Session->read('Course.course_id')
            ), 'options' => array('class' => 'selected')
        )
);
echo $this->element('tab-menu', array('links' => $links)); 
?>
    </div>
</div>
<div class="row">
<?php
    echo '<div class="ninecol">';
    echo '<h1>'.$course['name'].'</h1>';
    echo '<p class="course-dates">' .__('Kurssi alkaa').': '. $this->Time->Format('j.n.Y', $course['starttime']) . '</p>';
    echo '<p class="course-dates">' .__('Kurssi päättyy').': '. $this->Time->Format('j.n.Y', $course['endtime']) . '</p>';
    echo '</div>';
    echo '<div class="threecol last course-tools">';
    echo $this->Html->link(__('Muokkaa'), array(
            'action' => 'edit',
            $course['id']
        ),
        array(
            'class' => 'button modal-link'
        )
    );
    echo '</div>';
    echo '</div>';

    echo '<hr class="row">';

    echo '<div class="row">';
    echo '<div class="twelvecol last">';

    if ( $edit_exercises ) {
        echo $this->Form->create('Exercise', array(
                'url' => array(
                    'controller' => 'exercises',
                    'action' => 'edit_many'
                ),
                'inputDefaults' => array(
                    'label' => false,
                    'div' => false
                )
            )
        );
        echo '<div class="section-header">';
        echo '<h2>Harjoitukset</h2>';

        echo $this->Form->submit(__('Tallenna harjoitukset'), array('div' => false));
        echo $this->Html->link(__('Peruuta'), array($course_id), array('class' => 'button'));
        echo $this->Html->link('Lisää harjoitus', array('action' => 'add', 'controller' => 'exercises'), array('class' => 'button modal-link', 'id' => 'add-exercise-link'));

        echo '</div>';

        echo '<table class="data-table">';
        echo '    <tr>';
        echo '        <th>'. __('Harjoituskerta') .'</th>';
        echo '        <th colspan="2">'. __('Tehtävä') .'</th>';
        echo '        <th colspan="2">'. __('Arviointi') .'</th>';
        echo '    </tr>';
        echo '    <tr>';
        echo '        <th></th>';
        echo '        <th>'. __('Aukeaa') .'</th>';
        echo '        <th>'. __('Palautus') .'</th>';
        echo '        <th>'. __('Aukeaa') .'</th>';
        echo '        <th>'. __('Palautus') .'</th>';
        echo '    </tr>';

        $i = 0;
        foreach($exercises as $exercise) {

            $id = $exercise['id'];
            $number = $exercise['exercise_number'];
            $name = $exercise['exercise_name'];
            $stime = date('d.m.Y H:i', strtotime($exercise['starttime']));
            $etime = date('d.m.Y H:i', strtotime($exercise['endtime']));
            $rstime = date('d.m.Y H:i', strtotime($exercise['review_starttime']));
            $retime = date('d.m.Y H:i', strtotime($exercise['review_endtime']));
            echo '<tr>';
            echo $this->Form->input('Exercise.'.$i.'.id', array('type' => 'hidden', 'default' => $id));
            echo $this->Form->input('Exercise.'.$i.'.course_id', array('type' => 'hidden', 'default' => $course_id));
            echo '<td>'.$this->Form->input('Exercise.'.$i.'.exercise_number', array(
                'default' => $number,
                'size' => 2
                )
            );
            echo $this->Form->input('Exercise.'.$i.'.exercise_name', array(
                'default' => $name
                )
            ).'</td>';
            echo '<td>'.$this->Form->input('Exercise.'.$i.'.starttime', array(
                'default' => $stime,
                'type' => 'text',
                'class' => 'datetimepicker'
                )
            ).'</td>';
            echo '<td>'.$this->Form->input('Exercise.'.$i.'.endtime', array(
                'default' => $etime,
                'type' => 'text',
                'class' => 'datetimepicker'
                )
            ).'</td>';
            echo '<td>'.$this->Form->input('Exercise.'.$i.'.review_starttime', array(
                'default' => $rstime,
                'type' => 'text',
                'class' => 'datetimepicker'
                )
            ).'</td>';
            echo '<td>'.$this->Form->input('Exercise.'.$i.'.review_endtime', array(
                'default' => $retime,
                'type' => 'text',
                'class' => 'datetimepicker'
                )
            ).'</td>';
            echo '</tr>';
            $i++;
        }

        echo '</table>';
        echo $this->Form->end();

    } else {
        echo '<div class="section-header">';
        echo '<h2>Harjoitukset</h2>';

        if (!empty($exercises) ) {
            echo $this->Html->link(__('Muokkaa harjoituksia'), array(
                    $course_id,
                    '?' => array('edit' => 'exercises')
                ),
                array('class' => 'button')
            );
        }

        echo $this->Html->link('Lisää harjoitus', array('action' => 'add', 'controller' => 'exercises'), array('class' => 'button modal-link', 'id' => 'add-exercise-link'));

        echo '</div>';

        echo '<table class="data-table">';
        echo '    <tr>';
        echo '        <th>'. __('Harjoituskerta') .'</th>';
        echo '        <th colspan="2">'. __('Tehtävä') .'</th>';
        echo '        <th colspan="2">'. __('Arviointi') .'</th>';
        echo '    </tr>';
        echo '    <tr>';
        echo '        <th></th>';
        echo '        <th>'. __('Aukeaa') .'</th>';
        echo '        <th>'. __('Palautus') .'</th>';
        echo '        <th>'. __('Aukeaa') .'</th>';
        echo '        <th>'. __('Palautus') .'</th>';
        echo '    </tr>';

        if ( !empty($exercises) ) {


            foreach($exercises as $exercise) {
                echo '<tr>';
                echo '<td>'. $exercise['exercise_string'] .'</td>';
                echo '<td>'. $this->Time->Format('j.n.Y G:i', $exercise['starttime']) .'</td>';
                echo '<td>'. $this->Time->Format('j.n.Y G:i', $exercise['endtime']) .'</td>';
                echo '<td>'. $this->Time->Format('j.n.Y G:i', $exercise['review_starttime']) .'</td>';
                echo '<td>'. $this->Time->Format('j.n.Y G:i', $exercise['review_endtime']) .'</td>';
            }    
        } else {
             echo '<tr><td class="empty" colspan="5">' . __('Ei harjoituksia') . '</td><tr>';
        }    

        echo '</table>';
    }
    
    echo '<hr class="row">';
    echo '<div class="section-header">';
    echo '<h2>'.__('Assistentit').'</h2>';

    echo $this->Html->link('Lisää/poista kurssin assistentteja', array('action' => 'add_users', 'controller' => 'courses'), array('class' => 'button modal-link', 'id' => 'add-user-link'));
   
    echo '</div>';

    echo '<table class="data-table">';
    echo '    <tr>';
    echo '        <th>'. __('Nimi') .'</th>';
    echo '        <th>'. __('Tunnus') .'</th>';
    echo '        <th>'. __('Sähköposti') .'</th>';
    echo '        <th>'. __('Ryhmän koko') .'</th>';
    echo '    </tr>';
    if ( !empty($users) ) {
        foreach($users as $assari) {
            echo '<tr>';
            echo '<td>'. $this->Html->link($assari['first_name'] .' '. $assari['last_name'], array('controller' => 'users', 'action' => 'view', 'admin' => false, $assari['id'])). '</td>';
            echo '<td>' . $assari['basic_user_account'] . '</td>';
            echo '<td>'. $assari['email'] .'</td>';
            $count =  isset($group_count[$assari['id']]) ? $group_count[$assari['id']] : 0;
            echo '<td>'. $count .'</td>';
            echo '</tr>';
            
        }    
    } else {
        echo '<tr><td class="empty" colspan="4">' . __('Ei assistentteja') . '</td><tr>';
    }
    
    echo '</table>';

    echo '<hr class="row">';   
    echo '<div class="section-header">'; 
    echo '<h2>'.__('Opiskelijat').'</h2>';
    echo $this->Html->link('Lisää uusi opiskelija kurssille', array(
                'action' => 'add',
                'controller' => 'course_memberships'
            ),
            array(
                'class' => 'button modal-link',
                'id' => 'add-students-link',
                'div'
            )
    );
    echo '</div>';


    // Selection for assistent groups
    echo $this->Form->create(false, array('id' => 'StudentIndexFilters', 'class' => 'filter-form', 'type' => 'get', 'data-target' => 'StudentsList'));
    echo $this->Form->input('group', array('label' => __('Vastuuryhmä'), 'options' => $user_groups, 'empty' => array('' => 'Kaikki'), 'default' => $this->Session->read('User.group_id')));
    //echo $this->Form->input('actions', array('label' => __('Käsittelemättömiä toimenpiteitä'), 'type' => 'checkbox', 'hiddenField' => '', 'value' => 'true', 'div' => false));
    echo $this->Form->input('quit', array('options' => array('' => __('Kaikki'), 'true' => __('Kyllä'), 'false' => __('Ei')), 'label' => __('Keskeyttänyt'), 'empty' => array('' => __('Kaikki')), 'default' => ''));
    echo $this->Form->input('actions', array('options' => array('' => __('Kaikki'), 'true' => __('Kyllä'), 'false' => __('Ei')), 'label' => __('Toimenpiteitä'), 'empty' => array('' => __('Kaikki')), 'default' => ''));
    echo $this->Form->input('deadline', array('label' => __('Aikaraja umpeutunut'), 'type' => 'checkbox', 'hiddenField' => '', 'value' => 'true'));
    echo $this->Form->input('messages', array('label' => __('Lukemattomia viestejä'), 'type' => 'checkbox', 'hiddenField' => '', 'value' => 'true'));
    echo $this->Form->end();

    echo '<hr class="row">';
    echo '<div class="table-tools">';
    echo $this->Html->link(__('Liitä valitut opiskelijat vastuuryhmään'),array(
            'controller' => 'course_memberships',
            'action' => 'set_groups'
            ),
            array('class' => 'SelectManyLink button modal-link')
    );
    echo '<div class="post-button" id="DeleteManyCourseMembershipsForm">';
    echo $this->Form->postButton(__('Poista opiskelijat kurssilta'),
            array(
                'controller' => 'course_memberships',
                'action' => 'delete_many'
            ),
            array(
                'class' => 'SelectManyLink button',
                'id' => 'DeleteManyCourseMemberships'
            )
    );
    echo '</div>';

    echo '<div class="post-button" id="SetQuitForm">';
    echo $this->Form->postButton(__('Merkitse keskeyttäneeksi'),
            array(
                'controller' => 'course_memberships',
                'action' => 'set_quits'
            ),
            array(
                'class' => 'SelectManyLink button',
                'id' => 'SetQuitsButton'
            )
    );
    echo '</div>';
    echo '</div>';

    echo $this->Form->create(false, array('id' => 'SelectStudents',
            'url' => array('controller' => 'actions', 'action' => 'add'),
            'inputDefaults' => array(
                'label' => false,
                'div' => false
            )
        )
    );
    
    echo '<table class="data-table" id="StudentsList">';
    echo '<thead>';
    echo '    <tr class="table-header">';
    echo '        <th></th>';
    echo '        <th>'. __('Etunimi') .'</th>';
    echo '        <th>'. __('Sukunimi') .'</th>';
    echo '        <th>'. __('Opiskelijanumero') .'</th>';
    echo '        <th>'. __('Sähköposti') .'</th>';
    echo '        <th>'. __('Vastuuassistentti') .'</th>';
    if ( !empty($is_admin) ) { echo '        <th>'. __('Toiminnot') .'</th>';}
    echo '    </tr>';
    echo '</thead>';

    if ( !empty($course_memberships) ) {
        foreach($course_memberships as $cm) {
            
            $student_group_id = 0;
            if(!empty($cm['Group'])) $student_group_id = $cm['Group']['id'];

            $has_actions = 'false';
            if(count($cm['Action']) > 0) {
                $has_actions = 'true';
            }

            $deadline = 'false';
            foreach($cm['Action'] as $ac) {
                if(
                    isset($ac['deadline']) &&                   //Deadline is set
                    !empty($ac['deadline']) &&                  //Deadline is not empty
                    strtotime($ac['deadline']) < time() &&      //Deadline has gone
                    empty($ac['handled_id'])                    //Not handled
                ) {
                    $deadline = 'true';
                }
            }

            $has_unread_messages = 'false';
            foreach($cm['EmailMessage'] as $em) {
                if(empty($em['read_time'])) {
                    $has_unread_messages = 'true';
                }
            }

            $has_quit = (empty($cm['quit_time'])) ? 'false' : 'true';

            echo '<tr class="table-content" 
                data-group="'.$student_group_id.'" 
                data-actions="' . $has_actions . '"
                data-deadline="' . $deadline . '"
                data-messages="' . $has_unread_messages . '"
                data-quit="' . $has_quit . '">';
            // NOTE in below checkbox $cm['id'] is CourseMembership.ID, not Student.ID!!!
            // format is array( 'Student' => array(CM.ID => Student.id))
            echo '<td>' . $this->Form->checkbox('Student.'.$cm['id'], array(
                                'value' => $cm['Student']['id'],
                                'hiddenField' => false
                            )
                        ) . '</td>';
            echo '<td>'. $this->Html->link(__($cm['Student']['first_name']),
                array(
                    'admin' => false,
                    'controller' => 'course_memberships',
                    'action' => 'view',
                    $cm['id'] // CourseMembership ID
                )
            ).'</td>';
            echo '<td>'. $this->Html->link(__($cm['Student']['last_name']),
                array(
                    'admin' => false,
                    'controller' => 'course_memberships',
                    'action' => 'view',
                    $cm['id'] // CourseMembership ID
                )
            ).'</td>';
            echo '<td>'. $cm['Student']['student_number'] .'</td>';
            echo '<td>'. $cm['Student']['email'] .'</td>';
            $assistant = isset($cm['Group']['user_id']) ? 
                $users_list[$cm['Group']['user_id']] : '';
            echo '<td>'. $assistant .'</td>';
            if ( !empty($is_admin) ) {
                echo '<td class="row-tools">'. $this->Html->link($this->Html->image('edit-action-icon.png',
                            array('alt' => __('Muokkaa opiskelijaa'),
                                'title' => __('Muokkaa opiskelijaa')
                                )
                            ),
                            array(
                                'controller' => 'students',
                                'action' => 'edit',
                                $cm['Student']['id']
                            ),
                            array(
                                'escape' => false,
                                'class' => 'modal-link'
                            )
                );
                echo $this->Html->link($this->Html->image('delete-action-icon.png',
                            array('alt' => __('Poista opiskelija kurssilta'),
                                'title' => __('Poista opiskelija kurssilta')
                                )
                            ),
                            array(
                                'controller' => 'course_memberships',
                                'action' => 'delete',
                                $cm['id']
                            ),
                            array(
                                'escape' => false
                            ),
                            __('Haluatko varmasti poistaa opiskelijan kurssilta?')
                ). '</td>';
            }
        }    
    } else {
        echo '<tr><td class="empty" colspan="6">' . __('Ei opiskelijoita') . '</td><tr>';
    }
    echo '</table>';
    echo $this->Form->end();
    echo '<div id="csv-upload">';
    echo '<h2>' . __('Lisää opiskelijat CSV-tiedostosta') . '</h2>';
    echo '<div>Rivit muodossa: sukunimi;etunimi;opnumero;email;assari_tunnus</div>';
    echo $this->Form->create('Student', array(
            'type' => 'file',
            'url' => array(
                'controller' => 'students',
                'action' => 'import'
            )
        )
    );
    echo $this->Form->file('tmp_file');
    echo $this->Form->end('Lähetä');
    echo '</div>';

?>
</div>
</div>