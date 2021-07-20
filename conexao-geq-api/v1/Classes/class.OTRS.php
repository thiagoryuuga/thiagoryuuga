<?php

/**Classe de acesso ao banco de dados oracle, que implementa a interface acessodata */
class OTRS
{
	public $ipBanco = null;				
	var $usuarioBanco = null; 
	var $senhaBanco = null;
	var $conexao; 
	var $counter;
	var $is_executed = false;
	
	function __construct()
	{
		$this->ipBanco = getenv('OTRS_DATABASE_IP_BANCO');
		$this->usuarioBanco = getenv('OTRS_DATABASE_USER');
		$this->senhaBanco = getenv('OTRS_DATABASE_PASS');		
	}
	/** Método de uso interno para conectar ao banco de dados */	
	function conectar()
	{
		$this->conexao = ocilogon($this->usuarioBanco, $this->senhaBanco, $this->ipBanco);
	}
	
	/** Método de uso interno que retorna o objeto de conexão com o banco de dados */	
	function getConexao()
	{
		return $this->conexao;
	}
	
	/** Método de uso interno que encerra conexão com banco de dados */	
	function finalizarConexao()
	{
		ocilogoff($this->conexao);
	}
	
	function close ( ) 
	{
		ocilogoff($this->conexao);
	}
	
	
	function executeQuery($sql)
	{
		
		$this->conectar();
		$statement = ociparse($this->getConexao(),$sql);
		ociexecute($statement);

//		$lista = oci_fetch_array($statement);
//		$count = sizeof($lista);
//		$this->counter = $count;

		$this->close ( );
		
		return $statement;
	}


  	function executeQueryWithReturn($sql)
	{
		$this->conectar();
		$statement = ociparse($this->getConexao(),$sql);
		ociexecute($statement);

 		while ($row = oci_fetch_array($statement, OCI_BOTH)) {
 			echo $row['MATRICULA'] . '<Br>';
 		}	
        
	}    

   function executeQueryCount($sql)
	{
		$this->conectar();
		$statement = ociparse($this->getConexao(),$sql);
		ociexecute($statement);
		ocifetchstatement($statement, $lista);
		$count = oci_num_rows($statement);
        $this->close ( );
        
        if ($count == 0) {
            /* Not Exists SEND TRUE FUNCTION */
            //return true;    
        }
        else
        {
            /* Exists SEND FALSE to FUNCTION */
		  //return false;
        }

		return $count;
        
	}    
	
	/** Método público de execução de consultas ao banco de dados. Retorna um array bidimensional com resultado obtido */	
	function executeQueryWithArrayReturn($sql)
	{
		$this->conectar();

		$statement = ociparse($this->getConexao(),$sql);
		ociexecute($statement);
		$count = ocifetchstatement($statement,$lista);

		$valores = array();
		
		for($j=0; $j<$count; $j++)
		{
			$linha = ""; 
			$linha = array();
			
			foreach($lista as $val)
			{
				array_push($linha,$val[$j]);
			}
			
			array_push($valores,$linha);
		}

		$erro = ocierror($this->getConexao());
		if($erro)
		{
			$this->finalizarConexao();
			return addslashes($erro);
		}
		$this->finalizarConexao();
		return $valores;
	}
	
	/** Método público de execução de updates, inserts, deletes. */	
	function executeUpdate($sql)
	{
		$this->conectar();
		$this->is_executed = false;
		$statement = ociparse($this->getConexao(),$sql);
		$this->is_executed = ociexecute($statement); 
		
		$erro = ocierror($this->getConexao());
		if($erro)
		{
			$this->finalizarConexao();
			return addslashes($erro);
		}
		
		
		$this->finalizarConexao();
		//@ocicommit($this->getConexao());
	}

	/** Método público de execução de updates, inserts, deletes. */	
	function executeUpdateID($sql)
	{	 
		
		$this->conectar();			

		$statement = ociparse($this->getConexao(),$sql);	
		
		$maxlength = -1;
		
		oci_bind_by_name($statement, ':id', $id, $maxlength, OCI_B_INT);

		$data = ociexecute($statement); 
		
		$this->finalizarConexao();	

		return $id;
		
	}


	function parseJSONObjectIdReferenceState ($table, $objectArray = "", $caption=true,$URL,$id)
	{
		// ORACLE 9i
		
		$tableReturn['resultCount'] = 0;
		
		while ($row = oci_fetch_array($table,OCI_RETURN_NULLS)) 
		{		
			foreach($row as $key => $value)	
			{
				if (is_string($key))
				{
					$fields[$key] = $value;	
				}
			}
		
			$tableReturn["results"][] =  $fields;
			$tableReturn['resultCount']++;
		}
		
		if ($tableReturn['resultCount'] > 0)
		{
			$tableReturn['dependentesURI'] = $URL."/dependentes/".$id;	
		}
		
		return json_encode($tableReturn);
	}
	
