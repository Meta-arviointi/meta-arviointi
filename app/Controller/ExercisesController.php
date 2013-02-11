<?php
class ExercisesController extends AppController {
    public $name = 'Exercises';

    public function admin_add() {
        if ($this->request->is('post')) {
            $this->Exercise->create();
            if ($this->Exercise->save($this->request->data)) {
                $this->Session->setFlash(__('Harjoitus lisätty'));
//                $this->redirect(array('controller' => 'Courses', 'action' => 'admin_index', $this->Course->id));
            } else {
                $this->Session->setFlash(__('Harjoitusta ei voitu lisätä. Ole hyvä ja yritä myöhemmin uudestaan.'));
            }
        }
    }
}

