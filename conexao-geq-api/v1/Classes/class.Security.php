<?php
class Security {
 
	private $api_key = "";
	private $api_secret = "";
	private $signature = "";
	private $signatureLimit = "600";//"15";
	private $isvalidsignatureLimit = true;
	private $securityApp = NULL;
	private $haspermission = 1;
    private $hasuserpermission = 1;
    private $hastokeid = 1;
    private $tokenid = NULL;
    private $requesttokenid = NULL;
    private $returnMessage = array(); 
    private $returnDeviceId = NULL;
    private $returnDeviceType = NULL;
    private $returnUserId = NULL;
	private $returnUtillizationId = NULL;
	private $debug = true;
	private $db = NULL;
		 
	function __construct($api_key=null,$signature=null) {
		$this->api_key = $api_key;
		$this->signature = $signature;
		$this->db = new Oracle();
	}
	
	function loadApplication($application) {
		$this->securityApp = $application;	
	} 
	
	function setReturnUtillizationId($id) {
		$this->returnUtillizationId = $id;
	}
	
	function getReturnUtillizationId() {
		return $this->returnUtillizationId;
	}
	
    function getReturnMessage() {
      return $this->returnMessage;    
    }
     
    function setReturnMessage($data) {
       $this->returnMessage = $data;    
    }
    
    function getReturnDeviceId() {
      return $this->returnDeviceId;
    }

    function getReturnDeviceType() {
      return $this->returnDeviceType;
    }
    
    function getReturnUserId() {
        return $this->returnUserId;
    }

    function setReturnUserId($userid) {
        $this->returnUserId = $userid;
    }
    
	function setApiUtilizationResponse($data=NULL) {
		
		if ($this->debug) {
		
			if ($data) {
				$this->setReturnMessage($data);
				$return = $this->getReturnMessage();
			}
			else
			{
				$return = json_encode($this->getReturnMessage());
			}
			 
			$SQL = "
				UPDATE api_utilization
					SET
					  api_utilization.api_utilizationreturnmessage = utl_raw.cast_to_raw('".$return."')
				WHERE 
					api_utilization.api_utilizationid = '".$this->getReturnUtillizationId()."'
			";
			
			$this->db->executeUpdate($SQL);	
		
		}
		
	} 
	 
	function single_signature_validation() {

		$asign  = $this->securityApp->request()->headers('api_key')."".$this->securityApp->request()->headers('api_date');
		$signature = $this->securityApp->request()->headers('signature');

		$seek = hash_hmac("sha1",$asign,$this->findSecretKey($this->api_key));
		 
		if ($signature == $seek) 
			return true;
			
		return false;
		
	}
	
	function token_signature_validation() {

		$asign  = $this->securityApp->request()->headers('api_key')."".$this->securityApp->request()->headers('api_date')."".$this->securityApp->request()->headers('access_token');
		$signature  = $this->securityApp->request()->headers('signature');

		$match = hash_hmac("sha1",$asign,$this->findSecretKey($this->api_key));
		
		if ($signature == $match) 
			return true;
			
		return false;
		
	}
	
	function api_token_signature_validation() {

		//$asign  = $this->securityApp->request()->headers('api_key')."".$this->securityApp->request()->headers('api_date')."".$this->securityApp->request()->headers('access_token');
		//$signature  = $this->securityApp->request()->headers('signature');

		//$match = hash_hmac("sha1",$asign,$this->findSecretKey($this->api_key));
		
		if ($this->findSecretKey($this->api_key)) {
		
			return true;
		
		}
		//if ($signature == $match) 
		//	return true;
			
		return false;
		
	}	
	
    function key_validation($key) {
	    $serial = substr($key, 0, 40);	
	    $checksum = substr($key, -4);
	    return substr(md5($serial), 0, 4) == $checksum;			
	}
	
	function validateCheckSum() {
		return $this->key_validation($this->api_secret);
	}
	
	function validateSignature() {
		return $this->single_signature_validation();
	}

	function validateSignatureWithToken() {
		return $this->token_signature_validation();
	}
	
	function validateApiKeyWithToken() {
		return $this->api_token_signature_validation();
	}	
	
