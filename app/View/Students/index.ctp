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
        array('text' => __('Toimenpiteet'), 'url' => array('controller' => 'actions'))
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
    echo $this->Form->select('group_id', $user_groups, array('div' => false, 'empty' => array(0 => 'Kaikki'), 'default' => $group_id));
    echo $this->Form->input('filter', array('div' => false, 'label' => __('Suodata'), 'id' => 'TextFilterKeyword'));
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
            if ( !empty($students) ) { // check if not empty
                foreach($students as $student) {
                    echo '<tr class="table-content">';
                    echo '<td>' . $this->Form->checkbox('CourseMembership.'.$student['CourseMembership'][0]['id'], array(
                                'value' => $student['CourseMembership'][0]['id'],
                                'hiddenField' => false
                            )
                        ) . '</td>';
                    echo '<td>'.$this->Html->link($student['Student']['last_name'],
                        array('controller' => 'course_memberships', 'action' => 'view', $student['CourseMembership'][0]['id'])).'</td>';
                    echo '<td>'.$this->Html->link($student['Student']['first_name'],
                        array('controller' => 'course_memberships', 'action' => 'view', $student['CourseMembership'][0]['id'])).'</td>';
                    echo '<td>'.$student['Student']['student_number'].'</td>';

                    /* If student belongs to a group, print assistant name */
                    if ( isset($student['Group'][0]['User']) ) {
                        echo '<td>'.$student['Group'][0]['User']['name'].'</td>';
                    } else {
                        /* If not in any group, leave cell empty */
                        echo '<td><em>' . __('(ei määritelty)') .'</em></td>';

                    }
                    echo '<td>'.(isset($student['Action']) ? count($student['Action']) : 0).'</td>';
                    echo '<td>'. $this->Html->link($this->Html->image('edit-action-icon.png',
                            array('alt' => __('Lisää toimenpide'), 'title' => __('Lisää toimenpide'))),
                            array(
                                'controller' => 'actions',
                                'action' => 'create',
                                $student['CourseMembership'][0]['id']
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
                echo '<tr><td class="empty" colspan="6">' . __('Ei opiskelijoita') . '</td><tr>';
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
