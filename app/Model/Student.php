<?php

class Student extends AppModel {
	public $name = 'Student';

	public $hasMany = array( 
        'CourseMembership'
    );
	public $hasAndBelongsToMany = array('Group');

    public $virtualFields = array(
        'name' => 'Student.first_name || \' \' || Student.last_name'
    );

    public function find_student($bua) {
        return $this->find('first', array('conditions' => array('student_number' => $bua)));
    }

    /*
     * returns true, if student ($sid) has been marked to course ($cid) - else false
     */
    public function hasCourseMembership($sid, $cid) {
        if (!empty($sid) && !empty($cid)) {
            $result = $this->CourseMembership->find('first', array('conditions' => array('student_id' => $sid, 'course_id' => $cid)));
            if (empty($result)) {
                return false;
            } else {
                return true;
            }
        } else {
            return false;
        }
    }

    public function hasCGroup($sid, $cid) {
        if (!empty($sid) && !empty($cid)) {
            $result = $this->find('all', array(
                'conditions' => array(
                    'Student.id' => $sid
                ),
                'contain' => array(
                    'Group' => array(
                        'Group.course_id' => $cid
                    )
                )
            ));
            foreach ($result as $index => $student) {
               if ( empty($student['Group']) ) {
                   unset($result[$index]);
                }
            }
            if (empty($result)) {
                return false;
            } else {
                return true;
            }
        } else {
            return false;
        }
    }
/*
$students = $this->Student->find('all', array(
        'conditons' => array(
            'Student.id' => $sid
        ),
        'contain' => array(
            'Group' => array(
                'Group.course_id' => $cid)
            )
        )
    )
);

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
*/
}
?>
