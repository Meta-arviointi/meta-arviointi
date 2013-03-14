<?php

class Student extends AppModel {
	public $name = 'Student';

	public $hasMany = array( 
        'CourseMembership'
    );

    public $virtualFields = array(
        'name' => 'Student.first_name || \' \' || Student.last_name'
    );

    public $validate = array(
        'first_name' => array(
            'rule' => '/^[a-zA-ZÄäÖöÅå0-9_\-]+$/i',
            'required' => true,
            'message' => 'Etunimi on pakollinen'
        ),
        'last_name' => array(
            'rule' => '/^[a-zA-ZÄäÖöÅå0-9_\-]+$/i',
            'required' => true,
            'message' => 'Sukunimi on pakollinen'
        ),
        'email' => array(
            'rule' => '/^.+@.+$/i',
            'required' => true,
            'message' => 'Sähköposti on puutteellinen'
        ),
        'student_number' => array(
            'alphaNumeric' => array(
                'rule' => 'alphaNumeric',
                'required' => true,
                'message' => 'Opiskelijanumero on pakollinen'
            ),
            'isUnique' => array(
                'rule' => 'isUnique',
                'required' => true,
                'message' => 'Opiskelijanumero on jo käytössä'
            )
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

}
?>
