<?php
	// $dbhost = "localhost";
	// $username = "taylorlehman";
	// $password = "wingets1030";
	// $dbname = "intranet";

	// $dbhost = "localhost";
	// $username = "linda";
	// $password = "1224klog";
	// $dbname = "intranet";

	// $connection = mysqli_connect($dbhost, $username, $password, $dbname);
	



	// if(mysqli_connect_errno()) {
	// 	die("Database connection failed:" . mysqli_connect_error() . "(" . mysqli_connect_errno() . ")"
	// 		);
	// }

require_once (LIB_PATH.DS.'config.php');
/**
* MySQL Database Class
*/

class MySQLDatabase
{
	private $connection;
	public $last_query;

	function __construct() {
		$this->open_connection();
	}

	public function open_connection()
	{
		$this->connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	}

	public function close_connection()
	{
		if (isset($this->connection)) {
			mysqli_close($this->connection);
		}
	}

	public function escape_value($value='')
	{
		return mysqli_real_escape_string($this->connection, $value);
	}

	public function query($sql)
	{
		$this->last_query = $sql;
		$result = mysqli_query($this->connection, $sql);
		$this->confirm_query($result);
		return $result;
	}

	private function confirm_query($result)
	{
		if (!$result) {
			$output = "Database query failed: " . "(" . mysqli_errno($this->connection) . ")";
			$output = "Last SQL query: " . $this->last_query;
			die($output);
		}
	}
}

$database = new MySQLDatabase();

	
?>