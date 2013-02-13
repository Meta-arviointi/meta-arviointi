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
    public function new_actions($last_login = 0, $course_id = 0) {
        App::uses('CakeSession', 'Model/Datasource');
        if ( empty($last_login) )
            $last_login = CakeSession::read('User.last_login');
        if ( empty($course_id) )
            $course_id = CakeSession::read('Course.course_id');
        if ( $last_login ) {
            $actions = $this->find('all', array(
                    'conditions' => array(
                        'created >' => $last_login
                    ),
                    'contain' => array(
                        'Exercise' => array(
                            'conditions' => array(
                                'Exercise.course_id' => $course_id
                            )
                        )
                    )
                )
            );

            /*
             * Delete actions that don't belong to current course.
             */
            foreach ($actions as $index => $action) {
                if ( empty($action['Exercise']) ) {
                    unset($actions[$index]);
                }
            }
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
    public function new_actions_count($last_login = 0, $course_id = 0) {
        if ( empty($last_login) )
            $last_login = CakeSession::read('User.last_login');
        if ( empty($course_id) )
            $course_id = CakeSession::read('Course.course_id');
        if ( $last_login ) {
            $actions = $this->new_actions($last_login, $course_id);
            return count($actions);
        } else {
            return null;
        }
    }

    /*
     * Return all open (not handled) actions.
     * Optional parameter course id. If not set
     * take id from Session.
     */
    public function open_actions($course_id = 0, $contain = array() ) {
        App::uses('CakeSession', 'Model/Datasource');
        if ( empty($course_id) )
            $course_id = CakeSession::read('Course.course_id');

        // if $contain empty, include only Exercise
        if ( empty($contain) ) {
            $contain = array(
                'Exercise' => array(
                    'conditions' => array(
                        'Exercise.course_id' => $course_id
                    )
                )
            );
        } else { // $contain is set, add Exercise if not set already
            if ( !isset($contain['Exercise']['conditions']['Exercise.course_id']) ) {
                $contain['Exercise'] = array(
                    'conditions' => array(
                        'Exercise.course_id' => $course_id)
                );
            }
        }
        // Find actions with handled_id null and $contain
        $actions = $this->find('all', array(
                'conditions' => array(
                    'handled_id =' => null
                ),
                'contain' => $contain
            )
        );

        /*
         * Delete actions that don't belong to current course.
         */
        foreach ($actions as $index => $action) {
            if ( empty($action['Exercise']) ) {
                unset($actions[$index]);
            }
        }
        return $actions;
    }

}
?>