    function parseAuthorizationToken() {
        if ($this->tokenid) {
            $data = array('message'=>'Authorization Token','results'=>array('resource'=>'API Authentication','code'=>401));
            $this->setReturnMessage($data);
			$this->securityApp->response()->header('WWW-Authorization', $this->tokenid);
            $this->securityApp->response()->header('x-geq-bearen', md5($this->tokenid . time()));
            $this->securityApp->response()->status(200);    
        }
        else
        {
             $data = array('message'=>'Authorization Token Error','results'=>array('resource'=>'API Authentication','code'=>401));
             $this->setReturnMessage($data);
			 $this->securityApp->response()->status(401);
        }
    } 
	
	function validate() {
        
        // Server Header Response
        $this->securityApp->response()->header('x-geq-bearen', md5(uniqid() . ':' . time()));
		
		if ($this->validateCheckSum() && $this->request() && $this->validateMethodPermission())
		{
			$this->apiutilization();
			return true;
		}
        else
        { 
            $data = array('message'=>'Uso invalido de API','results'=>array('resource'=>'API Authentication','code'=>422));
            $this->securityApp->response()->status(422);
            
            if ($this->haspermission == 0) {
                 $data = array('message'=>'Permissao negada','results'=>array('resource'=>'API Route Permission','code'=>401));
                 $this->setReturnMessage($data);
				 $this->securityApp->response()->status(401);
            }else if($this->haspermission == 2) {
                 $data = array('message'=>'Sem privilegios para acesso','results'=>array('resource'=>'API Route Permission','code'=>401));
                 $this->setReturnMessage($data);
				 $this->securityApp->response()->status(401);
            }
            
            $this->securityApp->response()->header('x-geq-basic',md5(time()));
            
            $this->apiutilization();
            
            echo json_encode($data);
            
            return false;
            
        }
        
	} 
	
	function validateWithoutSecurity($idURI=NULL) {
	
        // Server Header Response
        $this->securityApp->response()->header('x-geq-bearen', md5(uniqid() . ':' . time()));
		
		if ($this->validateRoutesDenied() && !$this->validateCheckSum() && !$this->request() && !$this->validateMethodPermission($idURI))
		{
			$this->apiutilization();
			return true;
		}		
        else
        { 
            $data = array('message'=>'Uso invalido de API','results'=>array('resource'=>'API Authentication','code'=>422));
            $this->securityApp->response()->status(422);
            
            if ($this->haspermission == 0) {
                 $data = array('message'=>'Permissao negada','results'=>array('resource'=>'API Route Permission','code'=>401));
                 $this->setReturnMessage($data);
				 $this->securityApp->response()->status(401);
            }else if($this->haspermission == 2) {
                 $data = array('message'=>'Sem privilegios para acesso','results'=>array('resource'=>'API Route Permission','code'=>401));
                 $this->setReturnMessage($data);
				 $this->securityApp->response()->status(401);
            } else if ($this->haspermission == 3) {
                 $data = array('message'=>'Acesso negado','results'=>array('resource'=>'API Route Permission','code'=>403));
                 $this->setReturnMessage($data);
				 $this->securityApp->response()->status(403);
			}
            
            $this->securityApp->response()->header('x-geq-basic',md5(time()));            
            $this->apiutilization();
            
            echo json_encode($data);
            
            return false;
            
        }
        
	} 

	function validateWithoutSecurityAndWithToken($idURI=NULL) {
        
        // Server Header Response
        $this->securityApp->response()->header('x-geq-bearen', md5(uniqid() . ':' . time()));
		
		if (!$this->validateCheckSum() && !$this->request() && !$this->validateMethodPermission($idURI) && $this->requestAppToken())
		{
			$this->apiutilization();
			return true;
		}
        else
        { 
            $data = array('message'=>'Uso invalido de API','results'=>array('resource'=>'API Authentication','code'=>422));
            $this->securityApp->response()->status(422);
            
            if ($this->haspermission == 0) {
                 $data = array('message'=>'Permissao negada','results'=>array('resource'=>'API Route Permission','code'=>401));
                 $this->setReturnMessage($data);
				 $this->securityApp->response()->status(401);
            }else if($this->haspermission == 2) {
                 $data = array('message'=>'Sem privilegios para acesso','results'=>array('resource'=>'API Route Permission','code'=>401));
                 $this->setReturnMessage($data);
				 $this->securityApp->response()->status(401);
            }
            
            $this->securityApp->response()->header('x-geq-basic',md5(time()));
            
            $this->apiutilization();
            
            echo json_encode($data);
            
            return false;
            
        }
        
	} 
	
