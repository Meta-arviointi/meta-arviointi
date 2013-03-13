<script type="text/javascript">
    $(document).ready(function() {
        $('#CreateManyLink').addClass('is-disabled');
        var n = $('#CreateManyActions').find('input[type="checkbox"]:checked').length;
        if ( n > 0 ) {
            $('#CreateManyLink').removeClass('is-disabled');
        }

        $('#CreateManyLink').click(function(event) {
            event.preventDefault();
            return !$(this).hasClass('is-disabled');
        });

        $('#CreateManyActions').find('input[type="checkbox"]').each(function() {
            var chkbox = this;
            $(chkbox).click(function() {
                var n = $('#CreateManyActions').find('input[type="checkbox"]:checked').length;
                if ( n == 0 ) {
                    $('#CreateManyLink').addClass('is-disabled');
                } else {
                    $('#CreateManyLink').removeClass('is-disabled');
                }
            })
        });

    });
</script>
<div class="row">
    <div class="twelveol">
<?php 
$links = array(
        array('text' => __('Opiskelijat'), 'url' => array('controller' => 'students'), 'options' => array('class' => 'selected')),
        array('text' => __('Toimenpiteet'), 'url' => array('controller' => 'actions')),
        array('text' => __('Kurssi'), 'url' => array(
                'controller' => 'courses',
                'action' => 'view',
                $this->Session->read('Course.course_id')
            )
        )
);
echo $this->element('tab-menu', array('links' => $links)); 
?>
    </div>
</div>

