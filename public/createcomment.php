<?php
require_once ("../includes/initialize.php");


echo "<pre>";
// return var_dump($_GET['submit']);
// return phpinfo();
echo "</pre>";



$shadow = (int)$_GET['shadow'];





if (!empty($_GET['family']) && !empty($_GET['image'])) {
	$edits = array();

	$family  = $_GET['family'];
	$image = $edits['image_name'] =  $_GET['image'];
	//edits 
	$edits['need_shadow'] = (empty($_GET['shadow']) ? 0 :  (int)$_GET['shadow'] );
	$edits['need_scale'] = (empty($_GET['scale'])   ? 0 :   (int)$_GET['scale']  );
	$edits['need_color'] = (empty($_GET['color'])   ? 0 :   (int)$_GET['color']  );
	$edits['need_other'] = (empty($_GET['other'])   ? 0 :   (int)$_GET['other']  );

	// type
	$type = $_GET['submit'];

	(empty($_GET['comment'])  ? 0 : $edits['comment']  = $_GET['comment']  );


	$user = User::find_by_id($session->user_id);


	$new_comment = UserComment::make($family, $user->full_name(), $edits, $type);
	// return var_dump($new_comment);
	redirect_to($_SERVER['HTTP_REFERER'] . '#' . $_GET['family']);
	



} else {
	die("Failed");
}




?>