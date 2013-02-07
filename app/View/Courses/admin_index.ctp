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
    <?php 
     /* DEBUG */
    echo '<pre>';
//foreach($students_list as $student) {
//debug($student);
//}
    //debug($students);
    echo '</pre>';

    if (isset($single_course)) {
	    foreach($courses as $course) {
            echo $course['Course']['name'];
            echo ' ';
            echo $this->Time->Format('d-m-Y', $course['Course']['starttime']) .'-'. $this->Time->Format('d-m-Y', $course['Course']['endtime']);
            echo '<br/>';
            echo 'Opiskelijoiden lukumäärä: '. $scount;
            echo ' ';
            echo 'Assistenttien lukumäärä: '. $acount;
            echo '<br/>';
            echo 'Keskeyttäneitä: '. $quitcount;
            echo ' ';
            echo 'Annettuja toimenpiteitä: '. $actioncount;

            echo '<br/>';
            echo '[lisää tiedot csv nappi 8-p]';
            echo '[luo uusi assistentti järjestelmään nappi]';
            echo '[lisää opiskelija järjestelmään nappi]';

echo '<br/>';
echo '<br/>';
            echo 'Harjoitukset';
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


        foreach($exercise_list as $exercise) {
//            echo '<td>'.$this->Html->link($exercise['exercise_string'], 
//                array('controller' => 'courses', 'action' => 'index', $course['Course']['id'])).'</td>';
            echo '<tr>';
            echo '<td>'. $exercise['exercise_string'] .'</td>';
            echo '<td>'. $this->Time->Format('d-m-Y', $exercise['starttime']) .'</td>';
            echo '<td>'. $this->Time->Format('d-m-Y, h:m', $exercise['endtime']) .'</td>';
            echo '<td>'. $this->Time->Format('d-m-Y', $exercise['review_starttime']) .'</td>';
            echo '<td>'. $this->Time->Format('d-m-Y, h:m', $exercise['review_endtime']) .'</td>';
        }

            echo '</table>';
            echo '[lisää harjoitusnappi]';
//            echo $this->Html->link('Lisää harjoitus', array('action' => 'admin_add', 'controller' => 'courses'), array('class' => 'modal-link', 'id' => 'add-course-link'));

echo '<br/>';
echo '<br/>';
            echo $this->Html->link('Assistentit', array('action' => 'admin_index', 'controller' => 'users'));
            echo '<table class="data-table">';
            echo '    <tr>';
            echo '        <th>'. __('Nimi') .'</th>';
            echo '        <th>'. __('Sähköposti') .'</th>';
            echo '        <th>'. __('Ryhmän koko') .'</th>';
            echo '    </tr>';

        foreach($users_list as $assari) {
            if ($assari['is_admin'] != 'false') { 
                echo '<tr>';
                echo '<td>'. $this->Html->link($assari['first_name'] .' '. $assari['last_name'], array('controller' => 'users', 'action' => 'view', $assari['id'])). '</td>';
                echo '<td>'. $assari['email'] .'</td>';
                if (isset($groups[$assari['id']])) {
                    echo '<td>'. $groups[$assari['id']] .'</td>';
                } else {
                    echo '<td>0</td>';
                }
            }
        }
            echo '</table>';
            echo '[lisää assistentti]';
//            echo $this->Html->link('Lisää harjoitus', array('action' => 'admin_add', 'controller' => 'courses'), array('class' => 'modal-link', 'id' => 'add-course-link'));

echo '<br/>';
echo '<br/>';
            echo 'Opiskelijat';
            echo '<table class="data-table">';
            echo '    <tr>';
            echo '        <th>'. __('Etunimi') .'</th>';
            echo '        <th>'. __('Sukunimi') .'</th>';
            echo '        <th>'. __('Sähköposti') .'</th>';
            echo '        <th>'. __('Vastuuassistentti') .'</th>';
            echo '    </tr>';

        foreach($students_list as $student) {
//            echo '<td>'.$this->Html->link($exercise['exercise_string'], 
//                array('controller' => 'courses', 'action' => 'index', $course['Course']['id'])).'</td>';
            echo '<tr>';
            echo '<td>'. $student['Student']['first_name'] .'</td>';
            echo '<td>'. $student['Student']['last_name'] .'</td>';
            echo '<td>'. $student['Student']['email'] .'</td>';
            foreach($student['Group'] as $stg) {
                echo '<td>'. $stg['User']['name'] .'</td>';
            }
        }

            echo '</table>';
            echo '[lisää opiskelija]';
//            echo $this->Html->link('Lisää harjoitus', array('action' => 'admin_add', 'controller' => 'courses'), array('class' => 'modal-link', 'id' => 'add-course-link'));

        }
    } else {
        echo $this->Form->create(false, array('id' => 'CoursesList', 'type' => 'get'));
        echo $this->Form->label('group', 'Kurssit');
        echo $this->Form->select('course_id', $course_groups, array('empty' => array(0 => 'Kaikki kurssit'), 'default' => 0));
        echo $this->Form->end();

        echo '        <table class="data-table">';
        echo '        <tr>';
        echo '            <th>'. __('Kurssi') .'</th>';
        echo '            <th>'. __('Alku pvm').'</th>';
        echo '            <th>'. __('Loppu pvm').'</th>';
        echo '            <th>'. __('tila').'</th>';
        echo '        </tr>';

        foreach($courses as $course) {
            echo '<tr>';
            echo '<td>'.$this->Html->link($course['Course']['name'], 
                array('controller' => 'courses', 'action' => 'index', $course['Course']['id'])).'</td>';
            $this->Time->Format('d-m-Y');
            echo '<td>'.$this->Html->link($this->Time->Format('d-m-Y', $course['Course']['starttime']), 
                array('controller' => 'courses', 'action' => 'index', $course['Course']['id'])).'</td>';
            echo '<td>'.$this->Html->link($this->Time->Format('d-m-Y', $course['Course']['endtime']), 
                array('controller' => 'courses', 'action' => 'index', $course['Course']['id'])).'</td>';
            if (strtotime($course['Course']['starttime']) > time()) {
                echo '<td>Tulossa</td>';
            }
	    if (strtotime($course['Course']['endtime']) < time()) {
                echo '<td>Päättynyt</td>';
            }
            if (strtotime($course['Course']['starttime']) < time() && strtotime($course['Course']['endtime']) > time()) {
                echo '<td>Käynnissä</td>';
            }
        }
        echo '    </table>';
        echo $this->Html->link('Lisää uusi kurssi', array('action' => 'admin_add', 'controller' => 'courses'), array('class' => 'modal-link', 'id' => 'add-course-link'));
    }
    ?>
    </div>
</div>
