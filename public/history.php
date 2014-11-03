
<?php require_once ("../includes/initialize.php"); ?>
<?php if (!$session->is_logged_in()) { redirect_to("login.php"); } ?>

<?php
$user = User::find_by_id($session->user_id);
$vendors = Vendor::get_all_vendors();
$image = (isset($_GET['approved']) ?  UserComment::find_comments_on( $_GET['approved']) : NULL );

?>
<?php include('../includes/layouts/header.php'); ?>
<?php  (isset($_GET['brand'])) ? $vendor = new Vendor() : FALSE;  ?>
<div id="main">

	<div id="navigation"><br>
		<a href="index.php">&laquo; Main Menu</a><br>
		<ul class="vendor">
		<?php while ($record = mysqli_fetch_assoc($vendors)): ?>
			<a href="index.php?brand=<?php echo $record['vendor_no']; ?>"><?php echo ucfirst(strtolower($record['vendor_no']));  ?></a>
		<?php endwhile; ?>
		</ul>
	</div>
	<div id ="page">
		<table class="dashboard">
			<tr><th>Image Name</th><th>Requested By</th><th>Shadow</th><th>Scale</th><th>Color</th><th>Date</th></tr>
		<?php foreach ($image as $record): ?>

			<tr style="<?php echo ($record->approved_author !== NULL ? 'background-color: green;' : NULL); ?>">
				
				<td><?php echo $record->image_name;  ?></td>
				<td><?php echo $record->comment_author;  ?></td>
				<td style="<?php echo ($record->need_shadow === '1' ? 'background-color: #ED7B7B;' : NULL  ); ?>"><?php echo ($record->need_shadow === '1' ?  'Yes' : 'No') ?></td>
				<td style="<?php echo ($record->need_scale === '1' ? 'background-color: #ED7B7B;' : NULL  ); ?>"><?php echo ($record->need_scale === '1' ?  'Yes' : 'No') ?></td>
				<td style="<?php echo ($record->need_color === '1' ? 'background-color: #ED7B7B;' : NULL  ); ?>"><?php echo ($record->need_color === '1' ?  'Yes' : 'No') ?></td>
				<td><?php echo  datetime_to_text($record->comment_date); ?></td>
			</tr>
			
		<?php endforeach; ?>
		</table>

	</div>

</div>

<?php include("../includes/layouts/footer.php"); ?>




