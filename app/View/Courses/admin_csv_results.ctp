<div class="row">
    <div class="twelvecol last">
 <?php
    echo '&larr; '.  $this->Html->link(__('Takaisin kurssin hallinnointiin'), array(
            'admin' => false,
            'controller' => 'courses',
            'action' => 'view',
            $course_id
        )
    );
    echo "<div>Uusia opiskelijoita luotu järjestelmään $new_students_count kpl.".'</div>';
    echo "<div>Opiskelijoita linkitetty kurssille ". count($students_course) ." kpl.".'</div>';
    
    echo '<h2>Kurssille lisätyt assistentit ('.count($added_users).' kpl)</h2>';
    echo '<table class="data-table">';
    echo '<tr class="table-header"><th>Etunimi</th><th>Sukunimi</th><th>Tunnus</th></tr>';
        if ( !empty($added_users) ) {
            foreach($added_users as $user ) {
                echo '<tr class="table-content">';
                echo '<td>'.$user['User']['first_name'].'</td>';
                echo '<td>'.$user['User']['last_name'].'</td>';
                echo '<td>'.$user['User']['basic_user_account'].'</td>';
                echo '</tr>';

            }
        } else {
            echo '<tr><td colspan="3" class="empty">'.__('Tyhjä').'</td></tr>';
        }
    echo '</table>';

    echo '<h2>Tiedoston tuntemattomat assistentit ('.count($unknown_users).' kpl)</h2>';
    echo '<table class="data-table">';
    echo '<tr class="table-header"><th>'.__('Tunnus').'</th></tr>';
        if ( !empty($unknown_users) ) {
            foreach($unknown_users as $user_bua ) {
                echo '<tr class="table-content">';
                echo '<td>'.$user_bua.'</td>';
                echo '</tr>';

            }
        } else {
            echo '<tr><td colspan="1" class="empty">'.__('Tyhjä').'</td></tr>';
        }
    echo '</table>';

    echo '<h2>Kurssille linkitetyt opiskelijat</h2>';
    echo '<table class="data-table">';
    echo '<tr class="table-header"><th>Etunimi</th><th>Sukunimi</th><th>Opnumero</th></tr>';
        if ( !empty($students_course) ) {
            foreach($students_course as $student ) {
                echo '<tr class="table-content">';
                echo '<td>'.$student['Student']['first_name'].'</td>';
                echo '<td>'.$student['Student']['last_name'].'</td>';
                echo '<td>'.$student['Student']['student_number'].'</td>';
                echo '</tr>';

            }
        } else {
            echo '<tr><td colspan="3" class="empty">'.__('Tyhjä').'</td></tr>';
        }
    echo '</table>';

/*
    echo '<h2>Vastuuryhmiin lisätyt opiskelijat</h2>';
    echo '<table class="data-table">';
    echo '<tr class="table-header"><th>Etunimi</th><th>Sukunimi</th><th>Opnumero</th></tr>';
        if ( !empty($students_with_groups) ) {
            foreach($students_with_groups as $student ) {
                echo '<tr class="table-content">';
                echo '<td>'.$student['Student']['first_name'].'</td>';
                echo '<td>'.$student['Student']['last_name'].'</td>';
                echo '<td>'.$student['Student']['student_number'].'</td>';
                echo '</tr>';

            }
        } else {
            echo '<tr><td colspan="3" class="empty">'.__('Tyhjä').'</td></tr>';
        }
    echo '</table>';
*/

    echo '<h2>Ilman vastuuryhmää jääneet piskelijat</h2>';
    echo '<table class="data-table">';
    echo '<tr class="table-header"><th>Etunimi</th><th>Sukunimi</th><th>Opnumero</th></tr>';
        if ( !empty($students_wo_group) ) {
            foreach($students_wo_group as $student ) {
                echo '<tr class="table-content">';
                echo '<td>'.$student['Student']['first_name'].'</td>';
                echo '<td>'.$student['Student']['last_name'].'</td>';
                echo '<td>'.$student['Student']['student_number'].'</td>';
                echo '</tr>';
           }
        } else {
            echo '<tr><td colspan="3" class="empty">'.__('Tyhjä').'</td></tr>';
        }

    echo '</table>';

    echo '<h2>Tiedoston vanhat opiskelijat</h2>';
    echo '<span>Opiskelijat, jotka löytyivät jo järjestelmästä</span>';
    echo '<table class="data-table">';
    echo '<tr class="table-header"><th>Etunimi</th><th>Sukunimi</th><th>Opnumero</th></tr>';
        if ( !empty($old_students) ) {
            foreach($old_students as $student ) {
                echo '<tr class="table-content">';
                echo '<td>'.$student['Student']['first_name'].'</td>';
                echo '<td>'.$student['Student']['last_name'].'</td>';
                echo '<td>'.$student['Student']['student_number'].'</td>';
                echo '</tr>';
           }
        } else {
            echo '<tr><td colspan="3" class="empty">'.__('Tyhjä').'</td></tr>';
        }

    echo '</table>';

    echo '<h2>'.__('Tuontiloki').'</h2>';
    echo '<div>';
    echo '<textarea rows="'.($log_row_count + 5) .'" cols="100" readonly="readonly">';
    echo $errors_log;
    echo '</textarea>';
    echo '</div>';
 ?>
</div>
</div>
