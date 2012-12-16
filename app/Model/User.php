<?php

App::uses('AuthComponent', 'Controller/Component');

class User extends AppModel {

    public function beforeSave($options = array()) {
        if (isset($this->data[$this->alias]['password'])) {
            $this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['password']);
        }
        return true;
    }

    public $name = 'User';

    public $hasMany = array('Group', 'ActionComment');

    public $hasAndBelongsToMany = array('Course');

    public $virtualFields = array(
        'name' => 'User.first_name || \' \' || User.last_name'
    );

    public $validate = array(
        'basic_user_account' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'A basic user account is required'
            )
        ),
        'password' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'A password is required'
            )
        )

    );
}
?>
