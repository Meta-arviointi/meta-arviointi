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
	$content = str_replace('{#opiskelija}', $action['CourseMembership']['Student']['first_name'] . ' ' . $action['CourseMembership']['Student']['last_name'] , $content);
	
	$content = rawurlencode(utf8_decode($content));

?>

{"subject": "<?php echo $action['ActionType']['ActionEmailTemplate']['subject']; ?>", "content": "<?php echo $content; ?>"}