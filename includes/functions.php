<?php

	function confirm_query($result_set)
	{
		if(!$result_set) {
			die("Database query failed.");
		}
	}


	function redirect_to($new_location) {
		header("Location: " . $new_location);
		exit;
	}



	function find_distinct_web_groups_by_vendor($vendor) {
		global $connection;

		$query = "SELECT DISTINCT web_group_no ";
		$query .= "FROM tblItems ";
		$query .= "WHERE vendor_no = '$vendor' ";
		$vendor_groups = mysqli_query($connection, $query);
		confirm_query($vendor_groups);

		return $vendor_groups;
	}



	function find_groups_by_vendor($string) {
		global $connection;

		$query = "SELECT * ";
		$query .= "FROM tblItems ";
		$query .= "WHERE vendor_no = '$string' ";
		$web_group_set = mysqli_query($connection, $query);
		confirm_query($web_group_set);
		return $web_group_set;
	}


	// If a brand is selected then this will return
	// a global variable of $current_brand on both
	// view_grioup and index.php
	// **** DO NOT DROP INTO SQL***
	function find_selected_brand() {
		global $connection;
		global $current_brand;

		if (isset($_GET['brand'])) {
			$current_brand = $_GET['brand'];
		} else {
			$current_brand = null;
		}
	}



	function find_image_names($web_group) {
		global $connection;

		
		$query 	= "SELECT * ";
		$query .= "FROM tblImageInfo ";
		$query .= "INNER JOIN tblItems ON tblImageInfo.product_family = tblItems.product_family "; 
		$query .= "WHERE tblItems.web_group_no = '$web_group' ";
		$image_set = mysqli_query($connection, $query);
		confirm_query($image_set);

		return $image_set;
	}


	// queries the database for all vendor and returns
	// the set in  preformatted HTML
	function navigation($selected_brand) {
		$output = "<ul class=\"vendor\">";
		$vendor_set = find_all_vendors();
		while ($brand = mysqli_fetch_assoc($vendor_set)) {
			$output .= "<li";
			if($selected_brand && $selected_brand == $brand['vendor_no']) {
				$output .= " class=\"selected\"";
			}
			$output .=  ">";
			$output .= "<a href=\"index.php?brand=";
			$output .= urlencode($brand['vendor_no']);
			$output .= "\">"; 
			$output .= ucfirst(strtolower(htmlentities($brand['vendor_no'])));
			$output .= "</a>";
			$output .= "</li>";
		}

		mysqli_free_result($vendor_set);
		$output .= "</ul>";
		return $output;	
	}



	function find_admin_by_username($username) {
		global $database;

		print_r($username);

		$query  = "SELECT * ";
		$query .= "FROM tblUsers ";
		$query .= "WHERE username = '$username' ";
		$query .= "LIMIT 1";
		$admin_set = $database->query($query);
		confirm_query($admin_set);


		if($admin = mysqli_fetch_assoc($admin_set)) {
			return $admin;
		} else {
			return null;
		}

	}



	function check_admin($username, $password) {
		$admin = find_admin_by_username($username);

		if($admin) {
			if($admin['password'] == $password) {
				return $admin;
			} else {
				return false;
			}

		} else {
			return false;
		}
	}


	//NEW FUNCTION ADDED 4-17


	// reformats datetime type from mysql database
	// to be used on view_group as user comments
	function datetime_to_text($datetime='')
	{	

		$unixdatetime = strtotime($datetime);
		return strftime("%b-%d" . ' at ' . '%l:%M', $unixdatetime);
	}


	function datetime_totext_for_author($date='')
	{
		$unixtime = strtotime($date);
		return strftime("%m/%e", $unixtime);
	}


	function password_encrypt($password)
	{
		$hash_format = "$2y$10$";

		$salt_length = 22;
		$salt = generate_salt($salt_length);

		$format_and_salt = $hash_format . $salt;

		$hash = crypt($password, $format_and_salt);

		return $hash;

	}

	function generate_salt($length)
	{
		
		$unique_random_string = md5(uniqid(mt_rand(), true));

		$base64_string = base64_encode($unique_random_string);

		$modified_base64_string = str_replace('+', '.', $base64_string);

		$salt = substr($modified_base64_string, 0, $length);

		return $salt;


	}

	function password_check($password, $exisiting_hash)
	{
		$hash = crypt($password, $exisiting_hash);

		print_r($hash);

		if ($hash === $exisiting_hash) {
			return true;	
		} else {
			return false;
		}
	}

	function attempt_login($username, $password)
	{
		// print_r($username);
		$admin = find_admin_by_username($username);

		print_r($admin);

		if ($admin) {
			// print_r($password, $admin['password']);
			if (password_check($password, $admin['password'])) {
				
				return $admin;
			} else {
				return false;
			}

		} else {
			return false;
		}

	}

?>





















