<?php 
     /* DEBUG */
    echo '<pre>';
    //debug($course_membership);
      // debug($this->request);
    echo '</pre>';
?>
<script type="text/javascript">
    $(document).ready(function() {
        $('.student-action-form').each(function() {
            var formToHandle = this;
            $(formToHandle).on("submit", function() {
                var n = $(formToHandle).find('input[type="checkbox"]:checked').length;
                if ( n == 0 ) {
                    alert('<?php echo __("Valitse ainakin yksi harjoitus")?>');
                    return false;
                } else {
                    return true;
                }
            })
        });

        $('.student-action-form').each(function() {
            var formToHandle = this;
            var url = <?php echo '\'' .  $this->Html->url(array(
                        'controller' => 'course_memberships',
                        'action' => 'review_end'
                    )
                ) . '\';' . "\n" ?>
            $(formToHandle).find('.checkbox').each(function() {
                var thisDiv = this;
                var inputVal = $(this).find('input[type="checkbox"]').val();
                $.ajax({
                    url: url + "/" + inputVal
                }).done(function(data){
                    $(thisDiv).append('<span class="timestamp"><?php echo __('Viim. arviointipäivä') . ': \''?> + data + '</span>');
                });
            });
        });

        var scroll = $.urlParam('scroll_to');
        $.scrollTo($('#'+scroll), {offset: -45});

    })
</script>
<div class="row">
    <div class="twelveol">
