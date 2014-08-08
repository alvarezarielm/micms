<?php

abstract class Controller {
	
	function __construct() {
		Session::init();
	}
	
	public function loadModel($name){
		return Helper::loadModel($name);
	}
	
// 	public function assign($name, $value){
// 		$this->view->$name = $value;
// 	}
	
	public function redirect($controller){
		header('Location: '.BASE_URL.$controller);
	}
	
}