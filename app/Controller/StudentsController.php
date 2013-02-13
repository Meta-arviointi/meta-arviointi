<?php
class StudentsController extends AppController {

	public $name = 'Students';

	public function admin_add() {
		if($this->request->is('post')) {
			if(empty($this->data['Student']['tmp_file'])) {
				if($this->Student->save($this->request->data)) {
					$this->redirect(array('action' => 'view', $this->Student->id));
				}
			} else {
				$uploadfile = WWW_ROOT . 'files/' . basename($this->data['Student']['tmp_file']['name']);
				if (move_uploaded_file($this->data['Student']['tmp_file']['tmp_name'], $uploadfile)) {
					$csvfile = fopen($uploadfile, "r");

					fclose($csvfile);
					unlink($uploadfile);
				}
				else {
					// FAILED
				}
			}
		}
	}

	public function admin_edit($id) {
		if($this->request->is('put')) {
			if($this->Student->save($this->request->data)) {
				$this->Session->setFlash('Tallennettu!');
				$this->redirect(array('action' => 'view', $this->Student->id));
			}
			else $this->Session->setFlash('Ei onnistu!');
		}
		else {
			$this->data = $this->Student->findById($id);
		}
	}

	public function index_ajax() {


		// Flag variable to indicate if course is changed
        $course_changed = false;
        $course_id = $this->Session->read('Course.course_id') == null ? 0 : $this->Session->read('Course.course_id');

        /* If course changed, update group_id to Session
         * to match user's group in new course.
         */
        if ( $course_changed ) {
            $this->Course->User->set_new_group($this->Auth->user('id'), $course_id);
        }


		$group_id = 0;
		if(isset($this->request->data['group_id'])) {
			$group_id = $this->request->data['group_id'];
		}

        $order = array('Student.last_name' => 'ASC');

		$params = array(
            'contain' => array(
                'Group' => array(
                    'conditions' =>
                        ($group_id > 0 ? // if
                            array(
                                'Group.course_id' => $course_id,
                                'Group.id' => $group_id
                                )
                            : array('Group.course_id' => $course_id) // else
                        )
                    ,
                    'User' => array(
                        'fields' => 'name'
                    )
                 ),
                'CourseMembership' => array(
                        'conditions' => array('CourseMembership.course_id' => $course_id)
                )
            ),
            'order' => $order
        );
        
		$students = $this->Student->find('all', $params);

        foreach ($students as $index => $student) {
            if ( empty($student['CourseMembership']) ) {
                unset($students[$index]);
            } else if ( $group_id > 0 && empty($student['Group']) ) {
                unset($students[$index]);
            }
        }

        // Loop to fetch all actions related to one student
        foreach ($students as &$student) {
            $student_actions = $this->Course->Exercise->Action->find('all', array(
                    'conditions' => array(
                        'Action.student_id' => $student['Student']['id']
                    ),
                    'contain' => array(
                        'Exercise' => array(
                            'conditions' => array('Exercise.course_id' => $course_id)
                        )
                    )
                )
            );
            // Remove unnecessary depth from arrays that resulted
            // from call to find('all'), and add actions to
            // $student['Action'] -array
            foreach ($student_actions as $action) {
                // Check that action belongs to exercise
                // that belongs to current course
                if ( !empty($action['Exercise']) ) {
                    $student['Action'][] = $action['Action'];
                }
            }
        }

		$this->set('students', $students);
	}

/*	public function delete($id) {
		if($this->Student->delete($id)) {
			$this->Session->setFlash('Poistettu!');
		}
		$this->redirect(array('action' => 'index'));
	}
*/
}
