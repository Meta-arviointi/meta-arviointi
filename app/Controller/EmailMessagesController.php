<?php
	class EmailMessagesController extends AppController {
		public $name = 'EmailMessages';

		public function mark_as_read($id) {
			$this->EmailMessage->read(null, $id);
			$this->EmailMessage->set('read_time', date('Y-m-d H:i:sO'));
			$this->EmailMessage->save();
			$this->redirect($this->referer());
		}
	}
?>