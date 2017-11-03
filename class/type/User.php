<?php
	require_once('Table.php');

	class User extends Table{

		public $user_id;
		public $firstName;
		public $lastName;
		public $born;
		public $created;

		public function __construct($val = []){
			parent::__construct('User', $val);
		}
	}	