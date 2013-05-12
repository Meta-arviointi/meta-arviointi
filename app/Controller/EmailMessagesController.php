<?php
	class EmailMessagesController extends AppController {
		public $name = 'EmailMessages';

		public function mark_as_read($id) {
			$this->EmailMessage->read(null, $id);
			$this->EmailMessage->set('read_time', date('Y-m-d H:i:sO'));
			$this->EmailMessage->save();
			$this->redirect($this->referer());
		}

		public function send() {
			if($this->request->is('post')) {
				$cm_id = $this->request->data['EmailMessage']['course_membership_id'];
				$membership = $this->EmailMessage->CourseMembership->findById($cm_id);
				$this->request->data['EmailMessage']['receiver'] = $membership['Student']['email'];

				// Append user name to the message
				$this->request->data["EmailMessage"]["content"] .= "\r\n\r\n" . __("Ystävällisin terveisin") . ",\r\n" . $this->Session->read('Auth.User.name');

				if($this->EmailMessage->save($this->request->data)) {
					$message = array(
						"to" => $this->request->data["EmailMessage"]["receiver"],
						"subject" => $this->request->data["EmailMessage"]["subject"],
						"body" => $this->request->data["EmailMessage"]["content"]
					);
					if($this->_send_via_gateway($message)) {
						$this->EmailMessage->set('sent_time', date('Y-m-d H:i:sO'));
						$this->Session->setFlash(__('Sähköpostiviesti lähetetty.'));
					}
					else {
						$this->Session->setFlash(__('Sähköpostiviestin lähettäminen ei onnistunut. Yritä myöhemmin uudelleen.'));
					}
					$this->EmailMessage->set('read_time', date('Y-m-d H:i:sO'));
					$this->EmailMessage->save();
				}
				$this->redirect(array('admin' => false, 'controller' => 'course_memberships', 'action' => 'view', $cm_id));
			}
			$this->redirect('/');
		}


		public function send_many() {
			if($this->request->is('post')) {
				foreach($this->request->data['EmailMessage'] as $em) {

					$membership = $this->EmailMessage->CourseMembership->findById($em['course_membership_id']);
					$em['receiver'] = $membership['Student']['email'];

					// Append user name to the message
					$em["content"] .= "\r\n\r\n" . __("Ystävällisin terveisin") . ",\r\n" . $this->Session->read('Auth.User.name');

					$this->EmailMessage->create();
					if($this->EmailMessage->save($em)) {
						$message = array(
							"to" => $em["receiver"],
							"subject" => $em["subject"],
							"body" => $em["content"]
						);
						if($this->_send_via_gateway($message)) {
							$this->EmailMessage->set('sent_time', date('Y-m-d H:i:sO'));
							$this->EmailMessage->set('read_time', date('Y-m-d H:i:sO'));
							$this->EmailMessage->save();
						}
					}
				}
				$this->Session->setFlash(__('Viestit lähetetty'));
			}
			$this->redirect($this->referer());
		}

		public function send_ajax() {
			$msgs = array_values($this->request->data['EmailMessage']);
			$em = $msgs[0];

			$membership = $this->EmailMessage->CourseMembership->findById($em['course_membership_id']);
			$em['receiver'] = $membership['Student']['email'];

			// Append user name to the message
			$em["content"] .= "\r\n\r\n" . __("Ystävällisin terveisin") . ",\r\n" . $this->Session->read('Auth.User.name');

			$this->EmailMessage->create();
			if($this->EmailMessage->save($em)) {
				$message = array(
					"to" => $em["receiver"],
					"subject" => $em["subject"],
					"body" => $em["content"]
				);
				if($this->_send_via_gateway($message)) {
					$this->EmailMessage->set('sent_time', date('Y-m-d H:i:sO'));
					$this->EmailMessage->set('read_time', date('Y-m-d H:i:sO'));
					$this->EmailMessage->save();
					echo 'SUCCESS';
					die();
				}
				else {
					echo 'FAIL';
					die();
				}
			}

		}


		public function send_pw($email, $password, $name) {
			App::uses('CakeEmail', 'Network/Email');
			$Email = new CakeEmail();
			$Email->config(array(
					'host' => 'smtp.uta.fi',
					'port' => 25,
					'transport' => 'Smtp',
					'from' => array('meta-arviointi@sis.uta.fi' => 'Meta-arviointi'),
					'to' => $email,
					'subject' => __('Meta-arviointi - Unohtunut salasana'),
					'template' => 'forgotten_password'
				)
			);
			// set vars
			$Email->viewVars(array('name' => $name, 'password' => $password));
			$Email->send();
		}

		private function _send_via_gateway($message) {
			$http_status = null;
			if(function_exists('curl_init')) {
				$json_url = 'https://meta-arviointi.sis.uta.fi/email_json.php';
				$ch = curl_init($json_url);
				$options = array(
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_BINARYTRANSFER => true,
					CURLOPT_SSL_VERIFYPEER => false,
					CURLOPT_HTTPHEADER => array("Content-type: multipart/form-data"),
					//CURLOPT_HTTPHEADER => array('Content-type: application/json') ,
					CURLOPT_POST => true,
					CURLOPT_POSTFIELDS => $this->_flatten_GP_array(array("secret_token" => "m374arvioint1", "message" => $message))
				);
				curl_setopt_array($ch, $options);
				$results = curl_exec($ch);
				$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
				$results = json_decode($results);
				//print_r($results); die();
			}
			return ($http_status == 200);
		}

		private function _flatten_GP_array(array $var,$prefix = false){
			$return = array();
			foreach($var as $idx => $value){
				if(is_scalar($value)){
					if($prefix){
						$return[$prefix."[".$idx."]"] = $value;
					} else {
						$return[$idx] = $value;
					}
				} else {
					$return = array_merge($return,$this->_flatten_GP_array($value,$prefix ? $prefix."[".$idx."]" : $idx));
				}
			}
			return $return;
		}
	}
?>