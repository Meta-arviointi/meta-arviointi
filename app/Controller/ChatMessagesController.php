<?php
	class ChatMessagesController extends AppController {
		public $name = 'ChatMessages';

		public function send() {
			$this->ChatMessage->create();
			$this->ChatMessage->set(array(
				'user_id' => $this->Auth->user('id'),
				'content' => $this->data['msg']
			));
			$this->ChatMessage->save();
			$this->set('json', array('user_id' => $this->Auth->user('id'), 'content' => $this->data['msg']));
			$this->render('/Elements/json');
		}

		public function get_recent($last_id) {
			$msgs = array();
			$messages = $this->ChatMessage->find('all', array(
				'conditions' => array(
					'ChatMessage.id >' => $last_id
				),
				'order' => 'ChatMessage.created ASC'
			));
			foreach($messages as $msg) {
				$msgs[] = array(
					'id' => $msg['ChatMessage']['id'],
					'user' => $msg['User']['first_name'] . ' ' . $msg['User']['last_name'],
					'content' => $msg['ChatMessage']['content'],
					'timestamp' => date('H:i', strtotime($msg['ChatMessage']['created']))
				);
			}
			$this->set('json', $msgs);
			$this->render('/Elements/json');

		}

		public function set_chat_window_state() {
			$this->Session->write('Chat.window_state', $this->data['chat_window_state']);
			$this->set('json', array('chat_window_state' => $this->data['chat_window_state']));
			$this->render('/Elements/json');
		}
	}
?>