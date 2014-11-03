<?php

/**
* Do not store database objects in the session
* these are large objects and can become stale
*/
class Session
{
	private $logged_in = false;
	public $user_id;

	function __construct()
	{
		session_start();
		$this->check_login();
	}

	public function is_logged_in()
	{
		return $this->logged_in;
	}

	private function check_login()
	{
		if (isset($_SESSION['user_id'])) {
			$this->user_id = $_SESSION['user_id'];
			$this->logged_in = true;
		} else {
			unset($this->user_id);
			$this->logged_in = false;
		}
	}

	public function login($user)
	{
		if ($user) {
			$this->user_id = $_SESSION['user_id'] = $user->id;
			$this->logged_in = TRUE;
		}
		
	}

	public function logout()
	{
		session_unset($_SESSION['user_id']);
		session_unset($this->user_id);
		$this->logged_in = FALSE;
	}
}


$session = new Session();

?>