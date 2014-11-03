<?php require_once ("../includes/initialize.php");  ?>

<?
    if(isset($_POST['submit'])) {
        // $user = User::find_by_id($session->user_id);

        $username = trim($_POST['username']);
        $password = trim($_POST['password']);

        $found_user = User::authenticate($username, $password);
        // $found_user = attempt_login($username, $password);
        // var_dump($found_user);

        if ($found_user) {
            $session->login($found_user);
            redirect_to("index.php");
        } else {
            echo "combo failed";
        }

        
    } else {
        $username = "";
        $password = "";

    }
?>
<?php include ("../includes/layouts/header.php"); ?>

 <div id="main">        
    <div id="page">  
        <form class="login" action="login.php" method="post">
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
