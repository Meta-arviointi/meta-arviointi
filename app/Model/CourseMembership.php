<?php

class CourseMembership extends AppModel {
    public $name = 'CourseMembership';
    
    public $belongsTo = array('Course', 'Student', 'Group');
    public $hasMany = array(
        'Action' => array(
            'dependent' => true
        ),
        'EmailMessage' => array(
            'dependent' => true,
            'order' => 'EmailMessage.sent_time DESC',
        )
    );

    public function set_group($cmid, $gid) {
        if ( !empty($cmid) && !empty($gid) ) {
            $this->id = $cmid;
            if ( $this->exists() ) {
                return $this->saveField('group_id', $gid);
            } else {
                return false;
            }
        }
    }
    
    public function unset_group($cmid) {
        if ( !empty($cmid) ) {
            $this->id = $cmid;
            if ( $this->exists() ) {
                return $this->saveField('group_id', null);
            } else {
                return false;
            }
        }
    }

    /**
     * @return Group if student has group in given course, else false/null
     */
    public function student_group($sid, $cid) {
        if (!empty($sid) && !empty($cid)) {
            $this->contain('Group');
            $cm = $this->findByStudentId($sid);
            if ( !empty($cm['Group']['id']) ) {
                return $cm['Group']; // return group
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

}
?>