	function parseJSONObjectId ($table, $objectArray = "", $caption=true)
	{
		// ORACLE 9i
		
		/*$tableReturn['resultCount'] = 0;
		
		while ($row = oci_fetch_array($table,OCI_RETURN_NULLS)) 
		{		
			foreach($row as $key => $value)	
			{
				if (is_string($key))
				{
					$fields[$key] = $value;	
				}
			}
		
			$tableReturn["results"][] =  $fields;
			$tableReturn['resultCount']++;
			
		//$tableReturn['resultCount'] = 0;
		
		while ($row = oci_fetch_array($table,OCI_RETURN_NULLS)) 
		{		
			foreach($row as $key => $value)	
			{
				if (is_string($key))
				{
					$fields[$key] = $value;	
				}
			}
			//$tableReturn[] =  array_change_key_case($fields, CASE_LOWER);
			$tableReturn["results"][] =  $fields;
			$tableReturn['resultCount']++;
		}
		
		return json_encode($tableReturn);*/

		// ORACLE 9i
		
		//$tableReturn['resultCount'] = 0;
		
		while ($row = oci_fetch_array($table,OCI_RETURN_NULLS)) 
		{		
			foreach($row as $key => $value)	
			{
				if (is_string($key))
				{
					$fields[$key] = $value;	
				}
			}
			$tableReturn[] =  array_change_key_case($fields, CASE_LOWER);
			//$tableReturn["results"][] =  array_change_key_case($fields, CASE_LOWER);
			//$tableReturn['resultCount']++;
		}
		
		return json_encode($tableReturn);
		
		
		/*
		
		// ORACLE 8i
		
		while ($row = oci_fetch_array($table,OCI_RETURN_NULLS)) 
		{
			$i=1;
			
			foreach($row as $key => $value)	
			{
				 $fields[oci_field_name($table,$i)] = "$value";
				 $i++;
			}
			
			$tableReturn["bindings"][] =  $fields;
		}

		return json_encode($tableReturn);
		*/
	}	
	
	function parseJSONObjectIdUTF8 ($table, $objectArray = "", $caption=true)
	{
		while ($row = oci_fetch_array($table,OCI_RETURN_NULLS)) 
		{
			$i=1;
			
			foreach($row as $key => $value)	
			{
				 $fields[oci_field_name($table,$i)] = utf8_encode( "$value" );
				 $i++;
			}
			
			$tableReturn["bindings"][] =  $fields;
		}

		return json_encode($tableReturn);
		
	}	
	
	
}



if(!function_exists('json_encode'))
{
	function json_encode($a=false)
	{
		// Some basic debugging to ensure we have something returned
		if (is_null($a)) return 'null';
		if ($a === false) return 'false';
		if ($a === true) return 'true';
		
		if (is_scalar($a))
		{
			if (is_float($a))
			{
			// Always use '.' for floats.
			return floatval(str_replace(',', '.', strval($a)));
			}
			
			if (is_string($a))
			{
				static $jsonReplaces = array(array('\\', '/', "\n", "\t", "\r", "\b", "\f", '"'), array('\\\\', '\\/', '\\n', '\\t', '\\r', '\\b', '\\f', '\"'));
				return '"' . str_replace($jsonReplaces[0], $jsonReplaces[1], $a) . '"';
			}
			else
			{
				return $a;
			}
		}
		
		$isList = true;
		
		for ($i = 0, reset($a), $size = count($a); $i < $size; $i++) 
		{
			
			if (key($a) !== $i)
			{
				$isList = false;
				break;
			}
		
			next($a);
		}
		
		$result = array();
		
		if ($isList)
		{
			foreach ($a as $v) $result[] = json_encode($v);
			return '[' . join(',', $result) . ']';
		}
		else
		{
			foreach ($a as $k => $v) $result[] = json_encode($k).':'.json_encode($v);
			return '{' . join(',', $result) . '}';
		}
	}
}


if(!function_exists('json_decode'))
{
    function json_decode($json)
    {
        $comment = false;
        $out = '$x=';
        for ($i=0; $i<strlen($json); $i++)
        {
            if (!$comment)
            {
                if (($json[$i] == '{') || ($json[$i] == '['))
                    $out .= ' array(';
                else if (($json[$i] == '}') || ($json[$i] == ']'))
                    $out .= ')';
                else if ($json[$i] == ':')
                    $out .= '=>';
                else
                    $out .= $json[$i];
            }
            else
                $out .= $json[$i];
            if ($json[$i] == '"' && $json[($i-1)]!="\\")
                $comment = !$comment;
        }
        eval($out . ';');
        return $x;
    }
}

?>