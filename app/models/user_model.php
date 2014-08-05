<?php

/**
 * TODO: Crear setters y getters, load del objeto como corresponde
 * 		y limpiar las funciones que corresponden al controlador como 'getByEmail'
 * 		este tipo de funciones se pueden hacer desde el controlador.
 * 		Tambien se podria crear la clase abstracta model, directamente los getters y setters 
 * 		como tambien un get collection.		
 * */

class UserModel extends Model {
	
	var $id;
	var $username;
	var $password;
	var $email;
	
	public function get($username){
		$connection = DB::connect();
		$stmt = $connection->prepare('SELECT * FROM users WHERE nombre=:nombre');
		$stmt->execute(array(
			'nombre'=>$username
		));
		$data = $stmt->fetch(PDO::FETCH_ASSOC);
		return $data;
	}
	
	public function getByEmail($email){
		$connection = DB::connect();
		$stmt = $connection->prepare('SELECT * FROM users WHERE email=:email');
		$stmt->execute(array(
			'email'=>$email
		));
		$data = $stmt->fetch(PDO::FETCH_ASSOC);
		return $data;
	}
	
	public function login($u, $p){
		$connection = DB::connect();
		$stmt = $connection->prepare('SELECT id FROM users WHERE username = :username AND password = SHA1(:password)');
		$stmt->execute(array(
			'username'=>$u,
			'password'=>$p
		));
		
		$count = $stmt->rowCount();
		
		if($count > 0){
			return true;
		}else{
			return false;
		}
	}
	
	public function logout(){
		Session::destroy();
	}
}