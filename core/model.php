<?php

abstract class Model {

	/**
	 * Setea propiedad del objeto.
	 *
	 * @return: mixed
	 * */
	public function __get($property){
		if (property_exists($this, $property)) {
	    	return $this->$property;
	    }
	}
	
	/**
	 * Setea propiedad del objeto.
	 *
	 * @return: mixed
	 * */
	public function __set($property, $value){
		if (property_exists($this, $property)) {
	    	return $this->$property = $value;
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
		$exists = $this->load();
		if (is_null($exists)) {
			unset($vars['id']);
		}
		
		$columns = '';
		$values = '';
		$lastColumn = end(array_keys($vars));
		$setUpdate = '';
		$sql = "";

		foreach ($vars as $col=>$v){
			if($lastColumn == $col){
				$columns .= "`$col`";
				$values .= "'".DB::escape($v)."'";
				$setUpdate .= "`$col` = '".DB::escape($v)."'"; 
			}else {
				$columns .= "`$col`, ";
				$values .= "'".DB::escape($v)."', "; 
				$setUpdate .= "`$col` = '".DB::escape($v)."', ";
			}
		}
		
		
		if(is_null($exists)){
			$sql = "INSERT INTO $tableName ($columns) VALUES ($values)";
			
		}else {
			$sql = "UPDATE $tableName SET 
					$setUpdate
					WHERE `id`= ".DB::escape($this->id);
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
		
		if($data){
			$class = ucfirst($class.'Model');
			if(count($data) > 0){
				$object = new $class;
				foreach ($data as $k => $v){
					$object->__set($k, $v);
				}
			}
			
			return $object;
		}
		return null;
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
		$sql = "SELECT * FROM $tableName WHERE id = ?";
		$result = '';
		
		
		try {
			$stmt = $connection->prepare($sql);
			$stmt->bindParam(1, $id);
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