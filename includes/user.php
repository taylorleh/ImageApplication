<?php
require_once (LIB_PATH.DS.'database.php');
/**
* 
*/
class User extends DatabaseObject
{
	protected static $table_name = "tblUsers";
	public $id;
	public $username;
	public $password;
	public $first_name;
	public $last_name;
	public $role;

	public function full_name()
	{
		if (isset($this->first_name)  && isset($this->last_name)) {
			return $this->first_name . " " . $this->last_name;
		} else {
			return "";
		}
	}

	public static function authenticate($username="", $password="")
	{
		global $database;
		$username = $database->escape_value($username);
		$password = $database->escape_value($password);

		$sql  = "SELECT * FROM tblUsers ";
		$sql .= "WHERE username = '{$username}' ";
		$sql .= "AND password = '{$password}' ";
		$sql .= "LIMIT 1";

		$result_array = self::find_by_sql($sql);
		return !empty($result_array) ? array_shift($result_array) : FALSE;
	}


	public function user_comments()
	{	
		global $database;

		if (isset($this->id) && isset($this->last_name) ) {
			$sql = "SELECT * FROM tblUserEdits WHERE user_id = {$this->id} ";
			$result_array = self::find_by_sql($sql);

			return !empty($result_array) ? array_shift($result_array) : FALSE;
		}
	}

	static function find_all_admins()
	{
		$sql = "SELECT * FROM tblUsers";

		return $admin_set = self::find_by_sql($sql);


	}




}




?>