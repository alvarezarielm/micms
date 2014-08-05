<?php

class ActiveRecord extends Model {
	
	private static $_models = array();
	
	public static function model($className=__CLASS__){
		if(isset(self::$_model[$className])){
			return self::$_models[$className];
		}else{
			$model = self::$_models[$className] = new $className;
			return $model;
		}
	}
	
	/**
	 * Guarda un objeto.
	 *
	 * @return: int numero de id.
	 * */
	public function save(){
		$connection = DB::connect();
		$class = strtolower(substr_replace(get_class($this) ,"",-5));
		$tableName = $class.'s';
		$vars = get_object_vars($this);
		$columns = '';
		$values = '';
		$lastColumn = end(array_keys($vars));
		$setUpdate = '';
		$sql = "";
		
		if (!isset($this->id)) {
			unset($vars['id']);
		}
		
		foreach ($vars as $col=>$v){
			
			if($lastColumn == $col){
				$columns .= "`$col`";
				$values .= "'$v'";
				$setUpdate .= "`$col` = '$v'"; 
			}else {
				$columns .= "`$col`, ";
				$values .= "'$v', "; 
				$setUpdate .= "`$col` = '$v', ";
			}
		}
				
		if(!isset($this->id)){
			$sql = "INSERT INTO $tableName ($columns) VALUES ($values)";
			
		}else {
			$sql = "UPDATE $tableName SET 
					$setUpdate
					WHERE `id`= $this->id";
		}
		
		try {
			$connection->exec($sql);
			return $connection->lastInsertId();
		} catch (PDOException $e){
			die('Error: '.$e->getMessage().'<br/>');
		}
		
	}
	
	/**
	 * Elimina el objeto.
	 * 
	 * @return: boolean
	 * */
	public function delete(){
		$connection = DB::connect();
		$class = strtolower(substr_replace(get_class($this) ,"",-5));
		$tableName = $class.'s';
		$vars = get_object_vars($this);
		$columns = '';
		$values = '';
		$lastColumn = end(array_keys($vars));
		$sql = "DELETE FROM $tableName WHERE id = '".$this->__get('id')."'";
		
		try {
			return $connection->exec($sql);
		} catch (PDOException $e){
			die('Error: '.$e->getMessage().'<br/>');
		}
	}
	
	/**
	 * Realiza un carga del objeto.
	 * 
	 * @param: int $id
	 * @return: mixed.
	 * */

	public function load($id = null){
	
		if(!isset($id)){
			$id = $this->__get('id');
		}
		$class = strtolower(substr_replace(get_class($this) ,"",-5));
		$data = $this->getById($id);
		
		$class = ucfirst($class.'Model');
		if(count($data) > 0){
			$object = new $class;
			foreach ($data as $key => $v){
				$object->__set($key,$v);
			}
		}
		
		return $object;
		
	}
	
	/**
	 * Metodo para obtener un registro y realizar despues el load.
	 *
	 * @param: int $id
	 * @return: mixed.
	 * */
	protected function getById($id){
		
		$connection = DB::connect();
		$class = strtolower(substr_replace(get_class($this) ,"",-5));
		$tableName = $class.'s';
		$vars = get_object_vars($this);
		$sql = "SELECT * FROM $tableName WHERE id = $id";
		$result = '';
		
		try {
			$stmt = $connection->query($sql);
			$result = $stmt->fetch();

			return $result;
				
		} catch (PDOException $e){
			die('Error: '.$e->getMessage().'<br/>');
		}
	}
	
	/**
	 * Metodo para obtener todos los datos de una tabla 
	 * y poder cargarlos en una coleccion.
	 *
	 * @return: mixed.
	 * */
	static function getAll($name){
		
		$connection = DB::connect();
		$class = strtolower($name);
		$tableName = $class.'s';
		
		$sql = "SELECT * FROM $tableName";
		$result = '';
		
		try {
			$stmt = $connection->query($sql);
			$result = $stmt->fetchAll();
		
			return $result;
		
		} catch (PDOException $e){
			die('Error: '.$e->getMessage().'<br/>');
		}
	}
	
	public function instanceDependentTables(){
		if($this->hasDependentTables()){
			foreach ($this->dependentTables as $dependiente){
				$classname = ucfirst($dependiente).'Model';
				Helper::loadModel($dependiente);
				$instance = new $classname;
				$get = 'get'.ucfirst($dependiente);
// 				$this->{$get}($this->__get('id'));
			}
		}
	}
	

	
	private function hasDependentTables(){
		if(isset($this->dependentTables)){
			return true;
		}
		return false;
	} 
}