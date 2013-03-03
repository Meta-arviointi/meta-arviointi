<script type="text/javascript">
    $(document).ready(function() {
        $('#CreateManyLink').addClass('disabled');
        var n = $('#CreateManyActions').find('input[type="checkbox"]:checked').length;
        if ( n > 0 ) {
            $('#CreateManyLink').removeClass('disabled');
        }

        $('#CreateManyLink').click(function(event) {
            event.preventDefault();
            if ( $(this).hasClass('disabled')) {
                return false
            } else {
                return true;
            }
        });

        $('#CreateManyActions').find('input[type="checkbox"]').each(function() {
            var chkbox = this;
            $(chkbox).click(function() {
                var n = $('#CreateManyActions').find('input[type="checkbox"]:checked').length;
                if ( n == 0 ) {
                    $('#CreateManyLink').addClass('disabled');
                } else {
                    $('#CreateManyLink').removeClass('disabled');
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
    echo '<pre>';
    //debug($students);
    echo '</pre>';

    // Selection for assistent groups
    echo $this->Form->create(false, array('id' => 'StudentIndexFilters', 'type' => 'get', 'data-target' => 'StudentsList'));
    echo $this->Form->label('group', 'Vastuuryhmä');
    echo $this->Form->select('group', $user_groups, array('div' => false, 'empty' => array('' => 'Kaikki')));
    echo $this->Form->end();

    echo $this->Html->link(__('Lisää toimenpide valituille'),array(
            'controller' => 'actions',
            'action' => 'create_many'
            ),
            array('class' => 'modal-link',
                'id' => 'CreateManyLink'
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
                    echo '<tr class="table-content" data-group="'.$student_group_id.'">';
                    echo '<td>' . $this->Form->checkbox('CourseMembership.'.$membership['CourseMembership']['id'], array(
                                'value' => $membership['CourseMembership']['id'],
                                'hiddenField' => false
                            )
                        ) . '</td>';
                    
                    echo '<td>'.$this->Html->link($membership['Student']['last_name'],
                        array('controller' => 'course_memberships', 'action' => 'view', $membership['CourseMembership']['id'])).'</td>';

                    echo '<td>'.$this->Html->link($membership['Student']['first_name'],
                        array('controller' => 'course_memberships', 'action' => 'view', $membership['CourseMembership']['id'])).'</td>';
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
                                'id' => 'quick-action',
                                'class' => 'modal-link',
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
