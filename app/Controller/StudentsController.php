<?php
class StudentsController extends AppController {

	public $name = 'Students';

        public function index($course_id = 0) {
        // Flag variable to indicate if course is changed
        $course_changed = false;

        /* Check if course_id is requested in params */
        if ( $course_id > 0 ) {
            // Check if course_id is changed from last request
            if ( $course_id != $this->Session->read('Course.course_id') ) {
                $course_changed = true;
            }
            // Save new course_id to session for further use
            $this->Session->write('Course.course_id', $course_id);
        } else {
            // No course_id in request, take course_id from session
            $course_id = $this->Session->read('Course.course_id') == null ? 0 : $this->Session->read('Course.course_id');
        }

        /* If course changed, update group_id to Session
         * to match user's group in new course.
         */
        if ( $course_changed ) {
            $this->Student->Group->User->set_new_group($this->Auth->user('id'), $course_id);
        }


        $group_id = null;
        // Check if get-request has 'group_id'.
        // If so, set it to session 'User.group_id'
        if (isset($this->request->query['group_id'])) {
            $group_id = $this->request->query['group_id'];
            $this->Session->write('User.group_id', $group_id);
        } else { // No variable in get-request, take group_id from session
            // Read group_id from session, if 'null' group_id = 0.
            $group_id = $this->Session->read('User.group_id') == null ? 0 : $this->Session->read('User.group_id');
        }

        //debug($this->request);
        //debug($this->Session->read());
        $order = array('Student.last_name' => 'ASC');
        $students = $this->Student->find('all', array(
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
            )
        );

        /*
         * Delete students that don't belong to current course.
         * Delete also if student don't belong to selected group.
         * If $group_id = 0, show also students who don't have group.
         * ['Group'] or ['CourseMembership'] is empty array.
         */
        foreach ($students as $index => $student) {
            if ( empty($student['CourseMembership']) ) {
                unset($students[$index]);
            } else if ( $group_id > 0 && empty($student['Group']) ) {
                unset($students[$index]);
            }
        }

        // Loop to fetch all actions related to one student
        foreach ($students as &$student) {
            $this->Student->CourseMembership->Action->contain();
            $student_actions = $this->Student->CourseMembership->Action->find('all', array(
                    'conditions' => array(
                        'Action.course_membership_id' => $student['CourseMembership'][0]['id']
                    )
                )
            );
            // Remove unnecessary depth from arrays that resulted
            // from call to find('all'), and add actions to
            // $student['Action'] -array
            foreach ($student_actions as $action) {
                $student['Action'][] = $action['Action'];
            }
        }


        // Call Group-model to return groups with assistant names
        // in given course ($course_id from Session)
        $results = $this->Student->Group->groups($course_id);

        // Create array with 'Group.id' as key and 'User.name' as value
        // NOTE: 'User.name' is virtual field defined in User-model
        $user_groups = array();
        foreach($results as $result) {
            $user_groups[$result['Group']['id']] = $result['User']['name'];
        }

        // Get all courses user has attended
        // TODO: what if user isadmin?
        $courses = $this->Student->Group->User->user_courses($this->Auth->user('id'));

        $users_courses = array();
        // Iterate over courses and populate array ready to be used in 
        // selection list in courses/index/-view
        // format is Course.id as key and Course.name as value (like find('list'))
        foreach($courses as $course) {
            $users_courses[$course['id']] = $course['name'];
        }

        // Set array to be used in drop-down selection
        $this->set('user_groups', $user_groups);

        $this->set('students', $students);
        // Group_id visible for view
        $this->set('group_id', $group_id);

        $this->set('users_courses', $users_courses);
    }

    /**
     * Redirects to index-method.
     * Function is called from select-list Forms.
     * It takes $course_id from request and passes it to
     * index-method (above).
     */
    public function index_rdr() {
        // Init. variable to make sure it's not null at the end
        $course_id = $this->Session->read('Course.course_id');
        // Check if request is post
        if ( $this->request->is('post') ) {
            $course_id = $this->request->data['course_id'];
        } else if ( $this->request->is('get') ) { // .. or get
            $course_id = $this->request->query['course_id'];
        }

        // Redirect to index() with $course_id
        $this->redirect(array(
                'controller' => 'students',
                'action' => 'index',
                $course_id
            )
        );
    }

