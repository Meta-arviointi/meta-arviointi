<script type="text/javascript">
    $(document).ready(function() {

        $('select#course-switcher').change(function() {
            if ( $(this).val() != '' ) {
                var url = <?php echo '\''. $this->Html->url(
                        array(
                            'controller' => 'course_memberships',
                            'action' => 'create_many'                            
                        )
                    ) . '\';' ?>
                url = url + "/" + $(this).val();
                $.ajax({
                    url: url
                }).done(function(data) {
                    $('#user-selection-div').html(data);
                })    
            } else {
                $('#user-selection-div').html('');
            }
        });

        $('#many-course_memberships-form').submit(function() {
            // #SelectManyCourseMemberships is in /students/admin_index !
            $(this).append(($('#SelectManyCourseMemberships').find('input[type="checkbox"]:checked')).attr('type','hidden'));
            return true;
        });

    });
</script>
<?php 
echo '<div id="coursememberships-create">';
echo '<h2>' . __('Lisää valitut opiskelijat kurssille') . '</h2>';


// Create dropdown-list to change Course
echo $this->Form->create('CourseMembership', array('id' => 'many-course_memberships-form'));
echo $this->Form->input('Course.id', array(
        'id' => 'course-switcher',
        'options' => $courses,
        'empty' => __('Valitse kurssi'),
        'label' => __('Kurssi'),
    )
);

echo '<div id="user-selection-div">';
echo '</div>';
echo '</div>';