<?php

class UserMaster{
	
	private $email;
	
	private $id_company;
	
	private $id_departament;
	
	private $id_hierarch;
	
	private $id_register;
	
	private $id_user;
	
	private $login;
	
	
	public function setEmail($email){
		$this->email = $email;	
	}
	
	public function getEmail(){
		return $this->email;	
	}
	
	public function setIdCompany($id_company){
		$this->id_company = $id_company;
	}
	
	public function getIdCompany(){
		return $this->id_company;
	}
	
	public function setIdDepartament($id_departament){
		$this->id_departament = $id_departament;	
	}
	
	public function getIdDepartament(){
		return $this->id_departament;	
	}
	
	public function setIdHierarch($id_hierarch){
		$this->id_hierarch = $id_hierarch;
	}
	
	public function getIdHierarch(){
		return $this->id_hierarch;
	}
	
	public function setIdRegister($id_register){
		$this->id_register = $id_register;
	}
	
	public function getIdRegister(){
		return $this->id_register;
	}
	
	public function setIdUser($id_user){
		$this->id_user = $id_user;	
	}
	
	public function getIdUser(){
		return $this->id_user;
	}
	
	public function setLogin($login){
		$this->login = $login;	
	}
	
	public function getLogin(){
		return $this->login;
	}
}

?>