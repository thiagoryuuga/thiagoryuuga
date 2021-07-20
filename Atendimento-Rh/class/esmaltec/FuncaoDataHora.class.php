<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Classe FuncaoDataHora
 * Funções de data e hora 
 * @package lib
 * @author Leandro Pedrosa Rodrigues
 * @copyright Leandro Pedrosa Rodrigues
 * @version 1.0 FuncaoDataHora.class.php 19/08/2011
 */
class FuncaoDataHora {
    
	
	
    /**
     * Método que converte uma data-hora do formato EUA(SQL) para BR
     * @param String $datahora 2011-08-01 12:00:00
     * @return String Retorna 01/08/2011 12:00:00
     * @static
     */
    public static function converteDataHoraEuaParaBr($datahora) {
        $data = explode(" ", $datahora);
        $dataExp = explode("-", $data[0]);
        $dia = $dataExp[2];
        $mes = $dataExp[1];
        $ano = $dataExp[0];
        
        $dataCorreta = $dia."/".$mes."/".$ano." ".$data[1];
        return ($dataCorreta);
    }
    
    /**
     * Método que converte uma data-hora do formato BR para EUA(SQL)
     * @param String $datahora 01/08/2011 12:00:00
     * @return String Retorna 2011-08-01 12:00:00
     * @static
     */
    public static function converteDataHoraBrParaEUA($datahora) {
        $data = explode(" ", $datahora);
        $dataExp = explode("/", $data[0]);
        $dia = $dataExp[0];
        $mes = $dataExp[1];
        $ano = $dataExp[2];
        $dataCorreta = $ano."-".$mes."-".$dia." ".$data[1];
        return ($dataCorreta);
    }
    
    /**
     * Método que converte uma data formato EUA(SQL) para BR
     * @param String $data 2011-08-01
     * @return String Retorna 01/08/2011
     * @static
     */
    public static function converteDataEuaParaBr($data) {
        $dataExp = explode("-", $data);
        $dia = $dataExp[2];
        $mes = $dataExp[1];
        $ano = $dataExp[0];
        
        $dataCorreta = $dia."/".$mes."/".$ano;
        return ($dataCorreta);
    }
    
    /**
     * Método que converte uma data formato BR para EUA(SQL)
     * @param String $data 01/08/2011
     * @return String Retorna 2011-08-01 
     * @static
     */
    public static function converteDataBrParaEUA($data) {
        $dataExp = explode("/", $data);
        $dia = $dataExp[0];
        $mes = $dataExp[1];
        $ano = $dataExp[2];
        $dataCorreta = $ano."-".$mes."-".$dia;
        return ($dataCorreta);
    }
	
	
	/**
     * Método que converte uma data formato EUA(SQL) para BR sem dia
     * @param String $data 2011-08-01
     * @return String Retorna 08/2011
     * @static
     */
    public static function converteDataEuaParaBrSemDia($data) {
        $dataExp = explode("-", $data);
        $dia = $dataExp[2];
        $mes = $dataExp[1];
        $ano = $dataExp[0];
        
        $dataCorreta = $mes."/".$ano;
        return ($dataCorreta);
    }
	
	 /**
     * Método que converte uma data formato BR para EUA(SQL)
     * @param String $data 01/08/2011
     * @return String Retorna 2011-08 
     * @static
     */
    public static function converteDataBrParaEUASemDia($data) {
        $dataExp = explode("/", $data);
        $dia = $dataExp[0];
        $mes = $dataExp[1];
        $ano = $dataExp[2];
        $dataCorreta = $ano."-".$mes."-".$dia;
        return ($dataCorreta);
    }
    
    /**
     * Método que pega a data-hora no formato EUA(SQL)
     * @return String Retorna 2011-08-01 12:00:00
     * @static
     */
    public static function pegaDataHoraEUA() {
        return (date("Y-m-d H:i:s"));
    }
    
    /**
     * Método que pega a data no formato EUA(SQL)
     * @return String Retorna 2011-08-01
     * @static
     */
    public static function pegaDataEUA() {
        return (date("Y-m-d"));
    }
    
    /**
     * Método que pega a data-hora no formato BR
     * @return String Retorna 01/08/2011 12:00:00
     * @static
     */
    public static function pegaDataHoraBr() {
        return (date("d/m/Y H:i:s"));
    }
    
    /**
     * Método que pega a data no formato BR
     * @return String Retorna 01/08/2011
     * @static
     */
    public static function pegaDataBr() {
        return (date("d/m/Y"));
    }
		
		 /**
     * Método que converte uma data-hora do formato BR para formato da Reunião
     * @param String $datahora 01/08/2011 12:00:00
     * @return String Retorna 20110801T120000Z
     * @static
     */
    public static function converteDataHoraReuniaoBR($datahora) {
        $data = explode(" ", $datahora);
        $dataExp = explode("/", $data[0]);
        $dia = $dataExp[0];
        $mes = $dataExp[1];
        $ano = $dataExp[2];
				$horaExp = str_replace(':','',$data[1]);
        $dataCorreta = $ano.$mes.$dia."T".$horaExp."Z";
        return ($dataCorreta);
    }
}

?>
