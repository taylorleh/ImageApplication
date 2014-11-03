
<?php require_once ("../includes/initialize.php"); ?>
<?php if (!$session->is_logged_in()) { redirect_to("login.php"); } ?>
<?php
$user = User::find_by_id($session->user_id);
$vendors = Vendor::get_all_vendors();
$allComments = UserComment::find_unnaproved_comments();

?>

<?php  

if (isset($_POST['submit'])) {
	
	$required_fields = array('username', 'password');

	$firstname = $_POST['firstname'];
	$lastname = $_POST['lastname'];
	$username = $_POST['username'];
	$hashed_password = password_encrypt($_POST['password']);
print_r($hashed_password);

	$sql = "INSERT INTO tblUsers (";
	$sql .= " first_name, last_name, username, password, role";
	$sql .= ") values (";
	$sql .= " '{$firstname}', '{$lastname}', '{$username}', '{$hashed_password}', 1 )";

	$result = $database->query($sql);
}

?>

<?php include('../includes/layouts/header.php'); ?>

<style type="text/css">
.userform {
	width: 500px;
	display: inline-block;
	font-size: 22px;
	font-family: 'Abel';

}

input {
	border: 2px solid #383838;
	color: #383838; margin-bottom: 1em;

}

input:focus {
	border: 2px solid blue;
}


table th {
	color: none;
	border: none;
	background: none;
	text-align: left;
	text-decoration: none;
}
table {
	width: 35%;
}

table tr td, table tr {
	border: none;
}


</style>

<div class="wrap" style="width: 100%; text-align:center; padding:3em;">

<form action="new_admin.php" method="POST">
	<label for="firstname">First Name</label>
	<input type="text" value="" id="firstname" name="firstname">

	<label for="lastname">Last Name</label>
	<input type="text" value="" id="lastname" name="lastname">

	<label for="username">Username</label>
	<input name="username" id="username" type="text">
	
	<label for>Password</label>
	<input name="password" id="password" type="password">
	<input type="submit" name="submit" value="Add User">
</form>

<?php







?>

<a href="new_admin.php">Add New Admin</a>
</div>
