<?php
require_once ("database.php");
/**
* vendor class
*/
class Vendor extends DatabaseObject
{
	public $current_vendor;
	public $web_groups;

	protected static $table_name = "tblItems";
	public $id;
	public $product_family;
	public $image_name;
	public $vendor_no;
	public $description;
	public $web_group_no;
	function __construct() {
		if (isset($_GET['brand'])) {
			$this->current_vendor = $_GET['brand'];
			$this->web_groups = $this->web_groups($this->current_vendor);
		} else {
			$current_vendor = null;
		}
	}



	// this static function has replacd a vendor_nav() function
	// this formatted text and returned.
	public static function get_all_vendors()
	{
		global $database;
		$sql = "SELECT * FROM tblItems GROUP BY vendor_no ORDER BY vendor_no ASC ";
		$result_set = $database->query($sql);
		return $result_set;	

	}

	public function current_vendor()
	{
		if (isset($_GET['brand'])) {
			$this->current_vendor = $_GET['brand'];
		} else {
			$this->current_vendor = null;
		}
		
	}

	// Changed sql query to incorporate the both tables
	// this method should ultimately return the only result
	// set needed for the web group nav
	private function web_groups($vendor)
	{
		global $database;
		// $sql = "SELECT DISTINCT web_group_no FROM tblItems WHERE vendor_no = '$vendor' ";
		$sql = "SELECT * FROM tblImageInfo INNER JOIN tblItems ON tblImageInfo.product_family = tblItems.product_family WHERE tblItems.vendor_no = '$vendor' GROUP BY tblItems.web_group_no ";
		$web_groups_set = $database->query($sql);
		return $web_groups_set;
	}


	// method for overview button...
	// possbly delete
	public function product_families_by_group($group)
	{
		global $database;
		// $sql = "SELECT DISTINCT product_family FROM tblItems WHERE web_group_no = '$group'";
		$sql = "SELECT * FROM tblItems WHERE web_group_no = '$group' GROUP BY product_family";
		$group_family = $database->query($sql);
		return $group_family;
	}

	private function image_set_for_group($group)
	{
		global $database;
		$sql = "SELECT * FROM tblImageInfo INNER JOIN tblItems ON tblImageInfo.product_family = tblItems.product_family "; 
		$sql .= "WHERE tblItems.web_group_no = '$group' ";
		$image_set = $database->query($sql);
		return $image_set;
	}

	// this method call 3 helper methods
	// that return single colums result sets
	// helper methods: web_groups, product_families_by_group, image_set_for_group
	// this function needs refactored and return 1 result set 
	// ******  WEB_GROUPS() ******
	public function web_group_nav()
	{
		$web_groups_set = $this->web_groups($this->current_vendor);
		$output = "<table class=\"group-list\">";
		$output .= "<colgroup>";
		$output .= "<col>";
		$output .= "<col width=\"20%\">";
		$output .= "<col width=\"15%\">";
		$output .= "</colgroup>";
		$output .= "<thead>";
		$output .= "<th>Web Group</th>";
		$output .= "<th>Families</th>";
		$output .= "<th>Number Photos</th>";
		$output .= "</thead>";
		while ($group = mysqli_fetch_assoc($web_groups_set)) {				
			$output .= "<tr>";
			$output .= "<td>";
			$output .= "<a href=\"view_group.php?group=";
			$output .= urlencode($group['web_group_no']);
			$output .= "&brand=";
			$output .= urlencode($this->current_vendor);
			$output .= "\"";
			$output .= ">";
			$output .= $group['web_group_no'];
			$output .= "</a>";				
			$output .=  "</td>";
			$output .= "<td>";
			$output .= mysqli_num_rows($this->product_families_by_group($group['web_group_no']));
			$output .= "</td>";
			$output .= "<td>";
			$output .= mysqli_num_rows($this->image_set_for_group($group['web_group_no']));
			$output .= "</td>";
			$output .= "</tr>";
		}
		$output .= "</table>";
		return $output;
		mysqli_free_result($web_groups_set);
		
	}


	// ** TESTING
	// ** CHANGED function from private to public
	// was being called as a helper method for images2()... used on view_group.php
	// ** mvctest() is now taking its place. check to see if SQL statement can be improved up even more
	// as this is still a hepled method to mvctest() 
	// 4-16 
	public function family_images($family)
	{
		global $database;

		$sql = "SELECT * FROM tblImageInfo WHERE product_family = '$family' ";
		$family_images = $database->query($sql);
		return $family_images;
	}

	// DELETE
	// not used anymore.... double check*
	public function group_images_overview($group)
	{
		global $database;

		$sql = " SELECT * FROM tblImageInfo INNER JOIN tblItems ON tblItems.product_family = tblImageInfo.product_family WHERE tblItems.web_group_no = '$group' GROUP BY tblItems.product_family";
		$group_family_set = $database->query($sql);
		$output = "<ul>";

		while ($record = mysqli_fetch_assoc($group_family_set)) {
			$output .= "<li>";
			$output .= "<p class=\"image-id\">";
			$output .= $record['product_family'];
			$output .= "</p><br>";
			$output .= "<img ";
			$output .= "src=\"";
			$output .= "http://a248.e.akamai.net/f/248/9086/10h/origin-d5.scene7.com/is/image/KLog/";
			$output .= $record['image_name'];
			$output .= "?";
			$output .= "\$thumbnail\$";
			$output .= "\"";
			$output .= ">";
			$output .= "</li>";		
		}
		$output .= "</ul>";
		mysqli_free_result($group_family_set);
		return $output;
	}


	public static function family_of_group($group)
	{
		if(empty($group)) { return;}


		$sql = "SELECT * FROM tblItems WHERE web_group_no = '{$group}' ";
		$family_record = self::find_by_sql($sql);
		return $family_record;

	}







	public function mvctest($group='')
	{
		global $database;
		if(!empty($group)) {
			$sql = "SELECT * FROM tblImageInfo INNER JOIN tblItems ON tblItems.product_family = tblImageInfo.product_family WHERE tblItems.web_group_no = '{$group}' GROUP BY tblItems.product_family";
			$obj = $database->query($sql);
			if($obj) {
				return $obj;
			} else {
				return FALSE;
			}
			
		} else {
			return FALSE;
		}
	}






}

?>