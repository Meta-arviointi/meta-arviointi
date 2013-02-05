<script type="text/javascript">
    $(document).ready(function() {
        $('.generic-action-form').submit(function() {
            var n = $(this).find('input[type="checkbox"]:checked').length;
            if ( n == 0 ) {
                alert('<?php echo __("Valitse ainakin yksi harjoitus")?>');
                return false;
            } else {
                return true;
            }
        })

        $('#action-type-change').click(function(event) {
            alert('ASDSAD');
            event.preventDefault();
            $.ajax({
                url: <?php echo '\''. $this->Html->url( 
                    array(
                        'controller' => 'actions', 
                        'action' => 'edit_test', 
                        $action_data['Action']['id'], 2
                    )
                ) . '\''; ?>
            }).done(function(data) {
                $('#generic-action-form').html(data);
            })
        })

        $('#action-type-change4').click(function(event) {
            event.preventDefault();
            $('#generic-action-form').empty();

            $.ajax({
                url: <?php echo '\''. $this->Html->url( 
                    array(
                        'controller' => 'actions', 
                        'action' => 'edit_test', 
                        $action_data['Action']['id'], 4
                    )
                ) . '\''; ?>
            }).done(function(data) {
                $('#generic-action-form').html(data);
            })
        })
    })
</script>


<?php echo '<div id="action-edit">';
echo '<h2>Muokkaa toimenpidettä</h2>';
//echo $action_types[$action_data['Action']['action_type_id']];
//debug($action_data);
//debug($exercises);


echo $this->Html->link('ActionTypeID 2',null, 
    array(
        'escape' => false,
        'id' => 'action-type-change'
    )
);

echo $this->Html->link('ActionTypeID 4',null, 
    array(
        'escape' => false,
        'id' => 'action-type-change4'
    )
);

echo '<div id="generic-action-form">';
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

