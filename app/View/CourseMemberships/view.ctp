<?php 
     /* DEBUG */
    echo '<pre>';
    //var_dump($course_membership);
      // debug($this->request);
    echo '</pre>';
?>
<script type="text/javascript">
    <?php /* Load review date for default option. */ ?>
    $(document).ready(function() {
        $.ajax({
            type: 'GET',
            url: '<?php echo $this->request->webroot ?>course_memberships/review_end/' + $('#extra-action-form #ExerciseId').val(),
            success: function(data){
                $('#review_date').html(data);
            }
        });
    })

    $(document).ready(function() {
        <?php /* Load review date for selected option. */ ?>
        $('#extra-action-form #ExerciseId').change(function() {
            $.ajax({
                type: 'GET',
                url: '<?php echo $this->request->webroot ?>course_memberships/review_end/' + $(this).val(),
                success: function(data){
                    $('#review_date').html(data);
                }
            });
        });
    })
</script>
<div class="row">
    <div class="twelvecol last">
        <?php
        echo $this->Html->link('&larr; Takaisin listaukseen', array('controller' => 'courses', 'action' => 'index'), array('escape' => false));
        ?>
    </div>
</div>
<hr class="row">
<div class="row">
    <div class="ninecol">
        <?php

        echo '<h1>';
        echo '<strong>' . trim($course_membership['Student']['first_name']) . ' ' . trim($course_membership['Student']['last_name']) . '</strong> ' . $course_membership['Student']['student_number'];
        echo '</h1>';
        ?>
        <p>
            <?php echo $course_membership['Student']['email'] ?>
        </p>
        <p>Kommentti: <?php echo empty($course_membership['CourseMembership']['comment']) ? '-' : $course_membership['CourseMembership']['comment'] ?></p>

    </div>
    <div class="threecol last">
        <?php
        echo $this->Html->link('Muokkaa', array('controller' => 'students', 'action' => 'edit', $course_membership['Student']['id']), array('class' => 'button float-right modal-link'));
        ?>
        <div class="quit-info">
            <?php
            if(empty($course_membership['CourseMembership']['quit_time'])) {
                echo $this->Html->link(
                    'Aseta keskeyttäneeksi', 
                    array(
                        'action' => 'set_quit', 
                        $course_membership['CourseMembership']['id']
                    ),
                    null,
                    __('Haluatko varmasti merkitä opiskelijan keskeyttäneeksi?')
                );
            }
            else {
                echo 'Keskeyttänyt: ' . date('d.m.Y', strtotime($course_membership['CourseMembership']['quit_time']));
                echo '<br>';
                echo '(Merkinnyt: <em>'. $users[$course_membership['CourseMembership']['quit_id']] . '</em>)';
                echo '<br>';
                echo $this->Html->link(
                    'Peruuta keskeyttäminen', 
                    array(
                        'action' => 'unset_quit', 
                        $course_membership['CourseMembership']['id']
                    ),
                    null,
                    __('Haluatko varmasti poistaa keskeytysmerkinnän?')
                );
            }

            //  echo $this->Html->link('Poista', array('action' => 'delete', $course_membership['Student']['id']), array('class' => 'button float-right'), 'Haluatko varmasti poistaa opiskelijan järjestelmästä?');
            ?>
        </div>
    </div>
