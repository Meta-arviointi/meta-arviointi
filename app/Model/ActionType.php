<?php

class ActionType extends AppModel {
	public $name = 'ActionType';
	
	public $hasMany = array('Action');

	/**
	 * Fetch action types 
	 * @return all types with id as key and type name as value
	 */
	public function types() {
		return $this->find('list');

	}
}
?>