<?php 
$links = array(
        array('text' => __('Opiskelijat'), 'url' => array('controller' => 'students')),
        array('text' => __('Toimenpiteet'), 'url' => array('controller' => 'actions')),
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
    <div class="sixcol">
        <?php

        echo '<h1>';
        echo '<strong>' . trim($course_membership['Student']['first_name']) . ' ' . trim($course_membership['Student']['last_name']) . '</strong> ' . $course_membership['Student']['student_number'];
        echo '</h1>';
        ?>
        <p>
            <?php echo $course_membership['Student']['email'] ?>
        </p>

        <p>Kommentti: <?php echo empty($course_membership['CourseMembership']['comment']) ? '-' : $course_membership['CourseMembership']['comment'] ?>
            <?php
                echo $this->Html->link('('.__('Muokkaa').')',
                    array(
                        'action' => 'edit_comment', 
                        $course_membership['CourseMembership']['id']
                    ), 
                    array('class' => 'modal-link')
                )
            ?>
        </p>

    </div>
    <div class="threecol">
        <?php echo '<p>' . __('Kursseilla') . ":<p> \n"  ?>
        <?php
            // Display links to other courses
            foreach($student_courses as $cm) {
                if ( $cm['CourseMembership']['id'] != 
                    $course_membership['CourseMembership']['id']) {
                    echo '<span class="student-courses">' . 
                        $this->Html->link($cm['Course']['name'],
                            array(
                                'action' => 'view',
                                $cm['CourseMembership']['id']
                            )
                        ) . '</span>' . "\n";

                } else {
                    echo '<span class="student-courses">' .
                        $cm['Course']['name'] . '</span>' . "\n";
                }
            } 
        ?>
    </div>
    <div class="threecol last">
        <?php
        /*echo $this->Html->link('Muokkaa', array('controller' => 'students', 'action' => 'edit', $course_membership['Student']['id']), array('class' => 'button float-right modal-link'));*/
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
            if ( !empty($exercises) ) {
                echo '<strong>Lisää: </strong>';
                echo '<a href="#" data-action-type="request">Korjauspyyntö</a>';
                echo '<a href="#" data-action-type="notice">Huomautus</a>';
                echo '<a href="#" data-action-type="reject">Hylkäys</a>';
                echo '<a href="#" data-action-type="extra">Lisäaika</a>';    
            } else {
                echo '<strong>' . __('Harjoituksia ei saatavilla, toimenpiteitä ei voi lisätä') . '</strong>';
            }
            
            echo '</div>';



            // KORJAUSPYYNTÖ
            echo $this->element('action-request-form', array(
                    'course_membership' => $course_membership,
                    'exercises' => $exercises,
                    'print_handled' => false
                )
            );


            // HUOMAUTUS
            echo $this->element('action-notice-form', array(
                    'course_membership' => $course_membership,
                    'exercises' => $exercises,
                    'print_handled' => false
                )
            );

            // HYLKÄYS
            echo $this->element('action-reject-form', array(
                    'course_membership' => $course_membership,
                    'exercises' => $exercises,
                    'print_handled' => false
                )
            );


            // LISÄAIKA
            echo $this->element('action-extra-form', array(
                    'course_membership' => $course_membership,
                    'exercises' => $exercises,
                    'print_handled' => false
                )
            );




        echo '</div>';

        foreach($student_actions as $action) {

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

            echo '<div class="action" id="action'.$action['Action']['id'].'">';
            echo '<div class="toolbar">';
            echo $this->Html->link(__('Lähetä sähköposti'),
                '#', 
                array(
                    'class' => 'email-action',
                    'onClick' => 'javascript: window.emailAction('.$action['Action']['id'].'); return false;'
                )
            );
            echo $this->Html->link(__('Muokkaa'),
                array(
                    'controller' => 'actions',
                    'action' => 'edit', 
                    $action['Action']['id']
                ), 
                array(
                    'class' => 'modal-link edit-action',
                )
            );
            echo $this->Html->link(
                'Poista',
                array('controller' => 'actions', 'action' => 'delete', $action['Action']['id']),
                array('class' => 'delete-action'),
                __('Haluatko varmasti poistaa toimenpiteen?')
            );
            echo '</div>';
            echo '<h3>' . $action_title . '</h3>';
            if ( !empty($action['Action']['handled_id']) ) {
                echo '<div class="meta"><span>(' . __('Käsitellyt') . ': ' . 
                    $users[$action['Action']['handled_id']] . ' - ' .
                    date('j.n.Y G:i', strtotime($action['Action']['handled_time'])) . ')</span></div>';
            }
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
                echo '<span class="timestamp">'.date('d.m.Y H:i', strtotime($comment['created'])).'</span>';
                echo '</div>';
            }

            // ADD A NEW COMMENT
            echo $this->Form->create('ActionComment', array(
                'url' => array('controller' => 'actions', 'action' => 'add_action_comment'),
                'inputDefaults' => array(
                    'label' => false
                )
            ));
            echo $this->Form->input('action_id', array('type' => 'hidden', 'default' => $action['Action']['id']));
            echo $this->Form->input('user_id', array('type' => 'hidden', 'default' => $this->Session->read('Auth.User.id')));
            echo $this->Form->input('comment', array('rows' => 2));
            echo $this->Form->error('comment');
            echo $this->Form->submit(__('Lähetä kommentti'));
            echo $this->Form->end();

            echo '</div>';

            echo '</div>';
        }
        ?>
    </div>
    <div class="sixcol last">
        <h2>Sähköposti</h2>
        <?php
        echo '<div id="student-email-form-container">';

            echo '<a href="#" id="student-email-form-link">Lähetä uusi sähköpostiviesti</a>';

            // KORJAUSPYYNTÖ
            echo $this->Form->create('Mail', array(
                'class' => 'student-email-form', 
                'id' => 'student-email-form', 
                'url' => array('controller' => 'emails', 'action' => 'send'),
                'inputDefaults' => array('label' => false) // ilman tätä tulostuu jostain "Redirect" labeliksi
            ));
            echo $this->Form->input('title', array('label' => __('Otsikko')));
            echo $this->Form->input('content', array('label' => __('Viesti'), 'rows' => 10));
            echo $this->Form->submit(__('Lähetä'), array('before' => '<a href="#" class="collapse-toggle cancel">' . __('Peruuta') . '</a>'));
            echo $this->Form->end();
        echo '</div>';
        ?>
        <div id="email-messages">
            <?php 
                foreach($course_membership['EmailMessage'] as $msg) {
                    echo '<div class="email-message';
                    if(empty($msg['read_time'])) echo ' not-read';
                    echo '">';

                    echo '<h3>'.$msg['subject'].'</h3>';
                    echo '<p>'.$msg['content'].'</p>';

                    echo '<div class="meta">';
                    if(empty($msg['read_time'])) {
                        echo $this->Html->link(
                            __('Merkitse luetuksi'),
                            array(
                                'controller' => 'email_messages',
                                'action' => 'mark_as_read', 
                                $msg['id']
                            ),
                            array(
                                'class' => 'button mark-as-read'
                            )
                        );
                    }
                    echo '<span class="timestamp">'.date('d.m.Y H:i:s', strtotime($msg['sent_time'])).'</span>';
                    echo '</div></div>';
                }
            ?>
        </div> 
    </div>
</div>