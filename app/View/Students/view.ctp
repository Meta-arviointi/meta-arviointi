<?php 
     /* DEBUG */
    echo '<pre>';
    //var_dump($student);
    echo '</pre>';
?>
<div class="row">
    <div class="twelvecol last">
        <?php
        echo $this->Html->link('&larr; Takaisin listaukseen', array('action' => 'index'), array('escape' => false));
        ?>
    </div>
</div>
<hr class="row">
<div class="row">
    <div class="ninecol">
        <?php

        echo '<h1>';
        echo '<strong>' . trim($student['Student']['first_name']) . ' ' . trim($student['Student']['last_name']) . '</strong> ' . $student['Student']['student_number'];
        echo '</h1>';
        ?>
        <p>
            <?php echo $student['Student']['email'] ?>
        </p>
        <p>Kommentti: <?php echo empty($student['CourseMembership'][0]['comment']) ? '-' : $student['CourseMembership'][0]['comment'] ?></p>

    </div>
    <div class="threecol last">
        <?php
        echo $this->Html->link('Muokkaa', array('action' => 'edit', $student['Student']['id']), array('class' => 'button float-right'));
        echo $this->Html->link('Poista', array('action' => 'delete', $student['Student']['id']), array('class' => 'button float-right'), 'Haluatko varmasti poistaa opiskelijan järjestelmästä?');
        ?>
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
                'url' => array('controller' => 'students', 'action' => 'add_action')
            ));
            echo $this->Form->input('student_id', array('type' => 'hidden', 'default' => $student['Student']['id']));
            echo $this->Form->input('user_id', array('type' => 'hidden', 'default' => $this->Session->read('Auth.User.id')));
            echo $this->Form->input('action_type_id', array('type' => 'hidden', 'default' => '1'));
            echo $this->Form->input('exercise_id', array('label' => __('Harjoitus'), 'options' => $exercises));
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
                'url' => array('controller' => 'students', 'action' => 'add_action')
            ));
            echo $this->Form->input('student_id', array('type' => 'hidden', 'default' => $student['Student']['id']));
            echo $this->Form->input('user_id', array('type' => 'hidden', 'default' => $this->Session->read('Auth.User.id')));
            echo $this->Form->input('action_type_id', array('type' => 'hidden', 'default' => '3'));
            echo $this->Form->input('exercise_id', array('label' => __('Harjoitus'), 'options' => $exercises));
            echo $this->Form->input('status', array('label' => __('Tila'), 'options' => array('0' => __('Ei käsitelty'), '1' => __('Käsitelty'))));
            echo $this->Form->input('description', array('label' => false, 'rows' => 3));
            echo $this->Form->submit(__('Lisää'), array('before' => '<a href="#" class="collapse-toggle cancel">' . __('Peruuta') . '</a>'));
            echo $this->Form->end();


            // HYLKÄYS
            echo $this->Form->create('Action', array(
                'class' => 'student-action-form', 
                'id' => 'reject-action-form', 
                'url' => array('controller' => 'students', 'action' => 'add_action')
            ));
            echo $this->Form->input('student_id', array('type' => 'hidden', 'default' => $student['Student']['id']));
            echo $this->Form->input('user_id', array('type' => 'hidden', 'default' => $this->Session->read('Auth.User.id')));
            echo $this->Form->input('action_type_id', array('type' => 'hidden', 'default' => '2'));
            echo $this->Form->input('exercise_id', array('label' => __('Harjoitus'), 'options' => $exercises));
            echo $this->Form->input('status', array('label' => __('Tila'), 'options' => array('0' => __('Ei käsitelty'), '1' => __('Käsitelty'))));
            echo $this->Form->input('description', array('label' => false, 'rows' => 3));
            echo $this->Form->submit(__('Lisää'), array('before' => '<a href="#" class="collapse-toggle cancel">' . __('Peruuta') . '</a>'));
            echo $this->Form->end();



            // LISÄAIKA
            echo $this->Form->create('Action', array(
                'class' => 'student-action-form', 
                'id' => 'extra-action-form', 
                'url' => array('controller' => 'students', 'action' => 'add_action')
            ));
            echo $this->Form->input('student_id', array('type' => 'hidden', 'default' => $student['Student']['id']));
            echo $this->Form->input('user_id', array('type' => 'hidden', 'default' => $this->Session->read('Auth.User.id')));
            echo $this->Form->input('action_type_id', array('type' => 'hidden', 'default' => '4'));
            echo $this->Form->input('exercise_id', array('label' => __('Harjoitus'), 'options' => $exercises));
            echo $this->Form->input('status', array('label' => __('Tila'), 'options' => array('0' => __('Ei käsitelty'), '1' => __('Käsitelty'))));
            $default_deadline = date('Y-m-d') . ' 16:00:00';
            echo $this->Form->input('previous_deadline', array(
                'type'          => 'datetime',
                'label'         => __('Vanha aikaraja'), 
                'default'       => $default_deadline, 
                'timeFormat'    => 24, 
                'dateFormat'    => 'DMY',
                'interval'      => 15,
                'minYear'       => date('Y'),
                'maxYear'       => date('Y') + 1,
                'monthNames'    => false,
                'separator'     => '.'
            ));

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

        foreach($student['Action'] as $action) {
            $action_title = $action['ActionType']['name'];
            if(!empty($action['exercise_id'])) $action_title = 'H' . $action['exercise_id'] . ': ' . $action_title;
            echo '<div class="action">';
            echo '<h3>' . $action_title . '</h3>';
            if(!empty($action['deadline'])) echo '<p class="deadline">Aikaraja: '.date('d.m.Y H:i', strtotime($action['deadline'])).'</p>';
            if(!empty($action['description'])) echo '<p class="comment">'.$action['description'].'</p>';
            echo '<div class="meta">';
            echo '<span class="timestamp">'.date('d.m.Y H:i', strtotime($action['created'])).'</span> - ';
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
                echo '</div>';
            }

            // ADD A NEW COMMENT
            echo $this->Form->create('ActionComment', array(
                'url' => array('controller' => 'students', 'action' => 'add_action_comment'),
                'inputDefaults' => array(
                    'label' => false
                )
            ));
            echo $this->Form->input('action_id', array('type' => 'hidden', 'default' => $action['id']));
            echo $this->Form->input('user_id', array('type' => 'hidden', 'default' => $this->Session->read('Auth.User.id')));
            echo $this->Form->input('comment', array('rows' => 2));
            echo $this->Form->submit(__('Lähetä kommentti'));
            echo $this->Form->end();

            echo '</div>';

            echo '</div>';
        }
        ?>
    </div>
    <div class="sixcol last">
        <h2>Sähköposti</h2>
        <p>To be done.</p>
    </div>
</div>
