<?php

abstract class Model {

	var $attributes;
	private $_className;
	private $_tableName;
	
	public function __construct(){
		$this->setTableName();
		$this->setClassName();
	}
	
	private function setClassName(){
		$this->_className = get_class($this);
	}
	
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
	
	private function setTableName($tableName = null){
		if(!isset($tableName)){
			$class = strtolower(substr_replace(get_class($this) ,"",-5));
			$tableName = $class.'s';
			$this->__set('_tableName', $tableName );
		}else{
			$this->__set('_tableName', $tableName);
		}
		
	}
	
	private function getTableName(){
		return $this->_tableName;
	}
	
	/**
	 * Guarda un objeto.
	 *
	 * @return: int numero de id.
	 * */
	public function save(){
		$connection = DB::connect();
		$vars = (isset($this->attributes)) ? $this->attributes : get_object_vars($this);
		
		$columns = '';
		$values = '';
		$lastColumn = end(array_keys($vars));
		$setUpdate = '';
		$sql = "";

		foreach ($vars as $col=>$v){
			if(!is_null($this->{$col})){
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
		}
		
		if(is_null($this->id)){
			$sql = "INSERT INTO $this->_tableName ($columns) VALUES ($values)";
			
		}else {
			$sql = "UPDATE $this->_tableName SET 
					$setUpdate
					WHERE `id`= ".DB::escape($this->id);
		}

		try {
			return $connection->exec($sql);
		} catch (PDOException $e){
			die('Error: '.$e->getMessage().'<br/>');
		}
		
	}
	
	/**
	 * Elimina el objeto.
	 * 
	 * @return: boolean
	 * */
	public function delete($id = null){
		$connection = DB::connect();
		$id = (!is_null($id)) ? $id : $this->__get('id');
		$sql = "DELETE FROM $this->_tableName WHERE id = '".DB::escape($id)."'";
		
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
	
		if(is_null($id)){
			$id = $this->__get('id');
		}
		
		$data = $this->getById($id);

		if($data){
			if(count($data) > 0){
				foreach ($data as $k => $v){
					$this->__set($k, $v);
				}
			}

			return $this;
		}
		return null;
	}
	
	public function loadByAttributes($attributes = array()){
	
		if(is_array($attributes)){
			
			foreach ($attributes as $property => $value){
				$this->__set($property, $value);
			}
			
			$data = $this->getByAttr($attributes);
			
			if($data){
				if(count($data) > 0){
					foreach ($data as $k => $v){
						$this->__set($k, $v);
					}
				}
			
				return $this;
			}
			return null;
		}
		
	}
	
	/**
	 * Metodo para obtener un registro y realizar despues el load.
	 *
	 * @param: int $id
	 * @return: mixed.
	 * */
	protected function getById($id){
		
		$connection = DB::connect();
		$vars = get_object_vars($this);
		$sql = "SELECT * FROM $this->_tableName WHERE id = ".DB::escape($id);
		$result = '';

		try {
			$stmt = $connection->query($sql);
			$result = $stmt->fetch();
			
			return $result;
				
		} catch (PDOException $e){
			die('Error: '.$e->getMessage().'<br/>');
		}
	}
	
	protected function getByAttr($attributes = array()){
		if(is_array($attributes)){
			$connection = DB::connect();
			$i = 0;
			$sql = "";
			
			$where = '';
			$and = '';
			
			foreach ($attributes as $col=>$v){
				if($i == 0){
					$where = $col." = '".DB::escape($v)."'";
					
				}else {
					$and .= " AND ".$col." = '".DB::escape($v)."'"; 
					
				}
				$i++;
			}
			
			$sql = "SELECT * FROM $this->_tableName WHERE ".$where.$and;
			$result = '';
			
			try {
				$stmt = $connection->query($sql);
				
				$result = $stmt->fetch();
				return $result;
			
			} catch (PDOException $e){
				die('Error: '.$e->getMessage().'<br/>');
			}	
		}else{
			throw new Exception('Los atributos deben cargarse en forma de array.');
		}
	}
	
	protected function getAllByAttr($attributes = array()){
		if(is_array($attributes)){
			$connection = DB::connect();
			$i = 0;
			$sql = "";
				
			$where = '';
			$and = '';
				
			foreach ($attributes as $col=>$v){
				if($i == 0){
					$where = $col." = '".DB::escape($v)."'";
						
				}else {
					$and .= " AND ".$col." = '".DB::escape($v)."'";
						
				}
				$i++;
			}
				
			$sql = "SELECT * FROM $this->_tableName WHERE ".$where.$and;
			$result = '';
				
			try {
				$stmt = $connection->query($sql);
	
				$result = $stmt->fetchAll();
				return $result;
					
			} catch (PDOException $e){
				die('Error: '.$e->getMessage().'<br/>');
			}
		}else{
			throw new Exception('Los atributos deben cargarse en forma de array.');
		}
	}
	
	/**
	 * Metodo para obtener todos los datos de una tabla 
	 * y poder cargarlos en una coleccion.
	 *
	 * @return: mixed.
	 * */
	protected function getAll(){
		
		$connection = DB::connect();
		
		$sql = "SELECT * FROM $this->_tableName";
		$result = '';
		
		try {
			$stmt = $connection->query($sql);
			$result = $stmt->fetchAll();
		
			return $result;
		
		} catch (PDOException $e){
			die('Error: '.$e->getMessage().'<br/>');
		}
	}
	
	public function getCollection(){
		$collection = new Collection();
		$dataArray = $this->getAll();
		foreach ($dataArray as $data){
			$obj = new $this->_className;
			$obj->load($data['id']);
			$collection->add($obj);
		}
		return $collection;
	}
	
	
// 	public function instanceDependentTables(){
// 		if($this->hasDependentTables()){
// 			foreach ($this->dependentTables as $dependiente){
// 				$classname = ucfirst($dependiente).'Model';
// 				Helper::loadModel($dependiente);
// 				$instance = new $classname;
// 				$get = 'get'.ucfirst($dependiente);
// // 				$this->{$get}($this->__get('id'));
// 			}
// 		}
// 	}
	
// 	private function hasDependentTables(){
// 		if(isset($this->dependentTables)){
// 			return true;
// 		}
// 		return false;
// 	} 
}