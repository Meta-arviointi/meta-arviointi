<?php
	echo $this->Form->create('EmailMessage', array('url' => array('admin' => false, 'controller' => 'email_messages', 'action' => 'send_many'), 'id' => 'SendActionEmailsForm'));
	$i = 0;
	$count = count($actions);
	foreach($actions as $action) {

		$subject = $action['ActionType']['ActionEmailTemplate']['subject'];

		$ex_titles = array();
		foreach($action['Exercise'] as $ex) {
			$ex_titles[] = $ex['exercise_number'] . ': ' . $ex['exercise_name'];
		}
		$ex_titles_str = implode(", ", $ex_titles);
		
		//print_r($action); die();

		$content = $action['ActionType']['ActionEmailTemplate']['content'];

		// Replace tags
		$content = str_replace('{#harjoitus}', $ex_titles_str, $content);
		$content = str_replace('{#selite}', $action['Action']['description'], $content);
		$content = str_replace('{#opiskelija}', $action['CourseMembership']['Student']['first_name'] . ' ' . $action['CourseMembership']['Student']['last_name'] , $content);


		echo '<div class="message-form';
		if($i == 0) { echo ' current'; }
		echo '">';

		echo '<div class="form-navi">';

		if($i == 0) { $prev_append_class = ' disabled'; } else { $prev_append_class = ''; }
		echo $this->Html->link('<', '#', array('class' => 'prev button' . $prev_append_class));

		if(($i + 1) == $count) { $next_append_class = ' disabled'; } else { $next_append_class = ''; }
		echo $this->Html->link('>', '#', array('class' => 'next button' . $next_append_class));

		echo '<div class="count">';
		echo ($i + 1) . '/' . $count;
		echo '</div>';


		echo '</div>';


		echo '<strong>'.__('Vastaanottaja').':</strong> ' . $action['CourseMembership']['Student']['name'] . ' &lt;' . $action['CourseMembership']['Student']['email'] . '&gt;';

		echo $this->Form->input('EmailMessage.'.$i.'.course_membership_id', array('type' => 'hidden', 'value' => $action['CourseMembership']['id']));
        echo $this->Form->input('EmailMessage.'.$i.'.subject', array('label' => __('Otsikko'), 'value' => $subject));
        echo $this->Form->input('EmailMessage.'.$i.'.content', array('label' => __('Viesti'), 'value' => $content, 'rows' => 10));
        echo __('Ystävällisin terveisin') . ',<br>' . $this->Session->read('Auth.User.name');
        echo '<div class="submit">';
        echo $this->Html->link(__('Lähetä'), array('controller' => 'email_messages', 'action' => 'send'), array('class' => 'button'));
        echo $this->Form->submit(__('Lähetä kaikki'), array('div' => false, 'class' => 'send-all'));
        echo '</div>';
        echo '</div>';
        $i++;
	}
	echo $this->Form->end();
?>
<script>
	$('#SendActionEmailsForm .form-navi a.next').click(function() {
      if(!$(this).hasClass('disabled')) {
      	$('#SendActionEmailsForm .message-form.current').removeClass('current').next('.message-form').addClass('current');
      }
      return false;
    });

	$('#SendActionEmailsForm .form-navi a.prev').click(function() {
      if(!$(this).hasClass('disabled')) {
      	$('#SendActionEmailsForm .message-form.current').removeClass('current').prev('.message-form').addClass('current');
      }
      return false;
    });

    $('#SendActionEmailsForm .send-all').click(function() {
    	return confirm('<?php echo __('Haluatko varmasti lähettää kaikki viestit?')?>');
    });
</script>