<div class="row">
	<div class="twelvecol last">
    <?php 
     /* DEBUG */
    //echo '<pre>';
    //debug($students);
    //echo '</pre>';

    // Selection for assistent groups
    echo $this->Form->create(false, array('id' => 'StudentIndexFilters', 'class' => 'filter-form', 'type' => 'get', 'data-target' => 'StudentsList'));
    echo $this->Form->input('group', array('label' => __('Vastuuryhmä'), 'options' => $user_groups, 'empty' => array('' => 'Kaikki'), 'default' => $this->Session->read('User.group_id')));
    //echo $this->Form->input('actions', array('label' => __('Käsittelemättömiä toimenpiteitä'), 'type' => 'checkbox', 'hiddenField' => '', 'value' => 'true', 'div' => false));
    echo $this->Form->input('quit', array('options' => array('' => __('Kaikki'), 'true' => __('Kyllä'), 'false' => __('Ei')), 'label' => __('Keskeyttänyt'), 'empty' => array('' => __('Kaikki')), 'default' => ''));
    echo $this->Form->input('actions', array('options' => array('' => __('Kaikki'), 'true' => __('Kyllä'), 'false' => __('Ei')), 'label' => __('Toimenpiteitä'), 'empty' => array('' => __('Kaikki')), 'default' => ''));
    echo $this->Form->input('deadline', array('label' => __('Aikaraja umpeutunut'), 'type' => 'checkbox', 'hiddenField' => '', 'value' => 'true'));
    echo $this->Form->input('messages', array('label' => __('Lukemattomia viestejä'), 'type' => 'checkbox', 'hiddenField' => '', 'value' => 'true'));
    echo $this->Form->end();

    echo '<hr class="row">';
    echo $this->Html->link(__('Lisää toimenpide valituille'),array(
            'controller' => 'actions',
            'action' => 'create_many'
            ),
            array('class' => 'modal-link button',
                'id' => 'CreateManyLink',
                'title' => 'Valitse ensin opiskelijat, joille toimenpide lisätään.'
            )
    );

    echo $this->Form->create(false, array('id' => 'CreateManyActions',
            'url' => array('controller' => 'actions', 'action' => 'create_many'),
            'inputDefaults' => array(
                'label' => false,
                'div' => false
            )
        )
    );
    ?>
    <table class="data-table" id="StudentsList" data-source="<?php echo $this->Html->url(array('admin' => false, 'controller' => 'students', 'action' => 'index_ajax')); ?>">
        <thead>
            <tr class="table-header">
                <th></th><!-- checboxes -->
                <th><?php echo __('Sukunimi'); ?></th>
                <th><?php echo __('Etunimi'); ?></th>
                <th><?php echo __('Opiskelijanumero'); ?></th>
                <th><?php echo __('Assistentti'); ?></th> <?php /* TODO sorttuas */ ?>
                <th><?php echo __('Toimenpiteitä'); ?></th>
                <th><?php echo __('Toiminnot'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ( !empty($memberships) ) { // check if not empty
                foreach($memberships as $membership) {
                    $student_group_id = 0;
                    if(!empty($membership['Student']['Group'])) $student_group_id = $membership['Student']['Group'][0]['id'];

                    $has_actions = 'false';
                    if(count($membership['Action']) > 0) {
                        $has_actions = 'true';
                    }

                    $deadline = 'false';
                    foreach($membership['Action'] as $ac) {
                        if(
                            isset($ac['deadline']) &&                   //Deadline is set
                            !empty($ac['deadline']) &&                  //Deadline is not empty
                            strtotime($ac['deadline']) < time() &&      //Deadline has gone
                            empty($ac['handled_id'])                    //Not handled
                        ) {
                            $deadline = 'true';
                        }
                    }

                    $has_unread_messages = 'false';
                    foreach($membership['EmailMessage'] as $em) {
                        if(empty($em['read_time'])) {
                            $has_unread_messages = 'true';
                        }
                    }

                    $has_quit = (empty($membership['CourseMembership']['quit_time'])) ? 'false' : 'true';

                    echo '<tr class="table-content" 
                        data-group="'.$student_group_id.'" 
                        data-actions="' . $has_actions . '"
                        data-deadline="' . $deadline . '"
                        data-messages="' . $has_unread_messages . '"
                        data-quit="' . $has_quit . '">';

                    echo '<td>' . $this->Form->checkbox('CourseMembership.'.$membership['CourseMembership']['id'], array(
                                'value' => $membership['CourseMembership']['id'],
                                'hiddenField' => false
                            )
                        ) . '</td>';
                    
                    echo '<td>'.$this->Html->link($membership['Student']['last_name'],
                        array('controller' => 'course_memberships', 'action' => 'view', $membership['CourseMembership']['id']), array('title' => __('Opiskelijan oma sivu'))).'</td>';

                    echo '<td>'.$this->Html->link($membership['Student']['first_name'],
                        array('controller' => 'course_memberships', 'action' => 'view', $membership['CourseMembership']['id']), array('title' => __('Opiskelijan oma sivu'))).'</td>';
                    echo '<td>'.$membership['Student']['student_number'].'</td>';

                    // If student belongs to a group, print assistant name
                    if ( isset($membership['Student']['Group'][0]['User']) ) {
                        echo '<td>'.$membership['Student']['Group'][0]['User']['name'].'</td>';
                    } else {
                        // If not in any group, leave cell empty
                        echo '<td><em>' . __('(ei määritelty)') .'</em></td>';

                    }

                    echo '<td>'.(isset($membership['Action']) ? count($membership['Action']) : 0).'</td>';
                    echo '<td>'. $this->Html->link($this->Html->image('edit-action-icon.png',
                            array('alt' => __('Lisää toimenpide'), 'title' => __('Lisää toimenpide'))),
                            array(
                                'controller' => 'actions',
                                'action' => 'create',
                                $membership['CourseMembership']['id']
                            ),
                            array(
                                'class' => 'modal-link quick-action',
                                'escape' => false
                            )
                    ); '</td>';
                    
                    echo '</tr>';
                }
            } else { // print "nothing available"
                echo '<tr><td class="empty" colspan="7">' . __('Ei opiskelijoita') . '</td><tr>';
            }
            echo $this->Form->end();
            ?>
        </tbody>
    </table>
    </div>
</div>
<script>
    $('#TextFilterKeyword').keyup(function() {
        $('#StudentsList tr.table-content').hide();
        $('#StudentsList tr.table-content:has(td:contains('+$(this).val()+'))').show();
    });
</script>
