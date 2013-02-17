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
        });

        $('select#action-type-switcher').change(function() {
            if ( $(this).val() != '' ) {
                var url = <?php echo '\''. $this->Html->url(
                        array(
                            'controller' => 'actions',
                            'action' => 'create',
                            $action_data['CourseMembership']['id']
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

        $('.generic-action-form').find('.checkbox').each(function() {
            var thisDiv = this;
            var url = <?php echo '\'' .  $this->Html->url(array(
                    'controller' => 'course_memberships',
                    'action' => 'review_end'
                )
            ) . '\';' . "\n" ?>
            var inputVal = $(thisDiv).find('input[type="checkbox"]').val();
            $.ajax({
                url: url + "/" + inputVal
            }).done(function(data){
                $(thisDiv).append('<span class="timestamp"><?php echo __('Viim. arviointipäivä') . ': \''?> + data + '</span>');
            });
        });


    });
</script>
<?php 
echo '<div id="action-create">';
echo '<h2>' . __('Luo toimenpide') . '</h2>';
echo '<h3>' . $action_data['Student']['last_name'] . ' '
     . $action_data['Student']['first_name'] . '</h3>';
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