	function validateWithSignature($idURI=NULL) {
		
        // Server Header Response
        $this->securityApp->response()->header('x-geq-bearen', md5(uniqid() . ':' . time()));
		 
		//if ($this->validateTimeStamp() &&  $this->requestSignature() &&  $this->validateSignature() && $this->validateMethodPermissionWithSignature($idURI)) {
		if ($this->validateTimeStamp() &&  $this->requestSignature() &&  $this->validateSignature() && $this->validateMethodPermissionWithSignature($idURI)) {
			$this->apiutilization();
			return true; 
		} 
		else {
			
			$data = array('message'=>'Assinatura invalida','results'=>array('resource'=>'API Authentication','code'=>422));
            $this->securityApp->response()->status(422);			
			$this->setReturnMessage($data);
			
            if ($this->haspermission == 0) {
                 $data = array('message'=>'Permissao negada','results'=>array('resource'=>'API Route Permission','code'=>401));
                 $this->setReturnMessage($data);
				 $this->securityApp->response()->status(401);
            }else if($this->haspermission == 2) {
                 $data = array('message'=>'Sem privilegios para acesso','results'=>array('resource'=>'API Route Permission','code'=>401));
                 $this->setReturnMessage($data);
				 $this->securityApp->response()->status(401);
            }		
		
            $this->securityApp->response()->header('x-geq-basic',md5(time()));
            $this->apiutilization();
			
            echo json_encode($data);
            
            return false;
		}
		
	} 
	
	function validateWithSignatureAndToken($idURI=NULL) {

		 // Server Header Response
        $this->securityApp->response()->header('x-geq-bearen', md5(uniqid() . ':' . time()));
		
		if ($this->validateTimeStamp() &&  $this->requestSignature() &&  $this->validateSignatureWithToken() && $this->validateMethodPermissionWithSignature($idURI) && $this->requestAppToken()) {
			$this->apiutilization();
			return true;
		}
		else 
		{
		
            $data = array('message'=>'Assinatura invalida','results'=>array('resource'=>'API Authentication','code'=>422));
            $this->setReturnMessage($data);
            
            $this->securityApp->response()->status(422);
            
            if ($this->haspermission == 0) {
                 $data = array('message'=>'Permissao negada','results'=>array('resource'=>'API Route Permission','code'=>401));
                 $this->setReturnMessage($data);
				 $this->securityApp->response()->status(401);
            }else if($this->haspermission == 2) {
                 $data = array('message'=>'Sem privilegios para acesso','results'=>array('resource'=>'API Route Permission','code'=>401));
                 $this->setReturnMessage($data);
				 $this->securityApp->response()->status(401);
            }
            
            $this->securityApp->response()->header('x-geq-Basic',md5(time()));
            
			$this->apiutilization();
			
            echo json_encode($data);
            
            return false;
		
		
		}
	}
	
	function validateWithApiKeyAndToken() {
		
		 // Server Header Response
        $this->securityApp->response()->header('X-geq-bearen', md5(uniqid() . ':' . time()));
		
		//if ($this->validateTimeStamp() &&  $this->requestSignature() &&  $this->validateSignatureWithToken() && $this->validateMethodPermissionWithSignature($idURI) && $this->requestAppToken()) {
		if ($this->validateApiKeyWithToken() && $this->requestAppToken()) {	
			$this->apiutilization();
			return true;
		}
		else 
		{
		
            $data = array('message'=>'Assinatura invalida','results'=>array('resource'=>'API Authentication','code'=>422));
            $this->setReturnMessage($data);
            
            $this->securityApp->response()->status(422);
            
            if ($this->haspermission == 0) {
                 $data = array('message'=>'Permissao negada','results'=>array('resource'=>'API Route Permission','code'=>401));
                 $this->setReturnMessage($data);
				 $this->securityApp->response()->status(401);
            }else if($this->haspermission == 2) {
                 $data = array('message'=>'Sem privilegios para acesso','results'=>array('resource'=>'API Route Permission','code'=>401));
                 $this->setReturnMessage($data);
				 $this->securityApp->response()->status(401);
            }
            
            $this->securityApp->response()->header('x-geq-basic',md5(time()));
            
			$this->apiutilization();
			
            echo json_encode($data);
            
            return false;
		
		
		}			
			
			
	}
	
