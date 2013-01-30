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
    //debug($students);
    echo '</pre>';

    // Selection for assistent groups
    echo $this->Form->create(false, array('id' => 'UsersList', 'type' => 'get'));
    echo $this->Form->label('group', 'Kurssi');
    echo $this->Form->select('course_id', $course_groups, array('empty' => array(0 => 'Kaikki kurssit'), 'default' => 0));
    echo $this->Form->input('filter', array('div' => false, 'label' => __('Suodata'), 'id' => 'TextFilterKeyword'));
    echo $this->Form->end();
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
            foreach($user['Course'] as $foob) {
                echo $this->Html->link($foob['name'].'<br />',
                array('controller' => 'user', 'action' => 'view', $foob['id']),
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