	public function admin_add() {

        $course_id = $this->Session->read('Course.admin_course');

		if($this->request->is('post')) {
			if(empty($this->data['Student']['tmp_file'])) {

                // single student
				if($this->Student->save($this->request->data)) {
                    $this->redirect(array('action' => 'index', 'controller' => 'courses', $course_id));
				}
			} else {
				$uploadfile = WWW_ROOT . 'files/' . basename($this->data['Student']['tmp_file']['name']);
				if (move_uploaded_file($this->data['Student']['tmp_file']['tmp_name'], $uploadfile)) {
					$csvfile = fopen($uploadfile, "r");

                    $users_system = $this->Course->User->find('list', array('fields' => array('basic_user_account', 'id')));
//                    $course = $this->Course->findById($course_id);

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

//                        if (in_array($user_bua, array_keys($users_course))) {
                            // assari on jo kurssilla, assarin läpikäynti tarpeetonta
//                            array_push($users_csv, $user_bua);
//                        }

                        if (in_array($user_bua, $users_csv)) { 
                            // assari on käyty läpi jo
                        } else {
                            // assaria ei ole käyty läpi
                            $users++;
                            array_push($users_csv, $user_bua); // lisätään assari $users_csv listaan
                            if (in_array($user_bua, array_keys($users_system))) {
                                // assari on jo lisättynä järjestelmään: ei tarvittavia toimenpiteitä
                            } else {
                                // assaria ei ole järjestelmässä, lisätään DUMMY placeholder assari ja merkitään assarin ppt järjestelmässä olevaksi tulevia tarkistuksia varten
                                $this->Course->User->create();
                                $this->Course->User->save(array('basic_user_account' => $user_bua, 'last_name' => 'PLACEHOLDER', 'first_name' => 'PLEASE CHANGE', 'email' => 'INVALID@EMAIL.FI', 'password' => 'default', 'is_admin' => 'false'));
                                $users_system[$user_bua] = $this->Course->User->id;
                            }
                            // lisätään assari kurssille
                            $crs = $this->Course->findById($course_id);
                            $crs_users = $crs['User'];
                            array_push($crs_users, $users_system[$user_bua]);
                            $this->Course->save(array(
                                'Course' => array(
                                    'id' => $course_id
                                ),
                                'User' => array(
                                    'User' => $crs_users
                                )
                            ));
                            // lisätään assarille vastuuryhmä ja otetaan ryhmän id talteen
                            $this->Student->Group->create();
                            $this->Student->Group->save(array(
                                'course_id' => $course_id, 
                                'user_id' => $users_system[$user_bua]
                                )
                            );
                            $gid = $this->Student->Group->id;
                            $user_group[$user_bua] = $gid;
                        }

                        if (in_array($student_bua, array_keys($students_system))) {
                            // opiskelija on jo järjestelmässä
//                            if (ryhmä)
                            $this->Student->create();
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
                            // opiskelijaa ei ole järjestelmässä, lisätään opiskelija järjestelmään
                            $this->Student->create();
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
                        if ($this->Student->hasCourseMembership($sid, $course_id)) {
                            // opiskelija on jo kurssille merkittynä, ei lisätä uutta merkintää
                        } else {
                            $this->Student->CourseMembership->create();
                            $this->Student->CourseMembership->save(array('course_id' => $course_id, 'student_id' => $sid));
                        }
                        $students++; 
                   }

					fclose($csvfile);
					unlink($uploadfile);
                    $this->Session->setFlash(__('Kurssille lisätty '. $students .' opiskelijaa ja '. $users .' assistenttia.'));
                    $this->redirect(array('action' => 'index', 'controller' => 'courses', $course_id));
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