	function validateAppToken($idURI=NULL) {
        
        // Server Header Response
        $this->securityApp->response()->header('x-geq-bearen', md5(uniqid() . ':' . time()));
		
		//if ($this->validateCheckSum() && $this->request() && $this->validateMethodPermission($idURI) && $this->requestAppToken())
		if (!$this->validateCheckSum() && !$this->request() && $this->validateMethodPermission($idURI) && $this->requestAppToken())
		{
			$this->apiutilization();
			return true;
		}
        else
        { 
            
            $data = array('message'=>'Uso invalido de API','results'=>array('resource'=>'API Authentication','code'=>422));
            $this->setReturnMessage($data);
            
            $this->securityApp->response()->status(422);
            
            if ($this->haspermission == 0) {
                 $data = array('message'=>'Permissao negada','results'=>array('resource'=>'API Route Permission','code'=>401));
                 $this->setReturnMessage($data);
				 $this->securityApp->response()->status(401);
            }else if($this->haspermission == 2) {
                 $data = array('message'=>'Sem privilegios para acesso','results'=>array('resource'=>'API Route Permission','code'=>401));
                 $this->setReturnMessage($data);
				 $this->securityApp->response()->status(401);
            }
            
            $this->securityApp->response()->header('x-geq-basic',md5(time()));
            
			$this->apiutilization();
			
            echo json_encode($data);
            
            return false;
            
        }
         
	}
    
	function validateTimeStamp() {
		
		$date = $this->securityApp->request()->headers('api_date');
		
		if (@strtotime($date)) {
		
			if ($date) {
				
				$data = new DateTime();
				$d1=new DateTime($date);
				$d2=new DateTime("NOW");	 
				$diff=$d2->diff($d1);
				
				if ($diff->h == 0) {
					if ($this->signatureLimit > $diff->i) {
						return true;	
					}
				}
				
			}
		
		}
		return false;
		
	}
	
    function requestAppToken() {
        
        $token = $this->securityApp->request()->headers('access_token');
        
        $SQL = "SELECT * FROM api_user where tokenid = '$token'";
        
		$result = $this->db->executeQuery($SQL);
		
		if (oci_fetch_all($result, $res) > 0) {

            //$row = oci_fetch_object($result);
			
			while (($row = oci_fetch_object($result))) {
			
	            $this->returnUserId     = $row->API_USERID;
	            $this->returnDeviceId   = $row->DEVICEID;
	            $this->returnDeviceType = $row->DEVICETYPE;
	            $this->requesttokenid   = $token;
			
			}
            
			return true;		
        }
        
		return false;
        
        
    }
	
	function validateUser() {
        
        if ($this->requestLoginAuthorization()) {
            
         return true;
            
        }  else  {
            
            $data = array('message'=>'Usuario invalido','results'=>array('resource'=>'API User Authentication','code'=>401));
			$this->setReturnMessage($data);
			$this->securityApp->response()->status(401);            
            
            echo json_encode($data);
            
            return false;
        }
	}

    function validateDeviceID() {
        
        if ($this->requestDeviceID()) {
            return true;   
        }
        
        $data = array('message'=>'Device ID invalido','results'=>array('resource'=>'API User Authentication','code'=>401));
        
		$this->setReturnMessage($data);
		
		$this->securityApp->response()->status(401);            
        
        echo json_encode($data);
        
        return false;
    }

    
    function requestDeviceID() {
      
      if ($this->securityApp->request()->params('deviceid') != "") {
            return true;   
        }
		
        return false;    
    }
    
    function validateRoutesDenied() {
		 
        $SQL = "
            SELECT
              *
            FROM
              api_routedenied
            WHERE
			  api_routedeniedaddress = '".$this->securityApp->request()->getIp()."'
        ";
       
		$result = $this->db->executeQuery($SQL);
		
		if (oci_fetch_all($result, $res) == 0) {
            return true;
		} else {
			$this->haspermission = 3;
			return false;
		}
        
    }
    
