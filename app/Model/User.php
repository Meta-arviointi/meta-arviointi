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

    public $hasMany = array('Group', 'ActionComment', 'Action', 'ChatMessage');

    public $hasAndBelongsToMany = array('Course');

    public $virtualFields = array(
        'name' => 'User.first_name || \' \' || User.last_name'
    );

    public $validate = array(
        'basic_user_account' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'required' => true,
                'message' => 'Tunnus vaaditaan'
            ),
            'maxLength' => array(
                'rule' => array('maxLength', 30),
                'required' => true,
                'message' => 'Tunnus liian pitkä (max 30 merkkiä)'
            ),
            'isUnique' => array(
                'rule' => 'isUnique',
                'required' => true,
                'message' => 'Tunnus on jo käytössä'
            )
        ),
        'password' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Salasana on pakollinen'
            )
        ),
        'email' => array(
            'email',
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Sähköposti on pakollinen'
            )
        ),
        'first_name' => array(
            'required' => array(
                'rule' => 'notEmpty',
                'message' => 'Etunimi on pakollinen'
            )
        ),
        'last_name' => array(
            'required' => array(
                'rule' => 'notEmpty',
                'message' => 'Sukunimi on pakollinen'
            )
        )
    );

    public function get_last_course($user_id) {
        // User_id must be passed
        if ( !empty($user_id) ) {

            $options = array(
                'conditions' => array(
                    'id' => $user_id),
                'contain' => array(
                    'Course' => array(
                        'order' => 'starttime DESC')
                    )
            );
            $user = $this->find('first', $options);
            return $user['Course'][0];

        } else { // Not valid user_id, return false
            return false;
        }
    }

    /**
     * Returns all courses that user with $user_id
     * has attended.
     */
    public function user_courses($user_id) {
        if ( !empty($user_id) ) {
            $options = array(
                'conditions' => array(
                    'id' => $user_id
                ),
                'contain' => array(
                    'Course' => array(
                        'order' => 'starttime ASC'
                    )
                )
            );

            $user = $this->find('first',$options);
            return $user['Course'];
        } else {
            return false;
        }
    }

    /**
     * Returns user's ($user_id) group in
     * selected course ($course_id).
     */
    public function user_group($user_id, $course_id) {
        // Check parameters validity
        if ( !empty($user_id) && !empty($course_id) ) {
            return $this->Group->find('first', array(
                'conditions' => array(
                    'Group.user_id' => $user_id,
                    'Group.course_id' => $course_id
                    ),
                'contain' => array(
                    'User'
                    )
                )
            );
        } else {
            return false;
        }
    }

    public function find_user($bua) {
        return $this->find('first', array('conditions' => array('basic_user_account' => $bua)));
    }

    /**
     * Returns all courses that user with $user_id
     * has attended.
     */
    public function user_in_course($user_id, $course_id) {
        if ( !empty($user_id) && !empty($course_id) ) {
            $options = array(
                'conditions' => array(
                    'id' => $user_id
                ),
                'contain' => array(
                    'Course' => array(
                        'conditions' => array(
                            'Course.id' => $course_id
                        )
                    )
                )
            );
            $user = $this->find('first',$options);
            return $user['Course']; // empty array() if false
        } else {
            return false;
        }
    }
}
