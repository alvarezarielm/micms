<?php

class Collection {
	
	private $collection;
	
	public function add($object){
		$this->collection[] = $object;
	}
	
	public function getCollection(){
		return $this->collection;
	}
	
}