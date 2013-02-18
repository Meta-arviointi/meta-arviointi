<script type="text/javascript">
    $(document).ready(function() {

        $('select#action-type-switcher').change(function() {
            if ( $(this).val() != '' ) {
                var url = <?php echo '\''. $this->Html->url(
                        array(
                            'controller' => 'actions',
                            'action' => 'create_many'                            
                        )
                    ) . '\';' ?>
                url = url + "/" + $(this).val();
                $.ajax({
                    url: url
                }).done(function(data) {
                    $('#generic-action-form').html(data);
                })    
            }
        });

    });
</script>
<?php 
echo '<div id="action-create">';
echo '<h2>' . __('Luo toimenpide usealle') . '</h2>';
/*echo '<h3>' . $action_data['Student']['last_name'] . ' '
     . $action_data['Student']['first_name'] . '</h3>';*/
//echo $action_types[$action_data['Action']['action_type_id']];
//debug($action_data);
//debug($exercises);

// Create dropdown-list to change action type
echo $this->Form->create();
echo $this->Form->input('id', array(
        'id' => 'action-type-switcher',
        'options' => $action_types,
        'empty' => __('Valitse toimenpidetyyppi'),
        'label' => __('Toimenpidetyyppi'),
    )
);
echo $this->Form->end();

echo '<div id="generic-action-form">';
echo '</div>';
echo '</div>';