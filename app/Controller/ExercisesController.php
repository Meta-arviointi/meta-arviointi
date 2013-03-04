<?php
class ExercisesController extends AppController {
    public $name = 'Exercises';

    public function add() {
        if ($this->request->is('post')) {
            $this->Exercise->create();
            if ($this->Exercise->save($this->request->data)) {
                $this->Session->setFlash(__('Harjoitus lisätty'));
                $this->redirect(array('controller' => 'courses', 'action' => 'view', $this->Session->read('Course.course_id')));
            } else {
                $this->Session->setFlash(__('Harjoitusta ei voitu lisätä. Ole hyvä ja yritä myöhemmin uudestaan.'));
            }
        } else {
            $course_id = $this->Session->read('Course.course_id');
            $this->Exercise->contain();
            $last = $this->Exercise->find('first', array(
                    'conditions' => array(
                        'Exercise.course_id' => $course_id
                    ),
                    'order' => 'Exercise.exercise_number DESC'
                )
            );
            $last_number = 0;
            if ( !empty($last) ) {
                $last_number = $last['Exercise']['exercise_number'];
            }
            $this->set('next_number', $last_number + 1);
        }
    }

    public function edit_many() {
        if ( $this->request->is('put') || $this->request->is('post') ) {
            if ( $this->Exercise->saveMany($this->request->data['Exercise']) ) {
                $this->Session->setFlash('Harjoitukset tallennettu');
                $this->redirect(array(
                    'controller' => 'courses',
                    'action' => 'view',
                    $this->Session->read('Course.course_id')
                ));
            } else {
                $this->Session->setFlash('Harjoitusten tallennus epäonnistui');
                $this->redirect(array(
                    'controller' => 'courses',
                    'action' => 'view',
                    $this->Session->read('Course.course_id')
                ));
            }
        }
    }
}