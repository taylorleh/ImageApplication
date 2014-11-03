
<?php require_once ("../includes/initialize.php"); ?>
<?php if (!$session->is_logged_in()) { redirect_to("login.php"); } ?>
<?php
$user = User::find_by_id($session->user_id);
$vendors = Vendor::get_all_vendors();
$allComments = UserComment::find_unnaproved_comments();

$allUsers = User::find_all_admins();
echo "<pre>";
echo "</pre>";

?>
<?php include('../includes/layouts/header.php'); ?>

<style type="text/css">
.userform {
	width: 500px;
	display: inline-block;
	font-size: 22px;
	font-family: 'Abel';

}

.userform input {
	border: 2px solid #383838;
	color: #383838; margin-bottom: 1em;

}

.userform input:focus {
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


<table>

	<tr><th>Username</th><th>First name</th><th>role</th></tr>
	<tr>
	<?php foreach ($allUsers as $Ouser): ?>
	<td><?php echo $Ouser->username; ?></td><td><?php echo $Ouser->first_name; ?></td><td><?php echo $Ouser->role; ?></td>
	</tr>
<?php endforeach; ?>

</table>

<a href="new_admin.php">Add New Admin</a>
</div>





