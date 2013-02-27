<div class="row">
    <div class="twelvecol last">
 <?php
    echo "<div>Uusia opiskelijoita luotu järjestelmään $new_users_count kpl.".'</div>';
    echo "<div>Opiskelijoita linkitetty kurssille count($students_course) kpl.".'</div>';
    
    echo '<label>Kurssille lisätyt assistentit ('.count($added_users).'kpl)</label>';
    echo '<table>';
    echo '<tr><th>Etunimi</th><th>Sukunimi</th><th>PPT</th></tr>';
        foreach($added_users as $user ) {
            echo '<tr>';
            echo '<td>'.$user['first_name'].'</td>';
            echo '<td>'.$user['last_name'].'</td>';
            echo '<td>'.$user['basic_user_account'].'</td>';
            echo '</tr>';

        }
    echo '</table>';

    echo '<label>Tiedoston tuntemattomat assistentit ('.count($unknown_users).'kpl)</label>';
    echo '<table>';
    echo '<tr><th>PPT</th></tr>';
        foreach($unknown_users as $user_bua ) {
            echo '<tr>';
            echo '<td>'.$user_bua.'</td>';
            echo '</tr>';

        }
    echo '</table>';

    echo '<label>Vastuuryhmiin lisätyt opiskelijat</label>';
    echo '<table>';
    echo '<tr><th>Etunimi</th><th>Sukunimi</th><th>Opnumero</th></tr>';
        foreach($new_students_groups as $student ) {
            echo '<tr>';
            echo '<td>'.$student['first_name'].'</td>';
            echo '<td>'.$student['last_name'].'</td>';
            echo '<td>'.$student['student_number'].'</td>';
            echo '</tr>';

        }
    echo '</table>';


    echo '<label>Ilman vastuuryhmää jääneet piskelijat</label>';
    echo '<table>';
    echo '<tr><td>Etunimi</td><td>Sukunimi</td><td>Opnumero</td></tr>';
        foreach($new_students_wo_groups as $student ) {
            echo '<tr>';
            echo '<td>'.$student['first_name'].'</td>';
            echo '<td>'.$student['last_name'].'</td>';
            echo '<td>'.$student['student_number'].'</td>';
            echo '</tr>';

        }
    echo '</table>';

    echo '<label>Virheloki</label>';
    echo '<textarea rows="100" columns="70">';
    echo $errors_log;
    echo '</textarea>'
 ?>
</div>
</div>