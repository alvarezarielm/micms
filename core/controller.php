<?php

abstract class Controller {
	function __construct() {
		$this->view = new View();
	}
	
	public function loadModel($name){
		return Helper::loadModel($name);
	}
	
	public function assign($name, $val){
		$this->view->$name = $val;
	}
	
	public function redirect($controller){
		header('Location: '.BASE_URL.$controller);
	}
	
}