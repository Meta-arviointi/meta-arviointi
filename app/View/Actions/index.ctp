<script type="text/javascript">
    $(document).ready(function() {
        $('#ActionsList input[type="checkbox"]').change(function() {
            var checkedInputs = $('#ActionsList input[type="checkbox"]:checked')
            $('#SendActionEmailsLink').toggleClass('is-disabled', checkedInputs.length < 1)
            var params = {
                actions: []
            }
            checkedInputs.each(function() {
                params.actions.push($(this).val());
            });
            $('#SendActionEmailsLink').querystring(params);
        });

        $('#SendActionEmailsLink').click(function() {
            if($(this).hasClass('is-disabled')) { return false; }
        });
    });
</script>

<div class="row">
    <div class="twelveol">
<?php 
$links = array(
        array('text' => __('Opiskelijat'), 'url' => array('controller' => 'students')),
        array('text' => __('Toimenpiteet'), 'url' => array('controller' => 'actions'), 'options' => array('class' => 'selected')),
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
//    echo '<pre>';
//  debug($actions);
//  debug($course_memberships);
//    echo '</pre>';

    // Selection for assistant group
    echo $this->Form->create(false, array('id' => 'ActionIndexFilters', 'class' => 'filter-form', 'type' => 'get', 'data-target' => 'ActionsList'));
    echo $this->Form->input('group', array('options' => $user_groups, 'label' => __('Vastuuryhmä'), 'empty' => array('' => __('Kaikki')), 'default' => $this->Session->read('User.group_id')));

    echo $this->Form->input('user', array('options' => $users, 'label' => __('Lisännyt'), 'empty' => array('' => __('Kaikki')), 'default' => ''));

   echo $this->Form->input('type', array('options' => $action_types, 'label' => __('Tyyppi'), 'empty' => array('' => __('Kaikki')), 'default' => ''));

   echo $this->Form->input('exercise', array('options' => $exercises, 'label' => __('Harjoitus'), 'empty' => array('' => __('Kaikki')), 'default' => ''));

    echo $this->Form->input('resolved', array('options' => array('' => __('Kaikki'), 'true' => __('Kyllä'), 'false' => __('Ei')), 'label' => __('Käsitelty'), 'empty' => array('' => __('Kaikki')), 'default' => 'false'));

    echo $this->Form->end();
    

    echo $this->Form->create(false, array('id' => 'SelectActions'));
    ?>

    <hr class="row">

    <?php
        echo $this->Html->link(__('Lähetä sähköposti valituille'), array('action' => 'send_action_emails'), array('id' => 'SendActionEmailsLink', 'class' => 'modal-link button is-disabled', 'title' => __('Valitse ensin opiskelijat, joille sähköposti lähetetään.')));
    ?>
    <input class="filter-keyword" data-target="ActionsList">

    <table class="data-table" id="ActionsList">
        <thead>
            <tr class="table-header">
                <th></th>
                <th><?php echo __('Tyyppi'); ?></th>
                <th><?php echo __('Harjoitus'); ?></th>
                <th><?php echo __('Opiskelija'); ?></th>
                <th><?php echo __('Lisännyt'); ?></th>
                <th><?php echo __('Käsitelty'); ?></th>
            </tr>
        </thead>
        <tbody>
        <?php /* <tr> paginaatio poistettu käytöstä (toistaiseksi)
            <th><?php echo $this->Paginator->sort('last_name', 'Sukunimi'); ?></th>
            <th><?php echo $this->Paginator->sort('first_name', 'Etunimi'); ?></th>
            <th><?php echo $this->Paginator->sort('student_number', 'Opiskelijanumero'); ?></th>
            <th>Assistentti</th>
            <th>Toimenpiteitä</th>
        </tr> --> */?>
        <?php
        if ( !empty($actions) ) { // check if not empty
            foreach($actions as $action) {
                $action_title = null;
                $student = $action['CourseMembership']['Student'];
                // If Action belongs to several Exercises
                if ( count($action['Exercise']) > 1 ) {
                    foreach($action['Exercise'] as $exercise) {
                        $action_title = $action_title . 'H' . $exercise['exercise_number'] . ', ';
                    }
                    // Remove last two characters (',' and ' ')
                    $action_title = substr($action_title, 0, -2);

                } else { // only one exercise
                        $action_title = 'H' . $action['Exercise'][0]['exercise_number'];
                }
                $student_group_id = '';
                if(!empty($action['CourseMembership']['Group']['id'])) {
                    $student_group_id = $action['CourseMembership']['Group']['id'];
                }
                $ex_ids = array();
                foreach($action['Exercise'] as $ex) {
                    $ex_ids[] = $ex['id'];
                }
                echo '<tr class="table-content"
                    data-group="'.$student_group_id.'"
                    data-type="'.$action['Action']['action_type_id'].'"
                    data-user="'.$action['Action']['user_id'].'"
                    data-exercise="'.implode(',', $ex_ids).'"
                    data-resolved="'. ((!empty($action['Action']['handled_id'])) ? 'true' : 'false') .'"
                    >';


                echo '<td>' . $this->Form->checkbox('Action.', array(
                            'value' => $action['Action']['id'],
                            'hiddenField' => false
                        )
                    ) . '</td>';
                

                echo '<td>' . $this->Html->link($action['ActionType']['name'], 
                    array(
                        'controller' => 'course_memberships',
                        'action' => 'view',
                        $action['CourseMembership']['id'],
                        '?' => array('scroll_to' => 'action'.$action['Action']['id'])
                        ), array('title' => __('Opiskelijan oma sivu'))
                    ) . '</td>';

                echo '<td>' . $action_title . '</td>';
                echo '<td>' . $this->Html->link($student['last_name'] . ', ' . $student['first_name'], 
                    array('controller' => 'course_memberships', 'action' => 'view', $action['CourseMembership']['id']), array('title' => __('Opiskelijan oma sivu'))) . '</td>';
                echo '<td>' . $action['User']['name'] . '</td>';
                $handler = isset($users[$action['Action']['handled_id']]) ? $users[$action['Action']['handled_id']] : null;
                if ( !empty($handler) ) {
                    echo '<td class="handled">' . $handler . '</td>';
                } else {
                    echo '<td class="empty-cell">' . __('Ei käsitelty') . '</td>';
                }
                echo '</tr>';
            }
        } else { // print "nothing available"
            echo '<tr><td class="empty" colspan="6">' . __('Ei toimenpiteitä') . '</td><tr>';
        }

        ?>
        </tbody>
    </table>
    <?php
    echo $this->Form->end();
    ?>
    </div>
</div>
