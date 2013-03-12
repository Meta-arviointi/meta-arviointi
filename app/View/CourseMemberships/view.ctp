<script type="text/javascript">
    $(document).ready(function() {
        $('#ActionHandleForm input[type="checkbox"]').on('click', function(e) {

            var form = $('#ActionHandleForm');
            var url = $(form).attr( 'action' );
            $.ajax({
                method: "POST",
                url: url,
                data: $(form).serialize()
            }).done(function(data) {
                $('#handled').html(data);
            });
        });

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
                echo $this->Html->link('('.__('Muokkaa kommenttia').')',
                    array(
                        'action' => 'edit_comment', 
                        $course_membership['CourseMembership']['id']
                    ), 
                    array('class' => 'modal-link')
                )
            ?>
        </p>
        <p>
        <?php
            echo __('Vastuuassistentti').': <strong>'.
                $users[$course_membership['Student']['Group'][0]['user_id']]
                .'</strong>';
            echo '</p>';
            echo $this->Html->link(__('Muuta vastuuryhmää'), array(
                    'controller' => 'students',
                    'action' => 'set_group',
                    $course_membership['Student']['id']
                ),
                array('class' => 'modal-link')
            );
        ?>

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
        echo $this->Html->link('Muokkaa', 
            array(
                'controller' => 'students',
                'action' => 'edit',
                $course_membership['Student']['id']
            ),
            array('class' => 'button float-right modal-link', 'title' => __('Muokkaa opiskelijan tietoja'))
        );
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

        foreach($course_membership['Action'] as $action) {

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

            echo '<div class="action" id="action'.$action['id'].'">';
            echo '<div class="toolbar">';
            echo $this->Form->create('Action', array(
                    'controller' => 'actions',
                    'action' => 'handle'
                )
            );
            echo $this->Form->input('id', array('type' => 'hidden', 'default' => $action['id']));
            echo '<div id="handle-action">';
            $handled_id = isset($action['handled_id']) ? $action['handled_id'] : 0;
            echo $this->Form->label('handled_id', __('Käsitelty'));
            echo $this->Form->checkbox('handled_id', array(
                'hiddenField' => false,
                'value' => $handled_id ? $handled_id : $this->Session->read('Auth.User.id'),
                'checked' => $handled_id ? 'checked' : false
            ));
            echo '</div>';
            echo $this->Form->end();
            echo $this->Html->link(__('Lähetä sähköposti'),
                '#', 
                array(
                    'class' => 'email-action',
                    'onClick' => 'javascript: window.emailAction('.$action['id'].'); return false;',
                    'title' => __('Lähetä sähköposti')
                )
            );
            echo $this->Html->link(__('Muokkaa'),
                array(
                    'controller' => 'actions',
                    'action' => 'edit', 
                    $action['id']
                ), 
                array(
                    'class' => 'modal-link edit-action',
                    'title' => 'Muokkaa'
                )
            );
            echo $this->Html->link(
                'Poista',
                array('controller' => 'actions', 'action' => 'delete', $action['id']),
                array('class' => 'delete-action', 'title' => 'Poista'),
                __('Haluatko varmasti poistaa toimenpiteen?')
            );
            echo '</div>';
            echo '<h3>' . $action_title . '</h3>';
            echo '<div id="handled" class="meta">';
            if ( !empty($action['handled_id']) ) {
                echo '<span>(' . __('Käsitellyt') . ': ' . 
                    $users[$action['handled_id']] . ' - ' .
                    date('j.n.Y G:i', strtotime($action['handled_time'])) . ')</span>';
            }
            echo '</div>';
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
                echo '<strong>' . $users[$comment['user_id']] . ':</strong> ';
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
            echo $this->Form->input('action_id', array('type' => 'hidden', 'default' => $action['id']));
            echo $this->Form->input('user_id', array('type' => 'hidden', 'default' => $this->Session->read('Auth.User.id')));
            echo $this->Form->input('comment', array('rows' => 2));
            echo $this->Form->error('comment');
            echo $this->Form->submit(__('Tallenna'));
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
            echo $this->Form->create('EmailMessage', array(
                'class' => 'student-email-form', 
                'id' => 'student-email-form', 
                'url' => array('controller' => 'email_messages', 'action' => 'send'),
                'inputDefaults' => array('label' => false) // ilman tätä tulostuu jostain "Redirect" labeliksi
            ));
            echo $this->Form->input('course_membership_id', array('type' => 'hidden', 'value' => $course_membership['CourseMembership']['id']));
            echo $this->Form->input('subject', array('label' => __('Otsikko')));
            echo $this->Form->input('content', array('label' => __('Viesti'), 'rows' => 10));
            echo __('Ystävällisin terveisin') . ',<br>' . $this->Session->read('Auth.User.name');
            echo $this->Form->submit(__('Lähetä'), array('before' => '<a href="#" class="collapse-toggle cancel">' . __('Peruuta') . '</a>'));
            echo $this->Form->end();
        echo '</div>';
        ?>
        <div id="email-messages">
            <?php 
                foreach($course_membership['EmailMessage'] as $msg) {
                    echo '<div class="email-message';
                    if(empty($msg['read_time'])) echo ' not-read';
                    if(empty($msg['sender'])) echo ' outbound';
                    echo '">';

                    echo '<h3>';
                    if(empty($msg['sender'])) { echo '&rarr; '; } else { echo '&larr; '; }
                    echo '<span class="email-subject">' . $msg['subject'].'</span></h3>';
                    echo '<p class="email-content">'.str_replace("\n", "<br>", $msg['content']).'</p>';

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
                    else if(!empty($msg['sender'])) {
                        echo $this->Html->link(__('Vastaa tähän viestiin'), '#', array('class' => 'reply-to-email'));
                    }
                    echo '<span class="timestamp">'.date('d.m.Y H:i:s', strtotime($msg['sent_time'])).'</span>';
                    echo '</div></div>';
                }
            ?>
        </div> 
    </div>
</div>