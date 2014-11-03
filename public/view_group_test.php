<?php require_once ("../includes/initialize.php"); ?>
<?php if (!$session->is_logged_in()) { redirect_to("login.php"); } ?>

<?php
$user = User::find_by_id($session->user_id); 

if (isset($_GET['view']) && $_GET['view'] == "summary") {
    $view = "summary";
} else {
    $view = null;
}
include ("../includes/layouts/header.php");
$vendors = Vendor::get_all_vendors();
?>
<?php  (isset($_GET['brand'])) ? $vendor = new Vendor() : FALSE;  ?>

<?php  $vendor = new Vendor; ?>

 <div id="main">
    <div id="navigation"><br>
        <a href="index.php">&laquo; Main Menu</a><br>
        <ul class="vendor">
        <?php while ($record = mysqli_fetch_assoc($vendors)): ?>
            <a href="index.php?brand=<?php echo $record['vendor_no']; ?>"><?php echo ucfirst(strtolower($record['vendor_no'])); ?></a>
        <?php endwhile; ?>
        </ul>
    </div>       
    <div id="page">
        <div class="nav">
            <ul class="info">
                <li>Vendor: <?php echo htmlentities($vendor->current_vendor); ?></li>
                <li>Selected Web Group:  <?php echo htmlentities($_GET['group']); ?></li>
                <li>Total Families: <?php echo mysqli_num_rows($vendor->product_families_by_group($_GET['group'])); ?></li>
            </ul>
            <form method="get" id="view-submit" action="view_group.php">
            <div class="sub-view">
                Detail<input <?php if(!$view) echo "checked" ; ?> name="view" type="radio" value="all" />
                Summary<input <?php if($view && $view == "summary")echo "checked";?> name="view" type="radio" value="summary" />
            </div>
            <input name="brand" value="<?php echo $vendor->current_vendor;   ?>"type="hidden">
            <input name="group" value="<?php echo $_GET['group'];   ?>"type="hidden">
            </form>
            <div class="control">
                <a href="index.php?brand=<?php echo $vendor->current_vendor;  ?>">Select Different Group</a>
            </div>

        </div>  
        <?php if(!$view): ?>

        <?php $group_arrays = $vendor->mvctest($_GET['group']); 
        while ($group = mysqli_fetch_assoc($group_arrays)):
            $family_images = $vendor->family_images($group['product_family']);
             ?>
        
    <div class="image-grid">
        <div class="sub-menu">
            <span class="product-family">Family:</span><?php echo $group['product_family']; ?>
            <span class="product-family">Description:</span><?php echo $group['description']; ?>
            <input type="hidden" name="offset">
        </div>

        <?php while ($image = mysqli_fetch_assoc($family_images)): ?>
        <?php  $comments = UserComment::find_comments_on($image['image_name']); ?>
        <?php $comment = array_shift($comments); ?>
        <ul>
            <li>
                <span style="vertical-align: super;">Status:</span><img style="width: 16px;" src="/images/status/<?php echo $color = UserComment::current_status($comment) . '.png';?>" /><br>
                <?php if($color == 'green.png'): ?>
                <span style="vertical-align: super;">
                  by:<?php echo $comment->approved_author; ?>&nbsp;on:<?php echo datetime_totext_for_author($comment->approved_date); ?>
                </span>
                <?php endif; ?>
            </li>

            <li>
                <img src="http://a248.e.akamai.net/f/248/9086/10h/origin-d5.scene7.com/is/image/KLog/<?php echo $image['image_name']; ?>?$thumbnail$">
            </li>
            <li>
                <form class="commentform" action="createcomment.php" action="post">
                  <?php  if ($color !== 'green.png'): ?>
                   shadow:<input type="checkbox" <?php echo (isset($comment->need_shadow) && ($comment->need_shadow == 1)) ? 'checked' : FALSE ?> name="shadow" value="1" />
                   scale:<input type="checkbox" <?php echo (isset($comment->need_scale) && ($comment->need_scale == 1)) ? 'checked' : FALSE ?> name="scale" value="1" />
                   color:<input type="checkbox" <?php echo (isset($comment->need_color) && ($comment->need_color == 1)) ? 'checked' : FALSE ?> name="color" value="1" />
                   other:<input class="showtxt" type="checkbox" <?php echo (isset($comment->need_other) && ($comment->need_other == 1)) ? 'checked' : FALSE ?> name="other" value="1" />
                  <?php endif; ?>
                <input type="submit" name="submit" value="Request" style="display:<?php echo (($user->role) === 1) ? 'none' : 'block' . ';' ;?>" >
                <input type="submit" name="submit" value="Approve" style="display:<?php echo (($user->role) === 1) ? 'block' : 'none' . ';' ;?>" > 
                <input type="hidden" name="family" value="<?php echo  $group['product_family']; ?>" >
                <input type="hidden" name="image" value="<?php echo  $image['image_name']; ?>" >
                <!-- <textarea placeholder="Place comment here..." style="display: none;" rows="5" cols="25" name="comment"></textarea> -->
                </form>
            </li>
        </ul>
        <?php endwhile; ?>
    </div>

<?php endwhile; ?>
        <?php  else: ?>
        <?php $group_set = Vendor::family_of_group($_GET['group']); ?>
    <div class="summary">
        <ul>
        <?php foreach($group_set as $value): ?>
            <li>
                <p class="image-id"><?php echo $value->product_family; ?></p><br>
                <img draggable="" src="http://a248.e.akamai.net/f/248/9086/10h/origin-d5.scene7.com/is/image/KLog/<?php echo  $value->product_family; ?>_1?$thumbnail$" />
            </li>
        <?php endforeach; ?>
        </ul>
    </div>
    <!-- <div style="text-align: center; margin-bottom: 5em;">
        <div id="subject" class="image-editor" style="position: relative;">
            
        </div>
        <button id="showgrid">Show Grid</button>
    </div> -->
    <div style="text-align: center;" class="framecontainer">

    <iframe id="frame" src="viewframe.php" width="346px" height="500px"></iframe>
    </div>
    <button id="showgrid">Show Grid</button>
    <section style="text-align: center;">
        <form class="resize-data">        
                <label>Percentage:<input name="percent" readonly type="text"></label>
                <label>width:<input name="labelWidth" id="labelWidth" type="number"></label>
                <label>height:<input name="labelHeight" id="labelHeight" type="number"></label>
            
        </form>
    </section>

<?php endif; ?>


<?php include ("../includes/layouts/footer.php"); ?>











