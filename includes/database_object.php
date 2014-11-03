<?php

/**
* 
*/
class DatabaseObject
{

	public static function find_all()
	{
		return static::find_by_sql("SELECT * FROM " . static::$table_name);
	}

	public static function find_by_id($id=0)
	{
		global $database;
		$result_array = static::find_by_sql("SELECT * FROM " . static::$table_name . " WHERE id = {$id} LIMIT 1");
		return !empty($result_array) ? array_shift($result_array) : FALSE;
	}


	public static function find_by_sql($sql="")
	{
		global $database;
		$result_set = $database->query($sql);
		$object_array = array();

		while ($row = mysqli_fetch_array($result_set)) {
			$object_array[] = static::instantiate($row);
		}
		return $object_array;
	}



	private static function instantiate($record)
	{
		$class_name = get_called_class();
		$object = new $class_name;
		// $object->id = $record['id'];
		// $object->username = $record['username'];
		// $object->password = $record['password'];
		// $object->first_name = $record['first_name'];
		// $object->last_name = $record['last_name'];
		

		foreach ($record as $attribute => $value) {
			if($object->has_attribute($attribute)) {
				$object->$attribute = $value;
			} else {
				$object->$attribute = $value;
			}
		}
		return $object;
	}

	private function has_attribute($attribute)
	{
		$object_vars = get_object_vars($this);

		return array_key_exists($attribute, $object_vars);
	}
	

}


?>