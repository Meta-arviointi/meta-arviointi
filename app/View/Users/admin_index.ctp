<div class="row">
    <div class="twelveol">
<?php 
$links = array(
        array('text' => __('Kurssit'), 'url' => array('controller' => 'courses')),
        array('text' => __('Assistentit'), 'url' => array('controller' => 'users'), 'options' => array('class' => 'selected')),
        array('text' => __('Opiskelijat'), 'url' => array('controller' => 'students'))
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
            <th><?php echo __('Tunnus'); ?></th>
            <th><?php echo __('Sähköposti'); ?></th>
            <th><?php echo __('Kurssit'); ?></th>
            <?php if ( $admin ) { echo '<th>'. __('Toiminnot') . '</th>'; }?>
        </tr>
        <?php
        foreach($users as $user) {
            echo '<tr>';
            echo '<td>'.$this->Html->link($user['User']['last_name'], 
                array('admin' => false,
                    'controller' => 'users',
                    'action' => 'view',
                    $user['User']['id']
                    )
                ).'</td>';
            echo '<td>'.$this->Html->link($user['User']['first_name'], 
                array('admin' => false,
                     'controller' => 'users',
                     'action' => 'view',
                     $user['User']['id']
                     )
                ).'</td>';
            echo '<td>'.$user_logins[$user['User']['id']].'</td>';

            echo '<td>'.$this->Html->link($user['User']['email'], 
                array('admin' => false, 
                    'controller' => 'users', 
                    'action' => 'view', 
                    $user['User']['id']
                    )
                ).'</td>';

            echo '<td>';
            foreach($user['Course'] as $userc) {
                echo $this->Html->link($userc['name'].'<br />',
                array('controller' => 'courses', 'action' => 'admin_index', $userc['id']),
                array('escape' => false));
            }
            echo '</td>';

            if ( $admin ) {
                echo '<td>'. $this->Html->link($this->Html->image('edit-action-icon.png',
                        array('alt' => __('Muokkaa'), 'title' => __('Muokkaa'))),
                        array(
                            'admin' => false,
                            'controller' => 'users',
                            'action' => 'edit',
                            $user['User']['id']
                        ),
                        array(
                            'id' => 'edit-user-modal',
                            'class' => 'modal-link',
                            'escape' => false
                        )
                ); '</td>';    
            }
            
        }
        ?>
    </table>
    <?php echo $this->Html->link('Lisää uusi assistentti', array(
            'admin' => false,
            'action' => 'add',
            'controller' => 'users'
        ), array('class' => 'modal-link', 'id' => 'add-admin-link')
    ); ?>
    </div>
</div>
<script>
    $('#TextFilterKeyword').keyup(function() {
        $('#UsersList tr.table-content').hide();
        $('#UsersList tr.table-content:has(td:contains('+$(this).val()+'))').show();
    });
</script>

