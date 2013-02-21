<?php
class CoursesController extends AppController {
    public $name = 'Courses';

    /**
     * Index method prints information about course and it's
     * attendees.
     */
    public function admin_index($cid = 0) {

/*
array(
    'conditions' => array('Model.field' => $thisValue), //array of conditions
    'recursive' => 1, //int
    'fields' => array('Model.field1', 'DISTINCT Model.field2'), //array of field names
    'order' => array('Model.created', 'Model.field3 DESC'), //string or array defining order
    'group' => array('Model.field'), //fields to GROUP BY
    'limit' => n, //int
    'page' => n, //int
    'offset' => n, //int
    'callbacks' => true //other possible values are false, 'before', 'after'
)*/
    if ($cid > 0) {
        $this->Session->write('Course.admin_course', $cid);    

        $order = array('Student.last_name' => 'ASC');
        $students = $this->Course->CourseMembership->Student->find('all', array(
            'contain' => array(
                'Group' => array(
                    'conditions' => array(
                        'Group.course_id' => $cid
                    ),
                    'User' => array(
                        'fields' => 'name'
                    )
                ),
                'CourseMembership' => array(
                    'conditions' => array(
                        'CourseMembership.course_id' => $cid
                    )
                )
            ),
            'order' => $order
        ));
    } else {
        $this->Session->write('Course.admin_course', '0');
    }

	$courses = $this->Course->get_courses($cid);

    $course_groups = array();
    $exercise_list = array();
    $users_list = array();
    $groups = array();
    $quitcount = '0';

    foreach($courses as $course) {
        $course_groups[$course['Course']['id']] = $course['Course']['name'];
        $exercise_list = $course['Exercise'];
        $users_list = $course['User'];
    }

    foreach($users_list as $index => $stuff) {
        if ($stuff['is_admin'] == '1') { 
            unset($users_list[$index]);
        }
    }

	if ($cid > 0) {
        foreach($students as $index => $stuff) {
            if (empty($stuff['CourseMembership'])) {
                unset($students[$index]);
            } else {
                foreach($stuff['CourseMembership'] as $coursem) {
                    if ($coursem['quit_id'] > '0') {
                        $quitcount++;
                    }
                }
                foreach($stuff['Group'] as $grouploop) {
                    $uid = $grouploop['user_id'];
                    if (isset($groups[$uid])) {
                        $groups[$uid]++;
                    } else {
                        $groups[$uid] = '1';
                    }
                }
            }
        }
		$this->set('single_course', 'true');
        $this->set('scount', count($students));
        $this->set('acount', count($users_list));
        $this->set('quitcount', $quitcount);
        $this->set('actioncount', 'hardcode 0');
        $this->set('exercise_list', $exercise_list);
        $this->set('users_list', $users_list);
        $this->set('students_list', $students);
        $this->set('groups', $groups);
    }

	$this->set('courses', $courses);
        $this->set('course_groups', $course_groups);
    }




    public function admin_add() {
        if ($this->request->is('post')) {
            $this->Course->create();
            if ($this->Course->save($this->request->data)) {
                $this->Session->setFlash(__('Kurssi lisätty'));
                $this->redirect(array('action' => 'index', $this->Course->id));
            } else {
                $this->Session->setFlash(__('Kurssia ei voitu lisätä. Ole hyvä ja yritä myöhemmin uudestaan.'));
            }
        }
    }

}

