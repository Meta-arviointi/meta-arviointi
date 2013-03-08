<script type="text/javascript">
    $(document).ready(function() {
        $('#SelectManyLink').addClass('disabled');
        var n = $('#SelectManyUsers').find('input[type="checkbox"]:checked').length;
        if ( n > 0 ) {
            $('#SelectManyLink').removeClass('disabled');
        }

        $('#SelectManyLink').click(function(event) {
            event.preventDefault();
            if ( $(this).hasClass('disabled')) {
                return false
            } else {
                return true;
            }
        });

        $('#SelectManyUsers').find('input[type="checkbox"]').each(function() {
            var chkbox = this;
            $(chkbox).click(function() {
                var n = $('#SelectManyUsers').find('input[type="checkbox"]:checked').length;
                if ( n == 0 ) {
                    $('#SelectManyLink').addClass('disabled');
                } else {
                    $('#SelectManyLink').removeClass('disabled');
                }
            })
        });

    });
</script>
<div class="row">
    <div class="twelveol">
<?php 
$links = array(
        array('text' => __('Kurssit'), 'url' => array('controller' => 'courses')),
        array('text' => __('Assistentit'), 'url' => array('controller' => 'users'), 'options' => array('class' => 'selected')),
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
    //debug($students);
    echo '</pre>';
    
    echo $this->Form->create(false, array('id' => 'SelectManyUsers',
            'url' => array('controller' => 'actions', 'action' => 'add'),
            'inputDefaults' => array(
                'label' => false,
                'div' => false
            )
        )
    );
    echo $this->Html->link(__('Lisää valitut kurssille'),array(
            'admin' => false,
            'controller' => 'courses',
            'action' => 'add_many_users'
            ),
            array('class' => 'button modal-link',
                'id' => 'SelectManyLink'
            )
    );
?>
    <table class="data-table" id="UsersList">
        <tr>
            <th></th><!-- checkboxes -->
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
            echo '<td>' . $this->Form->checkbox('User.'.$user['User']['id'], array(
                                'value' => $user['User']['id'],
                                'hiddenField' => false
                            )
                        ) . '</td>';
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
                array('admin' => false, 'controller' => 'courses', 'action' => 'view', $userc['id']),
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
    <?php 
        echo $this->Form->end();
        echo $this->Html->link('Lisää uusi assistentti', array(
            'admin' => false,
            'action' => 'add',
            'controller' => 'users'
        ), array('class' => 'button modal-link', 'id' => 'add-admin-link')
    ); ?>
    </div>
</div>
<script>
    $('#TextFilterKeyword').keyup(function() {
        $('#UsersList tr.table-content').hide();
        $('#UsersList tr.table-content:has(td:contains('+$(this).val()+'))').show();
    });
</script>

