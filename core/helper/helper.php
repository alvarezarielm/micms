<?php

class Helper {
	
	static function getCollection($name){
		
		$class = ucfirst($name).'Model';
		self::loadModel($name);
		$collection = new Collection();
		$tableArr = Model::getAll($name);
		
		foreach ($tableArr as $item){
			$object = new $class;
			foreach ($item as $key => $v){
				$object->__set($key, $v);
			}
			$collection->add($object);
		}
		return $collection->getCollection();
	}
	
	static function loadModel($name){
		$name = strtolower($name);
		$path = 'app/models/'.$name.'_model.php';
	
		if(file_exists($path)){
			require $path;
			$modelName = ucfirst($name).'Model';
			return new $modelName;
		}else{
			die('el modelo '.$name.' no existe.');
		}
	
	}
	
}