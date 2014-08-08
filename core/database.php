<?php 
class DB extends PDO {
	
	private static $host = "127.0.0.1";
	private static $dbname = "arialva_micms";
	private static $user = "root";
	private static $pass = "1qaz";
	
	static public function connect(){
		try {
			return new PDO('mysql:host='.self::$host.';dbname='.self::$dbname.'',self::$user, self::$pass);
		}catch (PDOException $e){
			throw new Exception('Error: '.$e->getMessage().'<br/>');
		}
	}
	
	static public function escape($value){
		$value = str_replace("%", "\\%", $value);
		
		return strtr($value, array(
				"\x00" => '\x00',
				"\n" => '\n',
				"\r" => '\r',
				'\\' => '\\\\',
				"'" => "\'",
				'"' => '\"',
				"\x1a" => '\x1a'
		));
	}

}