    function validateMethodPermission($idURI=NULL) {
        
	   $urlToParse = str_replace("/",".",$this->securityApp->request()->getPath());
	   
	   if ($idURI) {
		  $urlToParse = str_replace("/".$idURI,".0",$this->securityApp->request()->getPath());
		  $urlToParse = str_replace("/",".",$urlToParse);
	   }
		 
        $SQL = "
            SELECT
              api_routepermission.is_get,
              api_routepermission.is_post,
              api_routepermission.is_put,
              api_routepermission.is_delete,
              api_generator.api_key,
              api_route.path
            FROM
              api_routepermission
              INNER JOIN api_system ON api_system.api_systemid =
                api_routepermission.api_systemid
              INNER JOIN api_generator ON api_system.api_systemid =
                api_generator.api_systemid
              INNER JOIN api_route ON api_route.api_routeid =
                api_routepermission.api_routeid
            WHERE
              api_generator.api_key = '$this->api_key' and api_generator.api_secret = '$this->api_secret'
               AND api_route.path = '".$urlToParse."'
        ";
       
		$result = $this->db->executeQuery($SQL);
		//$nrows = oci_fetch_all($result, $res);
		if (oci_fetch_all($result, $res) == 0) {
			$this->haspermission = 2;
            return false;
		} else if (oci_fetch_all($result, $res) > 0) {
			$this->haspermission = 0;
		}
		
        $row = oci_fetch_object($result);
		
		while (($row = oci_fetch_object($result))) {
			
	        switch($this->securityApp->request()->getMethod()) {
	         
	            case 'GET':
	            
	            if ($row->IS_GET == 1)
	                return true;
	            
	            break;
	            
	            case 'POST':
	            
	            if ($row->IS_POST == 1)
	                return true;            
	            
	            break;
	
	            case 'DELETE':
	            
	            if ($row->IS_DELETE == 1)
	                return true;            
	            
	            break;
	
	            case 'PUT':
	            
	            if ($row->IS_PUT == 1)
	                return true;            
	            
	            break;
	            
	        }
		
		}
	
		return false;
        
    }

    function validateMethodPermissionWithSignature($idURI=NULL) {
        
	  $urlToParse = str_replace("/",".",$this->securityApp->request()->getPath());
	   
	   if ($idURI) {
	   	   
		  $urlToParse = str_replace("/".$idURI,".0",$this->securityApp->request()->getPath());
		  $urlToParse = str_replace("/",".",$urlToParse);
	   }
		 
        $SQL = "
            SELECT
              api_routepermission.is_get,
              api_routepermission.is_post,
              api_routepermission.is_put,
              api_routepermission.is_delete,
              api_generator.api_key,
              api_route.path
            FROM
              api_routepermission
              INNER JOIN api_system ON api_system.api_systemid =
                api_routepermission.api_systemid
              INNER JOIN api_generator ON api_system.api_systemid =
                api_generator.apisystemid
              INNER JOIN api_route ON api_route.api_routeid =
                api_routepermission.api_routeid
            WHERE
              api_generator.api_key = '$this->api_key'
               AND api_route.path = '".$urlToParse."'
        ";
       
		$result = $this->db->executeQuery($SQL);
		
		if (oci_fetch_all($result, $res) == 0) {
			$this->haspermission = 2;
            return false;
		} else if (oci_fetch_all($result, $res) > 0) {
			$this->haspermission = 0;
		}
		
        //$row = oci_fetch_object($result);
        
		while (($row = oci_fetch_object($result))) {
		
	        switch($this->securityApp->request()->getMethod()) {
	         
	            case 'GET':
	            
	            if ($row->IS_GET == 1)
	                return true;
	            
	            break;
	            
	            case 'POST':
	            
	            if ($row->IS_POST == 1)
	                return true;            
	            
	            break;
	
	            case 'DELETE':
	            
	            if ($row->IS_DELETE == 1)
	                return true;            
	            
	            break;
	
	            case 'PUT':
	            
	            if ($row->IS_PUT == 1)
	                return true;            
	            
	            break;
	            
	        }
		
		}
	
		return false;
        
    }


