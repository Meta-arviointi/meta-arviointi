<?php
class StudentsController extends AppController {

	public $name = 'Students';

	public function admin_add() {

        $course_id = $this->Session->read('Course.admin_course');

		if($this->request->is('post')) {
			if(empty($this->data['Student']['tmp_file'])) {

                // single student
				if($this->Student->save($this->request->data)) {
					$this->redirect(array('action' => 'view', $this->Student->id));
				}
			} else {
				$uploadfile = WWW_ROOT . 'files/' . basename($this->data['Student']['tmp_file']['name']);
				if (move_uploaded_file($this->data['Student']['tmp_file']['tmp_name'], $uploadfile)) {
					$csvfile = fopen($uploadfile, "r");

                    $users_system = $this->Course->User->find('list', array('fields' => array('basic_user_account', 'id')));
                    $students_system = $this->Student->find('list', array('fields' => array('student_number', 'id')));
                    $users_csv = array();
                    $users_gid = array();
                    $users = 0;
                    $students = 0;

                    while(($row = fgetcsv($csvfile)) !== false) {
                        // $row[0] = sukunimi;etunimi;ppt;email;assari_ppt
                        $line = explode(';',$row[0]);

                        $student_lname = $line[0];
                        $student_fname = $line[1];
                        $student_bua = $line[2];
                        $student_email = $line[3];
                        $user_bua = $line[4];
                        $sid = 0;
                        $gid = 0;

                        if (in_array($user_bua, $users_csv)) { 
                            // assari on käyty läpi jo
                        } else {
                            // assaria ei ole käyty läpi
                            array_push($users_csv, $user_bua); // lisätään assari $users_csv listaan
                            if (in_array($user_bua, array_keys($users_system))) {
                                // assari on jo lisättynä järjestelmään: ei tarvittavia toimenpiteitä
                            } else {
                                // assaria ei ole järjestelmässä, lisätään DUMMY placeholder assari ja merkitään assarin ppt järjestelmässä olevaksi tulevia tarkistuksia varten
                                $this->Course->User->save(array('basic_user_account' => $user_bua, 'last_name' => 'PLACEHOLDER', 'first_name' => 'PLEASE CHANGE', 'email' => 'INVALID@EMAIL', 'password' => 'default', 'is_admin' => 'false'));
                                $users_system[$user_bua] = $this->Course->User->id;
                            }
                            // lisätään assarille vastuuryhmä ja otetaan ryhmän id talteen
                            $this->Student->Group->save(array('course_id' => $course_id, 'user_id' => $users_system[$user_bua]));
                            $gid = $this->Student->Group->id;
                            $user_group[$user_bua] = $gid;
                        }

                        if (in_array($student_bua, array_keys($students_system))) {
                            // opiskelija on jo järjestelmässä, lisätään opiskelija määriteltyyn ryhmään
                            $this->Student->save(array(
                                'Student' => array(
                                    'id' => $students_system[$student_bua]
                                ),
                                'Group' => array(
                                    'id' => $user_group[$user_bua]
                                )
                            ));
                            $sid = $students_system[$student_bua];
                        } else {
                            // opiskelijaa ei ole järjestelmässä, lisätään opiskelija järjestelmään ja liitetään määriteltyyn ryhmään
                            $this->Student->save(array(
                                'Student' => array(
                                    'student_number' => $student_bua,
                                    'last_name' => $student_lname,
                                    'first_name' => $student_fname,
                                    'email' => $student_email
                                ),
                                'Group' => array(
                                    'id' => $user_group[$user_bua]
                                )
                            ));
                            $sid = $this->Student->id;                                                    
                        }
                        $this->Student->CourseMembership->save(array('course_id' => $course_id, 'student_id' => $sid));
                    }

					fclose($csvfile);
					unlink($uploadfile);
				} else {
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
