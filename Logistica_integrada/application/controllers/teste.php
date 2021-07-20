<?php
class Teste extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url', 'funcoes'));
		$this->load->library('session');
	}
	
	function index()
	{
		//$this->session->sess_destroy();
        $this->load->view('teste_cep');
	}
	
}
?>