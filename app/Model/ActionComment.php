<?php

class ActionComment extends AppModel {
	public $name = 'ActionComment';

	public $belongsTo = array('Action', 'User');

	public $validate = array(
		'comment' => array(
			'rule' => 'notEmpty',
			'required' => true,
			'message' => 'Kommentti ei voi olla tyhjä'
		)
	);
}
?>