	function request() {
		
        $SQL = "SELECT * FROM api_generator where api_key = '$this->api_key' and api_secret = '$this->api_secret'";
        
		$result = $this->db->executeQuery($SQL);
		
		if (oci_fetch_all($result, $res) > 0) 
			return true;		
			
		return false;
		
	}

	function requestSignature() {
		
        $SQL = "SELECT * FROM api_generator where api_key = '$this->api_key'";
        
		$result = $this->db->executeQuery($SQL);
		
		if (oci_fetch_all($result, $res) > 0) 
			return true;		
			
		return false;
		
	}


    function resetLoginAuthorization() {
       
        $token = $this->securityApp->request()->headers('access_token');
        
		$SQL = "SELECT * FROM api_user where tokenid = '$token'";
		
        
		$result = $this->db->executeQuery($SQL);
		
		if (oci_fetch_all($result, $res) > 0) {

            //$row = oci_fetch_object($result);
			
	        $SQL = "SELECT * FROM api_user where tokenid = '$token'";
	        
			$result = $this->db->executeQuery($SQL);
			
			while (($row = oci_fetch_object($result))) {
            
	            $apiuserid = $row->API_USERID;
	            
	           $sqlUpdate = "UPDATE api_user SET tokenid=NULL, deviceid=NULL, devicetype=NULL where api_userid = '".$apiuserid."'";
			   $resultUpdate = $this->db->executeUpdate($sqlUpdate);
				
			    $sql_conta_usuario = "SELECT NVL(COUNT(API_USERID),0) TOTAL FROM API_USERDEVICES WHERE API_USERID = '".$apiuserid."'";
				$result_usuario = $this->db->executeQueryWithArrayReturn($sql_conta_usuario);

				
				if($result_usuario[0][0] > 0)
				{
					$sqlUpdate2 = "UPDATE api_userdevices SET deviceUId=NULL, devicetoken=NULL, pushbadge = 0 where api_userid = '".$apiuserid."'";
					$resultUpdate2 = $this->db->executeUpdate($sqlUpdate2);
				}

				else
				{
					
					$sqlUpdate2 = "INSERT INTO RHATESTADO.API_USERDEVICES 
					(												  
					 API_USERDEVICEID, API_USERID, APPNAME, APPVERSION,  
					 DEVICEUID, DEVICETOKEN, DEVICENAME, DEVICEMODEL, 
					 DEVICEVERSION, PUSHBADGE, PUSHALERT, 	 PUSHSOUND,
					 DEVELOPMENT, STATUS, 	 CREATED
					 )
			VALUES (RHATESTADO.API_USERDEVICES_SEQ.NEXTVAL,
					TRIM('".$apiuserid."'),
					'".iconv('UTF-8','ISO-8859-1','Conexão GEQ')."',
					'1.0.0', NULL, NULL,
					' ', ' ', ' ', 
					0, ' ', ' ',
					' ', 'A', SYSDATE
					)";

					$resultUpdate2 = $this->db->executeUpdate($sqlUpdate2);
				}				
	               
	            $data = array('message'=>'Logout','results'=>array('resource'=>'API User Authentication','code'=>200));
	            
				$this->setReturnMessage($data);
				
				$this->securityApp->response()->status(200);            
	            
	            echo json_encode($data);
	            
				return true;
			
			}
            
        }
        
		return false;        
	}
	
	function resetAndroidLoginAuthorization($access_token) {
		
		 //$token = $this->securityApp->request()->headers('access_token');
		 $token = trim($access_token); 
		 
		 $SQL = "SELECT * FROM api_user where tokenid = '$token'";
		 
		 $result = $this->db->executeQuery($SQL);
		 
		 if (oci_fetch_all($result, $res) > 0) {
 
			 //$row = oci_fetch_object($result);
			 
			 $SQL = "SELECT * FROM api_user where tokenid = '$token'";
			 
			 $result = $this->db->executeQuery($SQL);
			 
			 while (($row = oci_fetch_object($result))) {
			 
				 $apiuserid = $row->API_USERID;
				 
				 $sqlUpdate = "UPDATE api_user SET tokenid=NULL, deviceid=NULL, devicetype=NULL where api_userid = '".$apiuserid."'";
				 $resultUpdate = $this->db->executeUpdate($sqlUpdate);                
					
				 $data = array('message'=>'Logout','results'=>array('resource'=>'API User Authentication','code'=>200));
				 
				 $this->setReturnMessage($data);
				 
				 $this->securityApp->response()->status(200);            
				 
				 echo json_encode($data);
				 
				 return true;
			 
			 }
			 
		 }
		 
		 return false;        
	 }
    
