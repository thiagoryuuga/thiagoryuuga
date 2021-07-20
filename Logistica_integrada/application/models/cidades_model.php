<?php 

if (! defined('BASEPATH')) exit('No direct script access');

class Cidades_model extends CI_Model {

	function __construct() {
		parent::__construct();
		//$this->load->database();
	}
	
		function getEstados() {
		
		return $this->db->get("ESM_LOGISTICA_ESTADOS")->result();
		
	}
	
	function getCidades($id = null) {
		
		if(!is_null($id))
		
			$this->db->where( array('ESM_LOGISTICA_ESTADOS.ID' => $id) );
		return $this->db->select('ESM_LOGISTICA_CIDADES.ID, ESM_LOGISTICA_CIDADES.NOME')
				 		->from('ESM_LOGISTICA_ESTADOS')
				 		->join('ESM_LOGISTICA_CIDADES', 'ESM_LOGISTICA_CIDADES.ID_UF = ESM_LOGISTICA_ESTADOS.ID')
						->get()->result();
		
	}

}