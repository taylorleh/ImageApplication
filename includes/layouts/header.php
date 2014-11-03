<!DOCTYPE html>
<html>
<head>
<title>HomeServer: Intranet</title>
<link rel="stylesheet" type="text/css" href="stylesheets/styles.css">
<link rel="icon" type="image/png" href="images/favicon.ico">
<script type="text/javascript" src="javascript/expand.js"></script>
</head>
<body>
    <div id="header">
    	<h1>Home Intranet</h1>
    	<?php if (isset($_SESSION['user_id'])) { ?>
    		<a href="logout.php">Logout</a>
            <h3><?php echo date('l\, F-d\, Y' , time()) . " |" ; ?></h3>
            <h3>Logged in as: 
            <?php echo $user->username; ?> |
            </h3>
    	<?php } ?>
    	

    </div>