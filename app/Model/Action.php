<?php

class Action extends AppModel {
    public $name = 'Action';

    public $belongsTo = array('Student', 'User', 'ActionType');
    public $hasMany = array('ActionComment' => array(
        'dependent' => true
        )
    );
    public $hasAndBelongsToMany = array('Exercise');

    /*
     * Return all actions created after last login.
     * Optional parameter last_login. If not set take
     * last_login from session.
     */
    public function new_actions($last_login = 0) {
        if ( empty($last_login) )
            $last_login = $this->Session->read('User.last_login');
        if ( $last_login ) {
            $this->contain();
            $actions = $this->find('all', array(
                    'conditions' => array(
                        'created >' => $last_login
                    )
                )
            );
            return $actions;
        } else {
            return null;
        }
    }

    /*
     * Return count of all actions created after last login.
     * Optional parameter last_login. If not set take
     * last_login from session.
     */
    public function new_actions_count($last_login = 0) {
        if ( empty($last_login) )
            $last_login = $this->Session->read('User.last_login');
        if ( $last_login ) {
            $this->contain();
            $actions_count = $this->find('count', array(
                    'conditions' => array(
                        'created >' => $last_login
                    )
                )
            );
            return $actions_count;
        } else {
            return null;
        }
    }
}
?>