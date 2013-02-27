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

    public $validate = array(
        'first_name' => array(
            'rule' => 'alphaNumeric',
            'required' => true,
            'message' => 'Etunimi on pakollinen (vain numeroita tai kirjaimia)'
        ),
        'last_name' => array(
            'rule' => 'alphaNumeric',
            'required' => true,
            'message' => 'Sukunimi on pakollinen (vain numeroita tai kirjaimia)'
        ),
        'email' => array(
            'rule' => 'email',
            'required' => true,
            'message' => 'Sähköposti on puutteellinen'
        ),
        'student_number' => array(
            'rule' => 'alphaNumeric',
            'required' => true,
            'message' => 'Opiskelijanumero on pakollinen'
        )
    );

    public function find_student($bua) {
        return $this->find('first', array('conditions' => array('student_number' => $bua)));
    }

    /**
     * @return CourseMembership if student ($sid) has been marked to course ($cid), else false  
     */
    public function student_course_membership($sid, $cid) {
        if (!empty($sid) && !empty($cid)) {
            // contain only info about CourseMembership
            $this->CourseMembership->contain();
            return $this->CourseMembership->find('first', array(
                'conditions' => array(
                    'student_id' => $sid,
                    'course_id' => $cid
                )
            ));
            
        } else {
            return false;
        }
    }

    /**
     * @return Group if student has group in given course, else false/null
     */
    public function student_group($sid, $cid) {
        if (!empty($sid) && !empty($cid)) {
            $result = $this->find('first', array(
                'conditions' => array(
                    'Student.id' => $sid
                ),
                'contain' => array(
                    'Group' => array(
                        'conditions' => array(
                            'Group.course_id' => $cid
                        )
                    )
                )
            ));
            return $result['Group'];
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
