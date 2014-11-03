<?php

require_once ("database.php");
/**
* USER COMMENTS TEST***********************
*/
class UserComment2 extends DatabaseObject
{
	protected static $table_name = "UserComments";
	protected static $db_fields = array('id',  'product_family', 'image_name', 'comment_date', 'comment_author', 'need_shadow', 'need_scale', 'need_color', 'need_other', 'approved_date', 'approved_author', 'comment');



	public $id;
	// required fields
	public $product_family;
	public $image_name;
	public $comment_date;
	public $comment_author;
	public $need_shadow;
	public $need_scale;
	public $need_color;
	public $need_other;
	public $approved_date;
	public $approved_author;
	public $comment;

	public static function make($family, $user="Anonymous", $edits)
	{
		if (!empty($family) && !empty($user)) {
			
			$comment = new UserComment2();
			$check = $comment->checkUserValues($edits);
			// return $comment->need_other;
			// return $check;
			$comment->product_family = $family;
			$comment->comment_date = strftime("%Y-%m-%d %H:%M:%S", time());
			$comment->comment_author = $user;

			// vars

			$comment->image_name =    (empty($comment->image_name)) ? NULL : $comment->image_name;
			$comment->need_shadow =   (empty($comment->need_shadow)) ? 0 :  $comment->need_shadow;
			$comment->need_scale =    (empty($comment->need_scale)) ? 0 : $comment->need_scale;
			$comment->need_color =    (empty($comment->need_color)) ? 0 : $comment->need_color;
			$comment->need_other =    (empty($comment->need_other)) ? 0 : $comment->need_other;

			// conditional 

			$comment->approved_date = (empty($comment->approved_date)) ? NULL : $comment->approved_date;
			$comment->approved_author = (empty($check['approved_author']) ? NULL : $comment->approved_author = $check['approved_author']);
			$comment->comment = (empty($comment->comment)) ? NULL : $comment->comment;
			self::save($comment);
		} else {
			return FALSE;
		}
	}


	private function checkUserValues($eds)
	{
		$object = get_called_class();
		$edits  = get_object_vars($this);
		foreach ($eds as $key => $value) {
			if ($value == 'on') {
				$value = 1;
				$this->$key = $value;
			} else {
				$this->$key = $value;
			}
		}

		if (!empty($object)) {
			return;
		} else {
			die('Database comment failed!');
		}
	}

	private static function save($comment)
	{
		global $database;
		
		$fam =  $comment->product_family;
		$image = $comment->image_name;
		$date = $comment->comment_date;
		$auhor = $comment->comment_author;
		$shadow = $comment->need_shadow;
		$scale = $comment->need_scale;
		$color = $comment->need_color;
		$other = $comment->need_other;
		$approve = 	$comment->approved_date;
		$approved_author =  $comment->approved_author;
		$note = $comment->comment;
		$sql  = "INSERT INTO UserComments ( id, product_family, image_name, comment_date, comment_author,"; 
		$sql  .= " need_shadow, need_scale, need_color, need_other, approved_date, approved_author, comment) ";
		$sql  .= " VALUES ( 'NULL', '{$fam}', '{$image}' ,'{$date}' ,'{$auhor}' ,'{$shadow}' ,'{$scale}' ,'{$color}' ,'{$other}' ,'{$approve}' ,'{$approved_author}' , '{$note}' )";
		$result = $database->query($sql);
		return $result;

	}


	// DATE_FORMAT(tblUserComments.created, '%m-%d')
	public static function find_comments_on($image='')
	{
		global $database;
		$sql  = "SELECT * FROM " . self::$table_name;
		$sql .=	" WHERE image_name= "; 
		$sql .= " '{$image}' " ;

		return self::find_by_sql($sql);

	}

	static function current_status($comments='')
	{
		$result = "grey";
		$comment = array_shift($comments);
		
		 if (empty($comment)) {
		 	return $result;
		 }
		    foreach ($comment as $key) 
		    {
		        switch ($key) 
		        {
		            case 'need_shadow' == 1:
		                $result = 'red';
		                break;
		            case 'need_scale' == 1:
		                $result = 'red';
		                break;
		            case 'need_color' == 1:
		                $result = 'red';
		                break;
		            case 'need_other' == 1:
		                $result = 'red';
		                break;
		            
		            default:
		                $result = 'yellow';
		                break;
		        }
		    }
	        return $result;
	}


	
}






?>