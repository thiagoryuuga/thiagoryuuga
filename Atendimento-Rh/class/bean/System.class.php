<?php
class System{
	
	public $id_system;
	
	public $name;
	
	public $description;
	
	public $path;
	
	public function setIdSystem($id_system){
		$this->id_system = $id_system;
	}
	
	public function getIdSystem(){
		return $this->id_system;
	}
	
	public function setName($name){
		$this->name = $name;
	}
	
	public function getName(){
		return $this->name;
	}
	
	public function setDescription($description){
		$this->description = $description;
	}
	
	public function getDescription(){
		return $this->description;
	}
	
	public function setPath($path){
		$this->path = $path;
	}
	
	public function getPath(){
		return $this->path;
	}
		
}
?>