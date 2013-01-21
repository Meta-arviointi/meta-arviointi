<?php
class StudentsController extends AppController {

	public $name = 'Students';

	public function add() {
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

	public function edit($id) {
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

	public function delete($id) {
		if($this->Student->delete($id)) {
			$this->Session->setFlash('Poistettu!');
		}
		$this->redirect(array('action' => 'index'));
	}


}
