<div class="row">
    <div class="twelveol">
<?php 
$links = array(
        array('text' => __('Kurssit'), 'url' => array('controller' => 'courses'), 'options' => array('class' => 'selected')),
        array('text' => __('Assistentit'), 'url' => array('controller' => 'users')),
        array('text' => __('Opiskelijat'), 'url' => array('controller' => 'students')),
        array('text' => __('Viestipohjat'), 'url' => array('controller' => 'action_email_templates'))
);
echo $this->element('tab-menu', array('links' => $links)); 
?>
    </div>
</div>
<div class="row">
    <div class="twelvecol last">

    <?php

    echo $this->Form->create(false, array('id' => 'CourseAdminIndexFilters', 'class' => 'filter-form', 'type' => 'get', 'data-target' => 'CourseListAdmin'));
    $course_status_list = array(
        '' => __('Kaikki'),
        'coming' => __('Tulossa'),
        'current' => __('Käynnissä'),
        'finished' => __('Päättynyt')
    );
    echo $this->Form->input('status', array('label' => __('Tila'), 'options' => $course_status_list));

    $course_years = array();
    foreach($courses as $c) {
        $y = date('Y', strtotime($c['Course']['starttime']));
        if(!isset($course_years[$y])) {
            $course_years[$y] = $y;
        }
    }
    echo $this->Form->input('started', array('label' => __('Vuosi'), 'options' => $course_years, 'empty' => array('' => 'Kaikki')));
    echo $this->Form->end();


    ?>

    <hr class="row">
    <?php 
     /* DEBUG */
    echo '<pre>';
//foreach($students_list as $student) {
//debug($student);
//}
    //debug($students);
    echo '</pre>';

        echo '        <table class="data-table" id="CourseListAdmin">';
        echo '        <thead><tr>';
        echo '            <th>'. __('Kurssi') .'</th>';
        echo '            <th>'. __('Alkaa').'</th>';
        echo '            <th>'. __('Päättyy').'</th>';
        echo '            <th>'. __('Tila').'</th>';
        if ( !empty($is_admin) ) { echo '        <th>'. __('Toiminnot') .'</th>';}
        echo '        </tr></thead>';
        echo '<tbody>';



    foreach($courses as $course) {
        if (strtotime($course['Course']['starttime']) > time()) {
            $status = __('Tulossa');
            $status_code = 'coming';
        }
        if (strtotime($course['Course']['endtime']) < time()) {
            $status = __('Päättynyt');
            $status_code = 'finished';
        }
        if (strtotime($course['Course']['starttime']) < time() && strtotime($course['Course']['endtime']) > time()) {
            $status = __('Käynnissä');
            $status_code = 'current';
        }

        echo '<tr ';
        echo 'data-status="' . $status_code . '" ';
        echo 'data-started="' . date('Y', strtotime($course['Course']['starttime'])) . '"';
        echo '>';
        echo '<td>'.$this->Html->link($course['Course']['name'], 
            array('admin' => false, 'controller' => 'courses', 'action' => 'view', $course['Course']['id'])).'</td>';
        $this->Time->Format('j.n.Y');
        echo '<td>'.$this->Html->link($this->Time->Format('j.n.Y', $course['Course']['starttime']), 
            array('admin' => false, 'controller' => 'courses', 'action' => 'view', $course['Course']['id'])).'</td>';
        echo '<td>'.$this->Html->link($this->Time->Format('j.n.Y', $course['Course']['endtime']), 
            array('admin' => false, 'controller' => 'courses', 'action' => 'view', $course['Course']['id'])).'</td>';
        echo '<td>' . $status . '</td>';
        if ( !empty($is_admin) ) {
                echo '<td class="row-tools">'. $this->Html->link($this->Html->image('delete-action-icon.png',
                            array('alt' => __('Poista kurssi'),
                                'title' => __('Poista kurssi')
                                )
                            ),
                            array(
                                'admin' => true,
                                'controller' => 'courses',
                                'action' => 'delete',
                                $course['Course']['id']
                            ),
                            array(
                                'escape' => false,
                            ),
                            __('Haluatko varmasti poistaa kurssin? Kaikki kurssiin liittyvä tieto poistetaan (harjoitukset, toimenpiteet)!')
                ). '</td>';
            }
    }
    echo '</tbody></table>';
    echo $this->Html->link('Lisää uusi kurssi', array('action' => 'admin_add', 'controller' => 'courses'), array('class' => 'button modal-link', 'id' => 'add-course-link'));

    ?>
    </div>
</div>
