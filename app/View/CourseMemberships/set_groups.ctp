<script type="text/javascript">
    $(document).ready(function() {
        // Inlcude Student-checkboxes in form before submit
        $('#UserSetGroupsForm').submit(function() {
            // #SelectStudents is in /courses/view !
            $(this).append(($('#SelectStudents').find('input[type="checkbox"]:checked')).attr('type','hidden'));
            return true;
        });

    });
</script>
<h1>Valitse vastuuryhmä</h1>
<p>Jos valittu opiskelija kuului jo ryhmään, vanha ryhmä poistetaan ja opiskelija lisätään uuteen ryhmään.</p>
<?php
echo $this->Form->create('User');
echo $this->Form->input('id', array(
        'label' => __('Assistentti'),
        'empty' => __('Valitse vastuuryhmä'),
        'options' => $users
    )
);
echo $this->Form->end('Tallenna');

