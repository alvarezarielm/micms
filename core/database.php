<?php 
class DB extends PDO {
	
	static public function connect(){
		try {
			return new PDO('mysql:host=127.0.0.1;dbname=arialva_micms','root', '1qaz');
		}catch (PDOException $e){
			die('Error: '.$e->getMessage().'<br/>');
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
