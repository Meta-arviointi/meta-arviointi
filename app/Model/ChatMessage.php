<?php

class ChatMessage extends AppModel {
	public $name = 'ChatMessage';
	public $belongsTo = array('User');
}

?>