	function requestLoginAuthorization() {
		
		//$db = new MySQL();
		 
		$SQL = "SELECT * FROM api_user where username = '".$this->securityApp->request()->params('username')."' and password = '".$this->securityApp->request()->params('password')."'";
		$result = $this->db->executeQuery($SQL);
		
        if (oci_fetch_all($result, $res) == 0) {
            $this->hasuserpermission = 2;
            return false;
        }
        
        $row = oci_fetch_object($result);
        
		if (oci_fetch_all($result, $res) > 0) {
			
			while (($row = oci_fetch_object($result))) {
            
	            if (!$row->TOKENID && !$row->DEVICEID) {
	                $this->hastokeid = 0;
	                $token = md5(uniqid().$this->securityApp->request()->params('deviceid').$row->API_USERID);
	                $SQL = "UPDATE api_user SET tokenid='".$token."', deviceid= '".$this->securityApp->request()->params('deviceid')."' where api_userid = $row->API_USERID";
	                $resultInsert = $this->db->executeUpdate($SQL);                
	                $this->tokenid = $token;
	            }
			
			}
            
			return true;		
        }
        
		return false;
		 
	}

	function loginAuthorizationUpdate() {
		
		//$db = new MySQL();
       
        $token = $this->securityApp->request()->headers('access_token');
        
        $SQL = "SELECT * FROM api_user where tokenid = '$token'";
        
		$result = $this->db->executeQuery($SQL);
		
		if (oci_fetch_all($result, $res) > 0) {
 
            $row = oci_fetch_object($result);
			
			while (($row = oci_fetch_object($result))) {
            
	            $apiuserid = $row->API_USERID;
	            
	            $sqlUpdate = "UPDATE api_user SET datelastlogin=NOW() where api_userid = '".$apiuserid."'";
	            $resultUpdate = $this->db->executeUpdate($sqlUpdate);                
	            
	            $perfil[1] = "cliente";
	            $perfil[2] = "tecnico"; 
	            $perfil[3] = "vendedor";
	            
	            $levelDescription = $perfil[$row->LEVELID];
	            
	            $data = array('perfil'=> $levelDescription);
	            $this->securityApp->response()->status(200);            
	            
				$this->setReturnMessage($data);
				
	            echo json_encode($data);
	            
				return true;
			
			}
            
        }
        
		return false;     
		 
	}
    
    function requestAccessToken($user, $pass, $deviceid) {
         
        if ($user && $pass) {
			 
            $SQL = "SELECT * FROM api_user where username = '".$this->securityApp->request()->params('username')."' and password = '".$this->securityApp->request()->params('password')."'";
            $result = $this->db->executeQuery($SQL);
            
             if (oci_fetch_all($result, $res) == 0) { 
                 
                 $data = array('message'=>'Usuario invalido','results'=>array('resource'=>'API User Authentication','code'=>401));
                 $this->securityApp->response()->status(401);          
                 
				 $this->setReturnMessage($data);
				 
                 echo json_encode($data);
                 
                 return false;
             }
           
            if ($deviceid == "") {
            
                $data = array('message'=>'DeviceID invalido','results'=>array('resource'=>'API User Authentication','code'=>401));
                $this->securityApp->response()->status(401);     
                
				$this->setReturnMessage($data);
				
                echo json_encode($data);
                 
                 return false;
                
            }
            
            $row = oci_fetch_object($result);
            
            if (oci_fetch_all($result, $res) > 0) {
				
				while (($row = oci_fetch_object($result))) {
                
	                if (!$row->TOKENID && !$row->DEVICEID) {
	                    $this->hastokeid = 0;
	                    $token = md5(uniqid().$deviceid.$row->API_USERID);
	                    $SQL = "UPDATE api_user SET tokenid='".$token."', deviceid= '".$deviceid."' where api_userid = $row->apiuserid";
	                    $resultInsert = $this->db->executeUpdate($SQL);                
	                    $this->tokenid = $token;
	                }
	                else
	                {
	                    if ($row->DEVICEID != $deviceid) {
	
	                        $this->hastokeid = 0;
	                        $token = md5(uniqid().$deviceid.$row->API_USERID);
	                        $SQL = "UPDATE api_user SET tokenid='".$token."', deviceid= '".$deviceid."' where api_userid = $row->apiuserid";
	                        $resultInsert = $this->db->executeUpdate($SQL);                
	                        $this->tokenid = $token;
	                        
	                    }
	                    else {
							$this->hastokeid = 1;
							$token = $row->TOKENID;
							$this->tokenid = $token;
						}
	                }
					
				}
                
                $this->securityApp->response()->header('x-app-token', $token);
               
                $data = array('access_token'=>$token);
                
				$this->setReturnMessage($data);
				
                $this->securityApp->response()->status(200);            
                
                echo json_encode($data);
                
                return true;		
            }
            
            
        }
        else
        { 
            $data = array('message'=>'Usuario invalido','results'=>array('resource'=>'API User Authentication','code'=>401));
            
            $this->setReturnMessage($data);
            
            $this->securityApp->response()->status(401); 
			           
            echo json_encode($data);
            
            return false;		
        }
        
    }
	
