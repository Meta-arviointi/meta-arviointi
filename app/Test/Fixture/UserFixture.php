<?php

class UserFixture extends CakeTestFixture {
	/* Import data from production database */
	public $import = array('model' => 'User');
	/*
	public $fields = array(
		'id' => array('type' => 'integer', 'key' => 'primary'),
		'basic_user_account' => array('type' => 'string'),
		'last_name' => array('type' => 'string'),
		'first_name' => array('type' => 'string'),
		'email' => array('type' => 'string'),
		'is_admin' => array('type' => 'string'),//No support for boolean
		'password' => array('type' => 'string'),
	);
	*/
	public $records = array(
		array('id' => 1, 'basic_user_account' => 12345, 
			'last_name' => 'Vastuu', 
			'first_name' => 'Ope',
			'email' => 'vastuu.ope@uta.fi', 
			'is_admin' => 'true',
			'password' => 'testi',
			'last_login' => '2013-02-09 00:28:25+02'
		),
		array('id' => 2,
			'basic_user_account' => 23456, 
			'last_name' => 'Assistentti',
			'first_name' => 'Asseri',
			'email' => 'asseri.assistentti@uta.fi',
			'is_admin' => 'false',
			'password' => 'testi',
			'last_login' => '2012-12-08 22:00:28+02'
		),
		array('id' => 3,
			'basic_user_account' => 34567, 
			'last_name' => 'Assistentti',
			'first_name' => 'Testi',
			'email' => 'testi.assistentti@uta.fi',
			'is_admin' => 'false',
			'password' => 'testi',
			'last_login' => '2013-01-15 09:54:25+02'
		),
		array('id' => 4,
			'basic_user_account' => 45678, 
			'last_name' => 'Auttaja',
			'first_name' => 'Aapo',
			'email' => 'aapo.auttaja@uta.fi',
			'is_admin' => 'false',
			'password' => 'testi',
			'last_login' => '2013-02-01 22:28:25+02'
		)
	);
}

?>
