<?php
ini_set("include_path", '/home/sedosir/php:' . ini_get("include_path") );

require_once('PEAR.php');
pear::loadExtension('mysqli');
class Database{
		var $last_query; //Saved result of the last query made
		var $last_result; //Results of the last query made
		var $func_call; //A textual description of the last query/get_row/get_var call
		var $link; //database link
		var $lastquery; //last query
		var $result; //query result
		protected static $instance = NULL;

		public static function instance() {
			if ( self::$instance == null ) {
				self::$instance = new self();
			}		 
			return self::$instance;
		}

		// Connect to mysqli database
		function __construct() {
			$this->link=mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME) or die('Server connection not possible.');
			//Set All Charsets to UTF8
			mysqli_query($this->link, "SET character_set_results=utf8 , character_set_client=utf8 , character_set_connection=utf8 , character_set_database=utf8 , character_set_server=utf8");
			mysqli_select_db($this->link, DB_NAME) or die('Database connection not possible.');
		}


		function mysqlCleaner($data)
		{
			$data= mysqli_real_escape_string($this->link, $data);
			$data= stripslashes($data);
			return $data;
		} 

		/** Query the database.
			* @param $query The query.
			* @return The result of the query into $lastquery, to use with fetchNextObject().
			*/

		function query( $query ){
        $query = $this->mysqlCleaner($query);
				$this->lastquery=$query;
				$this->result=mysqli_query( $this->link, $query);
				if(mysqli_error($this->link)){
					v(mysqli_error($this->link));
					v($query);
				}
				return $this->result;
		}
		/** Do the same as query() but do not return nor store result.
			* Should be used for INSERT, UPDATE, DELETE...
			* @param $query The query.
			* @param $debug If true, it output the query and the resulting table.
			*/
		function execute($query)
		{
      $query = $this->mysqlCleaner($query);
			mysqli_query( $this->link, $query);
			if(mysqli_error($this->link))
				v(mysqli_error($this->link));
		}
		/** Convenient method for mysqli_fetch_object().
			* @param $result The ressource returned by query().
			* @return An ARRAY representing a data row.
			*/
		function fetchArray($result){
		if ($result == NULL)
				$result = $this->result;
		if ($result == NULL || mysqli_num_rows($result) < 1)
				return NULL;
		else
				return mysqli_fetch_assoc($result);
		}
 
		/** Close the connecion with the database server.
			* It's usually unneeded since PHP do it automatically at script end.
			*/
		function close()
		{
			mysqli_close($this->link);
		}
		/** Get the number of rows of a query.
			* @param $result The ressource returned by query(). If NULL, the last result returned by query() will be used.
			* @return The number of rows of the query (0 or more).
			*/
		function numRows($result = NULL)
		{
			if ($result == NULL)
				return @mysqli_num_rows($this->result);
			else
				return mysqli_num_rows($result);
		}
		/** Get the rows id after last query.
			* @param
			* @return
			*/
		function last_id()
		{
			
			return mysqli_insert_id($this->link);
		}

		function affectedRow($result = null){
			if ($result == NULL)
				return mysqli_affected_rows($this->result);
			else
				return mysqli_affected_rows($result);			
		}
}
?>