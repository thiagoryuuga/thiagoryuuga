<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include ESM_CLASS_PATH.'/esmaltec/Debug.php';

class MY_Controller extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		
		$this->load->database();
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->helper('funcoes_helper');
		$this->load->library('session');
		/* ------------------ */	
		
	}
	
	function render_top()
	{
		$var = '';
		$this->load->view('topo.php', $var);
	}

	function render($pagina, $var=null)
	{
		$this->render_top();
		$this->load->view('css_js');
		$this->load->view($pagina, $var);
		$this->render_bot();
	}

	function render_bot()
	{
		$var = "";
		$this->load->view('rodape.php', $var);
	}
	
	

}

?>