</div>
<hr class="row">
<div class="row student-entries">
    <div class="sixcol">

        <h2>Toimenpiteet</h2>

        <?php
        echo '<div id="add-action-form-container">';

            echo '<div id="student-action-form-links">';
            echo '<strong>Lisää: </strong>';
            echo '<a href="#" data-action-type="request">Korjauspyyntö</a>';
            echo '<a href="#" data-action-type="notice">Huomautus</a>';
            echo '<a href="#" data-action-type="reject">Hylkäys</a>';
            echo '<a href="#" data-action-type="extra">Lisäaika</a>';
            echo '</div>';



            // KORJAUSPYYNTÖ
            echo $this->Form->create('Action', array(
                'class' => 'student-action-form', 
                'id' => 'request-action-form', 
                'url' => array('controller' => 'actions', 'action' => 'add_action'),
                'inputDefaults' => array('label' => false) // ilman tätä tulostuu jostain "Redirect" labeliksi
            ));
            echo $this->Form->input('redirect', array('type ' => 'hidden', 'default' => $course_membership['CourseMembership']['id']));
            echo $this->Form->input('student_id', array('type' => 'hidden', 'default' => $course_membership['Student']['id']));
            echo $this->Form->input('user_id', array('type' => 'hidden', 'default' => $this->Session->read('Auth.User.id')));
            echo $this->Form->input('action_type_id', array('type' => 'hidden', 'default' => '1'));
            echo $this->Form->input('Exercise.id', array('label' => __('Harjoitus'), 'options' => $exercises));
            echo $this->Form->input('status', array('label' => __('Tila'), 'options' => array('0' => __('Ei käsitelty'), '1' => __('Käsitelty'))));
            $default_deadline = date('Y-m-d') . ' 16:00:00';
            $default_deadline = date('Y-m-d H:i:s', strtotime('+ 7 day', strtotime($default_deadline)));
            echo $this->Form->input('deadline', array(
                'label'         => __('Aikaraja'), 
                'default'       => $default_deadline, 
                'timeFormat'    => 24, 
                'dateFormat'    => 'DMY',
                'interval'      => 15,
                'minYear'       => date('Y'),
                'maxYear'       => date('Y') + 1,
                'monthNames'    => false,
                'separator'     => '.'
            ));
            echo $this->Form->input('description', array('label' => false, 'rows' => 3));
            echo $this->Form->submit(__('Lisää'), array('before' => '<a href="#" class="collapse-toggle cancel">' . __('Peruuta') . '</a>'));
            echo $this->Form->end();



            // HUOMAUTUS
            echo $this->Form->create('Action', array(
                'class' => 'student-action-form', 
                'id' => 'notice-action-form', 
                'url' => array('controller' => 'actions', 'action' => 'add_action')
            ));
            echo $this->Form->input('redirect', array('type' => 'hidden', 'default' => $course_membership['CourseMembership']['id']));
            echo $this->Form->input('student_id', array('type' => 'hidden', 'default' => $course_membership['Student']['id']));
            echo $this->Form->input('user_id', array('type' => 'hidden', 'default' => $this->Session->read('Auth.User.id')));
            echo $this->Form->input('action_type_id', array('type' => 'hidden', 'default' => '3'));
            echo $this->Form->input('Exercise.id', array('label' => __('Harjoitus'), 'options' => $exercises));
            echo $this->Form->input('status', array('label' => __('Tila'), 'options' => array('0' => __('Ei käsitelty'), '1' => __('Käsitelty'))));
            echo $this->Form->input('description', array('label' => false, 'rows' => 3));
            echo $this->Form->submit(__('Lisää'), array('before' => '<a href="#" class="collapse-toggle cancel">' . __('Peruuta') . '</a>'));
            echo $this->Form->end();


            // HYLKÄYS
            echo $this->Form->create('Action', array(
                'class' => 'student-action-form', 
                'id' => 'reject-action-form', 
                'url' => array('controller' => 'actions', 'action' => 'add_action')
            ));
            echo $this->Form->input('redirect', array('type' => 'hidden', 'default' => $course_membership['CourseMembership']['id']));
            echo $this->Form->input('student_id', array('type' => 'hidden', 'default' => $course_membership['Student']['id']));
            echo $this->Form->input('user_id', array('type' => 'hidden', 'default' => $this->Session->read('Auth.User.id')));
            echo $this->Form->input('action_type_id', array('type' => 'hidden', 'default' => '2'));
            echo $this->Form->input('Exercise.id', array('label' => __('Harjoitus'), 'options' => $exercises));
            echo $this->Form->input('status', array('label' => __('Tila'), 'options' => array('0' => __('Ei käsitelty'), '1' => __('Käsitelty'))));
            echo $this->Form->input('description', array('label' => false, 'rows' => 3));
            echo $this->Form->submit(__('Lisää'), array('before' => '<a href="#" class="collapse-toggle cancel">' . __('Peruuta') . '</a>'));
            echo $this->Form->end();



            // LISÄAIKA
            echo $this->Form->create('Action', array(
                'class' => 'student-action-form', 
                'id' => 'extra-action-form', 
                'url' => array('controller' => 'actions', 'action' => 'add_action')
            ));
            echo $this->Form->input('redirect', array('type' => 'hidden', 'default' => $course_membership['CourseMembership']['id']));
            echo $this->Form->input('student_id', array('type' => 'hidden', 'default' => $course_membership['Student']['id']));
            echo $this->Form->input('user_id', array('type' => 'hidden', 'default' => $this->Session->read('Auth.User.id')));
            echo $this->Form->input('action_type_id', array('type' => 'hidden', 'default' => '4'));
            echo $this->Form->input('Exercise.id', array('label' => __('Harjoitus'), 'options' => $exercises));
            echo $this->Form->input('status', array('label' => __('Tila'), 'options' => array('0' => __('Ei käsitelty'), '1' => __('Käsitelty'))));
            $default_deadline = date('Y-m-d') . ' 16:00:00';
            echo  __('Viimeinen arviointipäivä: ') . '<span id="review_date"></span>';

            $default_deadline = date('Y-m-d') . ' 16:00:00';
            $default_deadline = date('Y-m-d H:i:s', strtotime('+ 7 day', strtotime($default_deadline)));
            echo $this->Form->input('deadline', array(
                'label'         => __('Uusi aikaraja'), 
                'default'       => $default_deadline, 
                'timeFormat'    => 24, 
                'dateFormat'    => 'DMY',
                'interval'      => 15,
                'minYear'       => date('Y'),
                'maxYear'       => date('Y') + 1,
                'monthNames'    => false,
                'separator'     => '.'
            ));
            echo $this->Form->input('description', array('label' => false, 'rows' => 3));
            echo $this->Form->submit(__('Lisää'), array('before' => '<a href="#" class="collapse-toggle cancel">' . __('Peruuta') . '</a>'));
            echo $this->Form->end();




        echo '</div>';

        foreach($student_actions as $action) {
            // Check that Exercise is valid
            if ( !empty($action['Exercise']) ) {    

                $action_title = null;

                // If Actions belongs to several Exercises
                if ( count($action['Exercise']) > 1 ) {
                    foreach($action['Exercise'] as $exercise) {
                        $action_title = $action_title . 'H' . $exercise['exercise_number'] . ', ';
                    }
                    // Remove last two characters ',' and ' '
                    $action_title = substr($action_title, 0, -2);
                    
                } else { // only one exercise
                    $action_title = 'H' . $action['Exercise'][0]['exercise_number'];
                }
                $action_title = $action_title .  ': ' . $action['ActionType']['name'];

                echo '<div class="action">';
                echo $this->Html->link(
                    'Poista',
                    array('controller' => 'actions', 'action' => 'delete', $action['Action']['id']),
                    array('id' => 'delete-action'),
                    __('Haluatko varmasti poistaa toimenpiteen?')
                );
                echo '<h3>' . $action_title . '</h3>';
                if(!empty($action['Action']['deadline'])) echo '<p class="deadline">Aikaraja: '.date('d.m.Y H:i', strtotime($action['Action']['deadline'])).'</p>';
                if(!empty($action['Action']['description'])) echo '<p class="comment">'.$action['Action']['description'].'</p>';
                echo '<div class="meta">';
                echo '<span class="timestamp">'.date('d.m.Y H:i', strtotime($action['Action']['created'])).'</span> - ';
                echo '<span class="by">' . $action['User']['name'] . '</span>';
                echo '</div>';

                echo '<div class="comments">';

                // LIST COMMENTS
                foreach($action['ActionComment'] as $comment) {
                    echo '<div class="comment">';
                    echo '<p>';
                    echo '<strong>' . $comment['User']['name'] . ':</strong> ';
                    echo $comment['comment'];
                    echo '</p>';
                    echo '<span class="timestamp">['.date('d.m.Y H:i', strtotime($comment['created'])).']</span>';
                    echo '</div>';
                }

                // ADD A NEW COMMENT
                echo $this->Form->create('ActionComment', array(
                    'url' => array('controller' => 'actions', 'action' => 'add_action_comment'),
                    'inputDefaults' => array(
                        'label' => false
                    )
                ));
                echo $this->Form->input('redirect', array('type' => 'hidden', 'default' => $course_membership['CourseMembership']['id']));
                echo $this->Form->input('action_id', array('type' => 'hidden', 'default' => $action['Action']['id']));
                echo $this->Form->input('user_id', array('type' => 'hidden', 'default' => $this->Session->read('Auth.User.id')));
                echo $this->Form->input('comment', array('rows' => 2));
                echo $this->Form->submit(__('Lähetä kommentti'));
                echo $this->Form->end();

                echo '</div>';

                echo '</div>';   
            }
        }
        ?>
    </div>
    <div class="sixcol last">
        <h2>Sähköposti</h2>
        <p>To be done.</p>
    </div>
</div>
