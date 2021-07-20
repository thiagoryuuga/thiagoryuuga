<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inicio extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		
		$this->load->database();
	}
	
	function index()
	{
		
		if($this->session->userdata('idUser')!= "")
		{
			$this->render('inicio.php');
		}
		else
		{
			redirect('login');	
		}
			
	}
	
}