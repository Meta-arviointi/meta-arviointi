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

    public $hasMany = array('Group');

    public $virtualFields = array(
        'name' => 'User.first_name || \' \' || User.last_name'
    );

    public $validate = array(
        'email' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Email is required'
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
