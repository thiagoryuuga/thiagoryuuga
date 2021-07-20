<?php 
class Token{

    function __construct()
    {
        $this->db = new Oracle();
    }

    function insertToken($token)
    {
        $sql = "INSERT INTO RHATESTADO.API_TOKEN VALUES(API_TOKEN_SEQ.NEXTVAL,'".$token."',SYDATE,0)";
        $db->executeQuery($sql);
    }

} ?>