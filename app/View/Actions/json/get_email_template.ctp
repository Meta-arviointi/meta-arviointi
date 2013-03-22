<?php
	
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
	$content = str_replace('{#aikaraja}', date('d.m.Y H:i', strtotime($action['Action']['deadline'])), $content);
	$content = str_replace('{#opiskelija}', $action['CourseMembership']['Student']['first_name'] . ' ' . $action['CourseMembership']['Student']['last_name'] , $content);
	
	$content = rawurlencode(utf8_decode($content));


	$subject = $action['ActionType']['ActionEmailTemplate']['subject'];

	// Replace tags
	$subject = str_replace('{#harjoitus}', $ex_titles_str, $subject);
	$subject = str_replace('{#selite}', $action['Action']['description'], $subject);
	$subject = str_replace('{#aikaraja}', date('d.m.Y H:i', strtotime($action['Action']['deadline'])), $subject);
	$subject = str_replace('{#opiskelija}', $action['CourseMembership']['Student']['first_name'] . ' ' . $action['CourseMembership']['Student']['last_name'] , $subject);
	
	$subject = rawurlencode(utf8_decode($subject));

?>

{"subject": "<?php echo $subject; ?>", "content": "<?php echo $content; ?>"}