<?php

class User extends AppModel {
	public $name = 'User';

	public $hasMany = array('Group');

	public $virtualFields = array(
    	'name' => 'User.first_name || \' \' || User.last_name'
	);


}
?>