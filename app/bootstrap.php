<?php

/**
 * @todo: Factorizar Bootstrap. 
 * */

class Bootstrap {
	
	public function init(){
		
		$controller_url = $this->getController();
		$method_url 	= $this->getMethod();
		$param_url 		= $this->getParam();
		
		if(is_null($controller_url)){
			$this->loadIndex();
		}else{
		
			$file = 'app/controllers/'.$controller_url.'_controller.php';
			
			if(file_exists($file)){
				
				require $file;
				$controllerName = ucfirst($controller_url).'Controller';
				$controller = new $controllerName;
				$controller->loadModel($controller_url);
				if(!is_null($method_url)){
					if(method_exists($controller, $method_url)){
						if(!is_null($param_url)){
							$controller->{$method_url}($param_url);
						}else{
							$controller->{$method_url}();
						}
					}
				}else{
					$controller->index();
				}
				
			}else{
				echo 'El controlador no existe';
				return false;
			}
		}
	}
	
	public function getController(){
		return $this->getUrlExploded()[0];
	}
	
	public function getMethod(){
		return (isset($this->getUrlExploded()[1]))? $this->getUrlExploded()[1] : null;
	}
	
	public function getParam(){
		return (isset($this->getUrlExploded()[2]))? $this->getUrlExploded()[2] : null;
	}
	
	public function getUrl(){
		return $_GET['url'];
	}
	
	public function getUrlExploded(){
		$url = rtrim($this->getUrl(), '/');
		$explodedUrl = explode('/',$url);
		return $explodedUrl;
	}
	
	
	function loadIndex(){
		require 'app/controllers/index_controller.php';
		$controller = new IndexController();
		$controller->index();
	}
	
	function loadPage($id = null){
		require 'app/controllers/index_controller.php';
		$controller = new IndexController();
		$controller->loadModel('index');
		$controller->index($id);
	}
}