<?php require_once ("../includes/initialize.php");   ?>
<?php
$user = User::find_by_id($session->user_id);

?>

<?php

if (isset($_POST['submit'])) {
	

	$username2 = $_POST['username'];
	$password2 = $_POST['password'];

	$found_admin = attempt_login($username2, $password2);


	if($found_admin) {

		echo "correct";

        redirect_to('index.php');

	} else {
		echo "failed login";
	}


}

?>
<?php include ("../includes/layouts/header.php"); ?>

 <div id="main">        
    <div id="page">  
        <form class="login" action="login2.php" method="post">
            <table>
                <tr>
                    <td>Username:</td>
                    <td><input name="username" type="text" value="" /></td>
                </tr>
                <tr>
                    <td>Password:</td>
                    <td><input name="password" type="password" value="" ></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input name="submit" value="Login" type="submit">
                    </td>
                </tr>

            </table>
             
             
            
        </form>
        

    </div>
</div>


<?php include ("../includes/layouts/loginfooter.php"); ?>
