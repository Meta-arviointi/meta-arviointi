<div class="row">
    <div class="twelveol">
<?php 
$links = array(
        array('text' => __('Kurssit'), 'url' => array('controller' => 'courses')),
        array('text' => __('Assistentit'), 'url' => array('controller' => 'users'), 'options' => array('class' => 'selected'))
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
    //debug($students);
    echo '</pre>';
?>
    <table class="data-table" id="UsersList">
        <tr>
            <th><?php echo __('Sukunimi'); ?></th>
            <th><?php echo __('Etunimi'); ?></th>
            <th><?php echo __('Kurssi(t)'); ?></th>
            <th><?php echo __('Sähköposti'); ?></th>
        </tr>
        <?php
        foreach($users as $user) {
// lisää controlleriin data admin booleanista, tänne if tarkistus onko admin - pois listasta jos on?
            echo '<tr>';
            echo '<td>'.$this->Html->link($user['User']['last_name'], 
                array('controller' => 'user', 'action' => 'view', $user['User']['id'])).'</td>';
            echo '<td>'.$this->Html->link($user['User']['first_name'], 
                array('controller' => 'user', 'action' => 'view', $user['User']['id'])).'</td>';
	    echo '<td>';
            foreach($user['Course'] as $userc) {
                echo $this->Html->link($userc['name'].'<br />',
                array('controller' => 'courses', 'action' => 'admin_index', $userc['id']),
                array('escape' => false));
            }
            echo '</td>';
            echo '<td>'.$this->Html->link($user['User']['email'], 
                array('controller' => 'user', 'action' => 'view', $user['User']['id'])).'</td>';
        }
        ?>
    </table>
    <?php echo $this->Html->link('Lisää uusi assistentti', array('action' => 'admin_add', 'controller' => 'users'), array('class' => 'modal-link', 'id' => 'add-admin-link')); ?>
    </div>
</div>
<script>
    $('#TextFilterKeyword').keyup(function() {
        $('#UsersList tr.table-content').hide();
        $('#UsersList tr.table-content:has(td:contains('+$(this).val()+'))').show();
    });
</script>

