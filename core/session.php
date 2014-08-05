<?php 
class Session {
	
	function init(){
		@session_start();
	}
	
	static function set($name, $value){
		$_SESSION[$name] = $value;
	}
	
	static function get($name){
		if(isset($_SESSION[$name])){
			return $_SESSION[$name];
		}else{
			return false;
		}
	}
	
	static function destroy(){
		session_destroy();
	}
}