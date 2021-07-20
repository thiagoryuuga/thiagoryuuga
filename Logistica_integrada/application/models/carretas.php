<?php
class Carretas extends CI_Model{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function cadastrar($info)
    { 
                            
        if(!empty($info['id_veiculo']))
        {
            
            $sql = "UPDATE ESMALTEC.ESM_LOGISTICA_VEICULOS SET PLACA_CARRETA = UPPER('".$info['placa_carreta']."'),
                                                                PLACA_CAVALO = UPPER('".$info['placa_cavalo']."'),
                                                                TIPO_VEICULO = UPPER('".$info['veiculo']."')
                                                                WHERE ID_VEICULO = ".$info['id_veiculo'];
            $this->db->query($sql);
                                                                
        }
        else
         {
             if(!empty($info['placa_cavalo']))
            {
                $sql_carreta_existente = "SELECT COUNT(*) TOTAL FROM ESMALTEC.ESM_LOGISTICA_VEICULOS 
                                          WHERE UPPER(PLACA_CAVALO) = UPPER('".$info['placa_cavalo']."')";
                                          $total_carretas = $this->db->query($sql_carreta_existente)->result_array();

                           
                $sql_cavalo_existente = "SELECT COUNT(*) TOTAL FROM ESMALTEC.ESM_LOGISTICA_VEICULOS 
                                          WHERE UPPER(PLACA_CARRETA) = UPPER('".$info['placa_carreta']."')";
                                          $total_cavalos = $this->db->query($sql_cavalo_existente)->result_array();
             
             if($total_cavalos[0]['TOTAL'] < 1 )
            {           
                $sql_insere_carreta = "INSERT INTO ESMALTEC.ESM_LOGISTICA_VEICULOS (ID_VEICULO, PLACA_CAVALO, PLACA_CARRETA, TIPO_VEICULO) 
                                        VALUES(ESM_LOGISTICA_VEICULOS_SEQ.NEXTVAL,UPPER('".$info['placa_cavalo']."'),
                                        UPPER('".$info['placa_carreta']."'),UPPER('".$info['veiculo']."'))";
                                        $this->db->query($sql_insere_carreta);
            } else
            {
               return 'ERROR';
            }
         }
         return 'OK';
        }      
        
    }

    function buscar_carreta($PLACA)
    {
           $sql = "SELECT PLACA_CARRETA||';'||PLACA_CAVALO||';'||TIPO_VEICULO PLACA_CARRETA FROM ESMALTEC.ESM_LOGISTICA_VEICULOS 
        WHERE UPPER(PLACA_CARRETA) LIKE(UPPER('%".$PLACA."%')) "; 
        return ($this->db->query($sql)->result_array());
    }

    function buscar_cavalo($PLACA_CAVALO, $PLACA_CARRETA)
    {
       $sql = "SELECT PLACA_CAVALO,TIPO_VEICULO FROM ESMALTEC.ESM_LOGISTICA_VEICULOS 
        WHERE UPPER(PLACA_CAVALO) LIKE(UPPER('%".$PLACA_CAVALO."%')) AND (UPPER(PLACA_CARRETA) LIKE( UPPER('%".$PLACA_CARRETA."%')) OR PLACA_CARRETA IS NULL)";
        return $this->db->query($sql)->result_array();
    }

    function Listar_Cadastrados()
    {
        $sql = "SELECT ID_VEICULO,
                PLACA_CAVALO,
                PLACA_CARRETA,
                CASE WHEN(TIPO_VEICULO = 'C_BAU')
                THEN 'Carreta baú'
                WHEN(TIPO_VEICULO = 'C_BAU_REB')
                THEN 'Carreta baú rebaixada'
                WHEN(TIPO_VEICULO = 'GRANELEIRA')
                THEN 'Carreta graneleira'
                WHEN(TIPO_VEICULO = 'SIDER')
                THEN 'Carreta sider'
                WHEN(TIPO_VEICULO = 'C_TRUCK')
                THEN 'Truck baú'
                WHEN(TIPO_VEICULO = 'CONTAINER_20')
                THEN 'Container 20º'
                WHEN(TIPO_VEICULO = 'CONTAINER_40')
                THEN 'Container 40º'
                WHEN(TIPO_VEICULO = 'IVECO')
                THEN 'Iveco'
                WHEN(TIPO_VEICULO = 'FURGOVAN')
                THEN 'Furgovan'
                WHEN(TIPO_VEICULO = 'LEVE')
                THEN 'Veiculo leve'
                END  TIPO_VEICULO,
                TIPO_VEICULO COD_TIPO
                FROM ESMALTEC.ESM_LOGISTICA_VEICULOS C";
        return $this->db->query($sql)->result_array();
       
    }
    function buscar_tipo($PLACA_CAVALO)
    {
        $sql = "SELECT TIPO_VEICULO FROM ESMALTEC.ESM_LOGISTICA_VEICULOS WHERE PLACA_CAVALO = '".$PLACA_CAVALO."'";
        
        return $this->db->query($sql)->result_array();
    }
    function remove_placa($id_veiculo)
    {
        $sql1 = "DELETE FROM ESMALTEC.ESM_LOGISTICA_VEICULOS WHERE ID_VEICULO = '".$id_veiculo."'";        
        $this->db->query($sql1);
         return 'deleted';
    }

}

?>