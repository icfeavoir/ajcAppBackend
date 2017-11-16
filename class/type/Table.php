<?php
	session_start();
	require_once('class/Notification.php');

	abstract class Table{

		static private $db;
		static private $table;
		static private $id_name;

		public function __construct($table, $val){
			self::$table = $table;
			self::$id_name = strtolower($table).'_id';
			self::$db = $GLOBALS['db'];
			//get from DB
			if(!is_array($val) && intval($val) != 0){
				$val = intval($val);
				$req = self::$db->where(self::$id_name, $val)->getOne(self::$table);
				foreach (get_object_vars($this) as $key => $value) {
					$this->$key = $req[$key];
				}
			}else{	// new object in parameter
				foreach (get_object_vars($this) as $key => $value) {
					$this->$key = $val[$key] ?? null;
				}
			}
		}

		public function get(){
			$req = self::$db;
			foreach ($this->values() as $key => $value) {
				$req = self::$db->where($key, $value);
			}
			return $req->get(self::$table);
		}
		public function select(){
			return $this->get();
		}

		public function insert(){
			return self::$db->insert(self::$table, $this->values());
		}

		/*
		* Take the id of the object and assign all the others values as an update
		*/
		public function update(){
			$values = $this->values();
			return self::$db->where(self::$id_name, $values[self::$id_name])->update(self::$table, $values) ? self::$db->count : false;
		}

		public function delete(){
			$req = self::$db;
			foreach ($this->values() as $key => $value) {
				$req = self::$db->where($key, $value);
			}
			return $req->delete(self::$table);
		}

		public function values(){
			$data = array();
			foreach (get_object_vars($this) as $key => $value) {
				if($value != null)
					$data[$key] = $value;
			}
			return $data; 
		}

		public function changeValue($key, $value){
			$this->{$key} = $value;
		}

		public function getValue($key){
			return $this->{$key};
		}
	}	