	function findSecretKey($apikey) {
		
		$SQL  = "SELECT api_secret FROM api_generator where api_key = '".$this->securityApp->request()->headers('api_key')."'";
		$result = $this->db->executeQuery($SQL);
		
		//$row = oci_fetch_object($result);
		
		while (($row = oci_fetch_object($result))) {
			return $row->API_SECRET;
		}
		
        //if (is_object($row)) {
        //   return $row->apisecret;
        //}
		
		return false;
		
	}
	
	function apiutilization($responseHeaders="") {
		
		if ($responseHeaders != "")
		{
			$urlresponse = $responseHeaders['headers']['uri'];
		}
		else
		{
			$urlresponse = $this->securityApp->request()->getResourceUri();	
		} 
		
		$SQL  = "SELECT api_appsid FROM api_generator where api_key = '".$this->securityApp->request()->headers('api_key')."'";
		$result = $this->db->executeQuery($SQL);
		
		//$row = oci_fetch_object($result);
		
        $appid = 0;
        
		while (($row = oci_fetch_object($result))) {
			$appid = $row->API_APPSID;
		}
		
        //if (is_object($row)) {
        //    $appid = $row->apiappsid;
        //}
       
		$jsons = preg_replace("/%u([0-9a-f]{3,4})/i","&#x\\1;",urldecode($this->securityApp->request()->getBody()));
		
		$SQL = "
            INSERT INTO api_utilization
                (	                
	                api_utilizationid			,
	                api_utilizationtime        ,
	                api_utilizationaddress      ,
	                api_utilizationheaders      ,
	                api_utilizationparameters   ,
	                api_utilizationbody         ,
	                api_utilizationreturnmessage,
	                api_utilizationagent        ,
	                api_utilizationmethod       ,
	                api_tutilizationreferrer    ,
	                api_utilizationurl          ,
	                api_utilizationresourceuri  
                )
                VALUES (	
                	NULL,               
	               	SYSDATE,
	                '".$this->securityApp->request()->getIp()."',
	                utl_raw.cast_to_raw('".json_encode($this->securityApp->request()->headers())."'),
	                utl_raw.cast_to_raw('".json_encode($this->securityApp->request()->params())."'),
	                utl_raw.cast_to_raw('".$jsons."'),
	                utl_raw.cast_to_raw('".json_encode($this->getReturnMessage())."'),
	                '".$this->securityApp->request()->getUserAgent()."',
	                '".$this->securityApp->request()->getMethod()."',
	                '".$this->securityApp->request()->getReferrer()."',
	                '".$this->securityApp->request()->getPath()."',
	                '".$urlresponse."'
                )
                RETURN api_utilizationid INTO :id
		";


		$id_result = $this->db->executeUpdateID($SQL);	
		$this->setReturnUtillizationId($id_result);
		
	}
	
	
}



?>