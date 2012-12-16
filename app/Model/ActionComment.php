<?php

class ActionComment extends AppModel {
	public $name = 'ActionComment';

	public $belongsTo = array('Action', 'User');
}
?>