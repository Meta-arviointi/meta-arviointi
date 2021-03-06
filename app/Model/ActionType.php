<?php

class ActionType extends AppModel {
	public $name = 'ActionType';
	
	public $hasMany = array('Action');

    public $hasOne = array('ActionEmailTemplate' => array(
        'dependent' => true
        )
    );
    
	/**
	 * Fetch action types, that are active 
	 * @return all types with id as key and type name as value
	 */
	public function types() {
		return $this->find('list', array(
				'conditions' => array(
					'active' => true
				)
			)
		);

	}
}
?>