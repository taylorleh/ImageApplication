<?php


/**
* USER COMMENTS
*/
class UserComment extends DatabaseObject
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

	public static function make($family, $user="Anonymous", $edits, $type='')
	{
		if (!empty($family) && !empty($user)) 
		{
			
			$comment = new UserComment();
			// return $comment->need_other;
			// return $check;
			$comment->product_family = $family;
			$comment->comment_author = $user;

			if ($type === 'Approve') {
				$comment->approved_date = strftime("%Y-%m-%d %H:%M:%S", time());
				// $comment->approved_author = $comment->comment_author;
				$comment->approved_author = $user;
			}  else {
				$comment->approved_date = NULL;
				$comment->approved_author = NULL;	
			}
								
			$comment->comment_date = strftime("%Y-%m-%d %H:%M:%S", time());

			// vars

			$comment->image_name =    (empty($comment->image_name)) ? NULL : $comment->image_name;

			foreach ($edits as $key => $value) {
				$comment->$key = $value;
			}
			

			// conditional 

			// $comment->approved_date = (empty($comment->approved_date)) ? NULL : $comment->approved_date;
			// $comment->approved_author = (empty($check['approved_author']) ? NULL : $comment->approved_author = $check['approved_author']);
			$comment->comment = (empty($comment->comment)) ? NULL : $comment->comment;
			// return var_dump($comment);
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
		$approve = $comment->approved_date;
		$approved_author = $comment->approved_author;
		$note = $comment->comment;
		// $sql  = "INSERT INTO UserComments ( id, product_family, image_name, comment_date, comment_author,"; 
		// $sql  .= " need_shadow, need_scale, need_color, need_other, approved_date, approved_author, comment) ";
		// $sql  .= " VALUES ( 'NULL', '{$fam}', '{$image}' ,'{$date}' ,'{$auhor}' ,'{$shadow}' ,'{$scale}' ,'{$color}' ,'{$other}' ,'{$approve}' , '{$approved_author}' , '{$note}' )";
		// $result = $database->query($sql);
		// return $result;

		$sql  = "INSERT INTO UserComments ( id, product_family, image_name, comment_date, comment_author, need_shadow, need_scale, need_color, need_other, approved_date,"; 
		if ( $comment->approved_author !== NULL ) {
			$sql  .= "approved_author, ";
		}
		$sql  .= " comment) ";
		$sql  .= " VALUES ( 'NULL', '{$fam}', '{$image}' ,'{$date}' ,'{$auhor}' ,'{$shadow}' ,'{$scale}' ,'{$color}' ,'{$other}' ,'{$approve}' , ";
		if ($comment->approved_author !== NULL ) {
		 	$sql .= " '{$approved_author}', ";
		 } 
		 $sql .= " '{$note}' )";

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
		$sql .= "ORDER BY comment_date DESC ";

		return self::find_by_sql($sql);

	}

	static function current_status($comments='')
	{
		$result = "grey";
		// $comment = array_shift($comments);
		$fields = array();
		 if (empty($comments)) {
		 	return $result;
		 }


	    foreach ($comments as $key => $value) 
	    {
	        switch ($key) 
	        {
	            case 'need_shadow':
	            	if ($value == 1) {
		            	$result = 'red'; 
	            	};
	            	break;

	            case 'need_scale':
	                if($value == 1) {
	                	$result = "red";
	                }
	                break;

	            case 'need_color':
	                if($value == 1) {
	                	$result = "red";
	                }
	                break;

	            case 'need_other':
	                if($value == 1) {
	                	$result = "yellow";
	                }
	                break;

                case 'approved_author':
                	if ($value !== NULL ) {
                		
	                	$result = "green";
                	}
                	
                	break;
	            
	            default:
	                // $result = 'yellow';
	                break;
	        }
	    }
        return $result;
	}

	public static function find_unnaproved_comments( )
	{
		global $database;

		// $sql = "SELECT * FROM "  . self::$table_name;
		// $sql .= " WHERE approved_author = 'NULL' ORDER BY comment_date DESC";

		$sql = "SELECT * FROM "  . self::$table_name;
		$sql .= " INNER JOIN tblItems ON tblItems.product_family = UserComments.product_family WHERE UserComments.approved_author <=> NULL AND UserComments.comment = '' ORDER BY id DESC LIMIT 15" ;
		return self::find_by_sql($sql);
		// return $database->query($sql);
	}

	public static function find_approved_comments()
	{
			
			$sql 	= "SELECT * FROM " . self::$table_name;
			$sql   .= " INNER JOIN tblItems ON tblItems.product_family = UserComments.product_family "; 
			$sql   .=  "WHERE UserComments.approved_author <> 'NULL' ";
			return self::find_by_sql($sql);
	}

	public static function find_comments_that_need_other( )
	{
			
			$sql 	= "SELECT * FROM " . self::$table_name;
			$sql   .= " INNER JOIN tblItems ON tblItems.product_family = UserComments.product_family ";
			$sql   .= "WHERE UserComments.need_other <=> '1' ORDER BY UserComments.comment_date DESC LIMIT 12";
			return self::find_by_sql($sql);

	}


	public static function get_image_approval_name($id='')
	{
		if (empty($name)) {
			return FALSE;
		}




	}

	
}






?>