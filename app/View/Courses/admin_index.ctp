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
     /* DEBUG */
    echo '<pre>';
//foreach($students_list as $student) {
//debug($student);
//}
    //debug($students);
    echo '</pre>';

        echo '        <table class="data-table">';
        echo '        <tr>';
        echo '            <th>'. __('Kurssi') .'</th>';
        echo '            <th>'. __('Alkaa').'</th>';
        echo '            <th>'. __('Päättyy').'</th>';
        echo '            <th>'. __('Tila').'</th>';
        echo '        </tr>';

    foreach($courses as $course) {
        echo '<tr>';
        echo '<td>'.$this->Html->link($course['Course']['name'], 
            array('admin' => false, 'controller' => 'courses', 'action' => 'view', $course['Course']['id'])).'</td>';
        $this->Time->Format('j.n.Y');
        echo '<td>'.$this->Html->link($this->Time->Format('j.n.Y', $course['Course']['starttime']), 
            array('admin' => false, 'controller' => 'courses', 'action' => 'view', $course['Course']['id'])).'</td>';
        echo '<td>'.$this->Html->link($this->Time->Format('j.n.Y', $course['Course']['endtime']), 
            array('admin' => false, 'controller' => 'courses', 'action' => 'view', $course['Course']['id'])).'</td>';
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
    echo $this->Html->link('Lisää uusi kurssi', array('action' => 'admin_add', 'controller' => 'courses'), array('class' => 'button modal-link', 'id' => 'add-course-link'));

    ?>
    </div>
</div>
