<?php
App::uses('CakeEmail', 'Network/Email');

class EmailController extends AppController {

    public function index() {
    	echo "sending";
        $email = new CakeEmail('meta');
        $email->to('joni.hamalainen@uta.fi');
        $email->subject('Test');
        $email->send('Moi! T. Meta-arviointi');
        
    }


}
