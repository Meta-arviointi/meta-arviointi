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
        $course_id = intval($course_id); // from session, datatype is char

        if( $this->request->is('post') ) {
            if ( empty($this->data['Student']['tmp_file']) ) {
                // single student
                if ( $this->Student->save($this->request->data) ) {
                    $this->redirect(array('action' => 'index', 'controller' => 'courses', $course_id));
                } else {
                    $this->Session->setFlash('Opiskelijan tallennus ei onnistunut');
                    $this->redirect($this->referer());
                }
            } else { // CSV IMPORT
                App::uses('File', 'Utility');

                $uploadfile = WWW_ROOT . 'files/' . basename($this->data['Student']['tmp_file']['name']);
                if ( move_uploaded_file($this->data['Student']['tmp_file']['tmp_name'], $uploadfile) ) {
                    $csvfile = fopen($uploadfile, "r"); // OPEN CSV

                    // Add timestamp to import logfile
                    $log_filename = 'Meta_csv_import_'. date('dmy-His').'.txt'; 
                    // create file
                    $import_log = new File(LOGS. DS .$log_filename, true, 0640);

                    // Get users from system
                    $users_system = $this->Course->User->find('list', array(
                        'fields' => array('basic_user_account', 'id')
                        )
                    );
                    // Get students from system
                    $students_system = $this->Student->find('list', array(
                        'fields' => array('student_number', 'id')
                        )
                    );
 
                    $users_csv = array();
                    $users_gid = array();
                    $users = 0;
                    $new_students_count = 0;
                    $new_groups = 0;
                    $curr_row = 0;
                    $has_group = false; // if student gets linked to group
                    $log_row_count = 0; // rows in log

                    $new_students = array(); // new students created
                    $old_students = array(); // old students already in system
                    $students_course = array(); // students added to course
                    // New students with group
                    $students_with_group = array();
                    // New students without group
                    $students_wo_group = array();
                    // created user groups
                    $new_user_groups = array();
                    // unknown users
                    $unknown_users = array();
                    // users linked to course
                    $added_users = array();
                    while(($row = fgetcsv($csvfile)) !== false) {
                        $curr_row++; // increment current row
                        $row_errors = array(); // all errors in current row

                        // $row[0] = sukunimi;etunimi;opnumero;email;assari_ppt
                        $line = explode(';',$row[0]);
                        // Check if we have all needed information
                        // and validate                                                 
                        if ( count($line) >= 4 && Validation::alphaNumeric($line[0]) &&
                            Validation::alphaNumeric($line[1]) &&
                            Validation::numeric($line[2]) &&
                            Validation::email($line[3]) ) {
                            // Set information
                            $student_lname = $line[0];
                            $student_fname = $line[1];
                            $student_number = $line[2];
                            $student_email = $line[3];
                            // User identifier can be empty
                            $user_bua = !empty($line[4]) ? $line[4] : null;

                            $sid = 0;
                            $gid = 0;
                            $uid = 0;
                            $user_group = array();
                            
                            // Check if user identifier is available
                            if ( $user_bua ) {
                                // STEP 1: CHECK USER STATUS
                                $uid = isset($users_system[$user_bua]) ? $users_system[$user_bua] : null;
                                $gid = isset($user_group[$user_bua]) ? $user_group[$user_bua] : null;
                                
                                // Check if user in system
                                if ( $uid ) {
                                    // User is in the system, good!
                                                                        
                                    // check if user already linked to course
                                    if ( !$this->Student->Group->User->user_in_course($uid, $course_id) ) {
                                        // not in course, add
                                        $this->Course->contain('User');
                                        $crs = $this->Course->findById($course_id);
                                        $crs_users = isset($crs['User']) ? $crs['User'] : array();
                                        array_push($crs_users, $users_system[$user_bua]);
                                        $this->Course->save(array(
                                            'Course' => array(
                                                'id' => $course_id
                                            ),
                                            'User' => array(
                                                'User' => $crs_users
                                            )
                                        ));
                                        $users++; // increment added users to course
                                        $this->Student->Group->User->contain();
                                        $linked_user = $this->Student->Group->User->findById($uid);
                                        $added_users[] =  $linked_user;
                                    } // else user already assigned to course

                                    // check if we already know user's group
                                    if ( empty($gid) ) {
                                        // user's group is not known

                                        // Get user's group in course. False if no group set.
                                        $group = $this->Student->Group->User->user_group($uid, $course_id);
                                        if ( !$group ) { 
                                            // User doesn't have group in course ($group = false)
                                            // Create group
                                            $this->Student->Group->create();
                                            $this->Student->Group->save(array(
                                                'course_id' => $course_id, 
                                                'user_id' => $uid
                                                )
                                            );
                                            // set $gid
                                            $gid = $this->Student->Group->id;
                                            $new_groups++;

                                            $this->Student->Group->User->contain();
                                            $g_user = $this->Student->Group->User->findById($uid);
                                            $new_user_groups[] =  $g_user;                                            
                                        } else { // user has a group already
                                            // Take existing Group's ID
                                            $gid = $group['Group']['id'];
                                        }
                                        
                                        // add group id linkage to $user_bua
                                        $user_group[$user_bua] = $gid;
                                    } // else = we know $gid from earlier rows

                                    
                                } else {
                                   //User not in system
                                    $row_errors[] = "Assistentti ($user_bua) ei järjestelmässä. Lisää assistentti käsin." .
                                        " Lisätään opiskelijaa ($student_number)...";
                                    $unknown_users[] = $user_bua;
                                } 
                                
                            } else {
                                // else NO USER INFORMATION IN ROW
                                
                            }

                            // At this point, IF $user_bua was found from the csv-line:
                            // we know: 1) user id ($uid), 2) user's group id ($gid), and we can
                            // add student to system, course and group.
                            // If $user_bua was not in the csv-line, we will assign the 
                            // student to system and course, but not into group.

                            // STEP 2: CHECK STUDENT STATUS

                            $sid = isset($students_system[$student_number]) ? 
                                $students_system[$student_number] : null;
                            if ( $sid ) {

                                // Student already saved in system
                                $old_students[] = $this->Student->findById($sid);
                                $row_errors[] = "Opiskelija ($student_number) oli jo järjestelmässä.";
                                // Check if student has a group assigned in course
                                $group = $this->Student->student_group($sid, $course_id);
                                // Also check that $uid is assigned.
                                // If not, don't link student to course
                                if ( !$group && $uid ) { 
                                    // student has no group. user id is known. create group linkage
                                    $this->Student->Group->contain('Student');
                                    $group = $this->Student->Group->findById($gid);
                                    $group_students = isset($group['Student']) ? $group['Student'] : array();
                                    array_push($group_students, $sid);
                                    //debug($group_students);
                                    $options = array(
                                        'Group' => array(
                                            'id' => $gid
                                        ),
                                        'Student' => array(
                                            'Student' => $group_students
                                        )
                                    );
                                    //debug($options);
                                    $this->Student->Group->save($options);
                                    $has_group = true;
                                    /*debug($data);
                                    $group = $this->Student->Group->findById($gid);
                                    $group_students = $group['Student'];
                                    debug($group_students);
                                    exit;*/
                                } else {
                                    if ( !$uid ) {
                                        // We end up here, if $uid is not known (we can't link
                                        // student to particular group)
                                        $row_errors[] = "Tuntematon assistentti. Opiskelijalle ei voida määritellä vastuuryhmää.";
                                    } else {
                                        // student is already linked to group in course
                                        // (but we don't know if it's group supervised by $uid,
                                        // or somebody else. 
                                        $row_errors[] = "Opiskelija ($student_number) on jo liitetty johonkin vastuuryhmään.";
                                    }
                                }
                                
                            } else { // STUDENT IS NEW
                                // Create student, and add linkage to group
                                
                                if ( !empty($uid) ) {
                                    $this->Student->create();
                                    // $uid is known, then we know also $gid
                                    $rdata = $this->Student->save(array(
                                        'Student' => array(
                                            'student_number' => $student_number,
                                            'last_name' => $student_lname,
                                            'first_name' => $student_fname,
                                            'email' => $student_email
                                        ),
                                        'Group' => array(
                                            'id' => $gid
                                        )
                                    ));
                                    if ( $rdata ) {
                                        //$new_students[] =  $rdata;
                                        $new_students_count++;
                                        $has_group = true;
                                    }
                                } else { // $uid not known, create student w/o group
                                    $this->Student->create();
                                    $rdata = $this->Student->save(array(
                                        'Student' => array(
                                            'student_number' => $student_number,
                                            'last_name' => $student_lname,
                                            'first_name' => $student_fname,
                                            'email' => $student_email
                                            )
                                        )
                                    );
                                    if ( $rdata ) {
                                        //$new_students[] = $rdata;
                                        $new_students_count++;
                                        $has_group = false;
                                    }
                                }
                                
                                // get just saved student's id
                                $sid = $this->Student->id;

                            }

                            if ( $sid ) {
                                // Check if student linked to current course
                                if (!$this->Student->student_course_membership($sid, $course_id)) {
                                    // Student not linked to current course
                                    // create CourseMembership
                                    $this->Student->CourseMembership->create();
                                    $this->Student->CourseMembership->save(array(
                                        'course_id' => $course_id,
                                        'student_id' => $sid
                                        )
                                    );
                                    // add student to array of added students
                                    $this->Student->contain();
                                    $student = $this->Student->findById($sid);
                                    $students_course[] = $student;
                                    if ( $has_group ) {
                                        $students_with_group[] = $student;

                                    } else {
                                        $row_errors[] = "Opiskelija ($student_number) lisätty kurssille ilman vastuuryhmää.";
                                        $students_wo_group[] = $student;
                                    }
                                } else {
                                    $row_errors[] = "Opiskelija ($student_number) oli jo kurssilla.";
                                }

                            } else {
                                $row_errors[] = "Opiskelijaa ($student_number) ei voitu luoda.".
                                " Tarkista tiedot (opiskelijanumero? e-mailin formaatti?).\n".
                                '       Oliko opiskelija duplikaatti?';
                            }


                        } else {
                            $row_errors[] = 'Rivi väärän muotoinen. Oikea muoto: sukunimi;etunimi;opnumero;email;assari_ppt'.
                                "\n". '       Tarkista myös tiedon muoto (esim sähköpostin formaatti, opiskelijanumero).';
                        }

                        if ( count($row_errors) > 0 ) {
                            foreach($row_errors as $error) {
                                $log_row_count++;
                                $row_string = "Rivi $curr_row: " . $error . "\n";
                                $import_log->append($row_string);
                            }
                        }
                    }

                    fclose($csvfile);
                    unlink($uploadfile);
                    $errors_log = $import_log->read();
                    $import_log->close();

                    // Set information for result-page
                    $this->set('course_id', $course_id); // course id for link
                    $this->set('old_students', $old_students);
                    //$this->set('new_students', $new_students);
                    //$this->set('students_with_group', $students_with_group);
                    $this->set('students_wo_group',$students_wo_group);
                    $this->set('students_course', $students_course); // added CourseMemberships
                    $this->set('added_users', $added_users); // linked users to course
                    $this->set('unknown_users', $unknown_users);
                    $this->set('errors_log', $errors_log);
                    $this->set('new_students_count', $new_students_count);
                    $this->set('log_row_count', $log_row_count);

                    $this->Session->setFlash(__('Kurssille lisätty '. count($students_course) .' opiskelijaa.'));
                    $this->render('/Courses/admin_csv_results');
                    //$this->redirect(array('action' => 'index', 'controller' => 'courses', $course_id));
                } else {
                    // FAILED to move csv file
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
        
		$this->set('students', $students);
	}

/*  public function delete($id) {
        if($this->Student->delete($id)) {
            $this->Session->setFlash('Poistettu!');
        }
        $this->redirect(array('action' => 'index'));
    }
*/
}
