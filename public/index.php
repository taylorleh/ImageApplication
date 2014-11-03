<?php require_once ("../includes/initialize.php"); ?>
<?php if (!$session->is_logged_in()) { redirect_to("login.php"); } ?>
<?php
$user = User::find_by_id($session->user_id);
$vendors = Vendor::get_all_vendors();
$allComments = UserComment::find_unnaproved_comments();
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
		<?php if(isset($vendor)): ?>
		<div class="nav">

		
		<ul class="info">
			<li>Vendor: <?php  echo $vendor->current_vendor; ?></li>
			<li>Total Web Groups: <?php echo mysqli_num_rows($vendor->web_groups);  ?> </li>
		</ul>
		</div>
		<?php echo $vendor->web_group_nav();?>
		<?php else: ?>
			<!-- <?php echo "please select a brand";?> -->


			<table class="dashboard">
				<tr><th><a href="index.php?order_by=product_family">Vendor</a></th><th>Image Name</th><th>Requested By</th><th>Shadow</th><th>Scale</th><th>Color</th><th>Date</th></tr>
			<?php foreach ($allComments as $comment): ?>

				<tr title="intranet.dev:8080/view_group.php?brand=<?php echo $comment->vendor_no; ?>&group=<?php echo $comment->web_group_no; ?>#<?php echo $comment->product_family; ?>">
					<td><?php echo $comment->vendor_no; ?></td><td><?php echo $comment->image_name;  ?></td>
					<td><?php echo $comment->comment_author;  ?></td>
					<td style="<?php echo ($comment->need_shadow === '1' ? 'background-color: #ED7B7B;' : NULL  ); ?>"><?php echo ($comment->need_shadow === '1' ?  'Yes' : 'No') ?></td>
					<td style="<?php echo ($comment->need_scale === '1' ? 'background-color: #ED7B7B;' : NULL  ); ?>"><?php echo ($comment->need_scale === '1' ?  'Yes' : 'No') ?></td>
					<td style="<?php echo ($comment->need_color === '1' ? 'background-color: #ED7B7B;' : NULL  ); ?>"><?php echo ($comment->need_color === '1' ?  'Yes' : 'No') ?></td>
					<td><?php echo  datetime_to_text($comment->comment_date); ?></td>
				</tr>
				
			<?php endforeach; ?>
			</table>

			<table class="needsother">
				<caption>Images With Comments</caption>
				<tr><th>Vendor</th><th>Image</th><th>Reguested By</th><th>Comment</th><th>Date</th></tr>
				<?php foreach ($needsOtherComment = UserComment::find_comments_that_need_other() as $ocomment): ?>
					<tr>
						<td><?php echo $ocomment->vendor_no; ?></td><td><?php echo $ocomment->image_name; ?></td>
						<td><?php echo $ocomment->comment_author; ?></td>
						<td><?php echo $ocomment->comment; ?></td><td><?php echo datetime_to_text($ocomment->comment_date); ?></td>
					</tr>
				<?php endforeach; ?>
			</table>

			

		<?php endif; ?>


	</div>

</div>

<?php include("../includes/layouts/footer.php"); ?>
