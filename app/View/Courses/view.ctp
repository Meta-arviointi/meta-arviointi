<script type="text/javascript">
    $(document).ready(function() {
        $('#CreateManyLink').addClass('disabled');
        var n = $('#EditStudentGroups').find('input[type="checkbox"]:checked').length;
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

        $('#EditStudentGroups').find('input[type="checkbox"]').each(function() {
            var chkbox = this;
            $(chkbox).click(function() {
                var n = $('#EditStudentGroups').find('input[type="checkbox"]:checked').length;
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
    <div class="twelvecol last">
<?php

    echo '<h1>'.$course['name'].'</h1>';
    echo '<p class="course-dates">' .__('Kurssi alkaa').': '. $this->Time->Format('j.n.Y', $course['starttime']) . '</p>';
    echo '<p class="course-dates">' .__('Kurssi päättyy').': '. $this->Time->Format('j.n.Y', $course['endtime']) . '</p>';

    echo '<h2>Harjoitukset</h2>';
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
    echo $this->Html->link('Lisää harjoitus', array('action' => 'add', 'controller' => 'exercises'), array('class' => 'modal-link', 'id' => 'add-exercise-link'));

    echo '<br/>';
    echo '<br/>';
    echo '<h2>'.__('Assistentit').'</h2>';
    echo '<table class="data-table">';
    echo '    <tr>';
    echo '        <th>'. __('Nimi') .'</th>';
    echo '        <th>'. __('Peruspalvelutunnus') .'</th>';
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

    echo $this->Html->link('Lisää/poista kurssin assistentteja', array('action' => 'add_users', 'controller' => 'courses'), array('class' => 'modal-link', 'id' => 'add-user-link'));

    echo '<br/>';
    echo '<br/>';

    
        
    echo '<h2>'.__('Opiskelijat').'</h2>';

    echo $this->Form->create(false, array('id' => 'EditStudentGroups',
            'url' => array('controller' => 'actions', 'action' => 'add'),
            'inputDefaults' => array(
                'label' => false,
                'div' => false
            )
        )
    );
    echo $this->Html->link(__('Liitä valitut opiskelijat vastuuryhmään'),array(
            'controller' => 'students',
            'action' => 'set_groups'
            ),
            array('class' => 'modal-link',
                'id' => 'CreateManyLink'
            )
    );
    echo '<table class="data-table">';
    echo '    <tr>';
    echo '        <th></th>';
    echo '        <th>'. __('Etunimi') .'</th>';
    echo '        <th>'. __('Sukunimi') .'</th>';
    echo '        <th>'. __('Opiskelijanumero') .'</th>';
    echo '        <th>'. __('Sähköposti') .'</th>';
    echo '        <th>'. __('Vastuuassistentti') .'</th>';
    if ( !empty($is_admin) ) { echo '        <th>'. __('Toiminnot') .'</th>';}
    echo '    </tr>';

    if ( !empty($course_memberships) ) {
        foreach($course_memberships as $student) {
            echo '<tr>';
            echo '<td>' . $this->Form->checkbox('Student.'.$student['Student']['id'], array(
                                'value' => $student['Student']['id'],
                                'hiddenField' => false
                            )
                        ) . '</td>';
            echo '<td>'. $student['Student']['first_name'] .'</td>';
            echo '<td>'. $student['Student']['last_name'] .'</td>';
            echo '<td>'. $student['Student']['student_number'] .'</td>';
            echo '<td>'. $student['Student']['email'] .'</td>';
            $assistant = isset($student['Student']['Group'][0]['user_id']) ? 
                $users_list[$student['Student']['Group'][0]['user_id']] : '';
            echo '<td>'. $assistant .'</td>';
            if ( !empty($is_admin) ) {
                echo '<td>'. $this->Html->link($this->Html->image('delete-action-icon.png',
                            array('alt' => __('Poista opiskelija kurssilta'),
                                'title' => __('Poista opiskelija kurssilta')
                                )
                            ),
                            array(
                                'controller' => 'course_memberships',
                                'action' => 'delete',
                                $student['id']
                            ),
                            array(
                                'escape' => false
                            ),
                            __('Haluatko varmasti poistaa opiskelijan kurssilta?')
                ); '</td>';
            }
        }    
    } else {
        echo '<tr><td class="empty" colspan="5">' . __('Ei opiskelijoita') . '</td><tr>';
    }
    echo '</table>';

    echo $this->Html->link('Lisää uusi opiskelija kurssille', array(
                'action' => 'add',
                'controller' => 'course_memberships'
            ),
            array(
                'class' => 'modal-link',
                'id' => 'add-students-link',
                'div'
            )
    );
    echo '<div id="csv-upload">';
    echo $this->Form->label(__('Lisää opiskelijat CSV-tiedostosta'));
    echo '<div>Rivit muodossa: sukunimi;etunimi;opnumero;email;assari_ppt</div>';
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