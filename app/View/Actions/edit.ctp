<script type="text/javascript">

        $('select#action-type-switcher').change(function() {
            var url = <?php echo '\''. $this->Html->url(
                    array(
                        'controller' => 'actions',
                        'action' => 'edit',
                        $action_data['Action']['id']
                    )
                ) . '\';'."\n" ?>
            url = url + "/" + $(this).val();
            $.ajax({
                url: url
            }).done(function(data) {
                $('#generic-action-form-container').html(data);
            });
        });

</script>


<?php echo '<div id="action-edit">';
echo '<h2>' . __('Muokkaa toimenpidettä') . ' (' . $action_types[$action_data['Action']['action_type_id']] . ')' . '</h2>';
echo '<h3>' . $action_data['CourseMembership']['Student']['last_name'] . ' '
     . $action_data['CourseMembership']['Student']['first_name'] . '</h3>';
//echo $action_types[$action_data['Action']['action_type_id']];
//debug($action_data);
//debug($exercises);

// Create dropdown-list to change action type
echo $this->Form->create('Switcher');
echo $this->Form->input('id', array(
        'id' => 'action-type-switcher',
        'options' => $action_types,
        'default' => $action_data['Action']['action_type_id'],
        'label' => __('Toimenpidetyyppi'),
    )
);
echo $this->Form->end();

echo '<div id="generic-action-form-container">';
echo $this->element('generic-action-form', array(
        'action_data' => $action_data,
        'list_action_exercises' => $list_action_exercises,
        'exercises' => $exercises,
        'users' => $users,
        'print_handled' => true
    )
);

echo '</div>';
echo '</div>';

