<?php 

require_once('PHPMailer/PHPMailerAutoload.php');
class Mail{

   
    function __construct()
    {
       
    }

    function emailOTRS($info)
    {
         $mail = new PHPMailer();
                
    //Tell PHPMailer to use SMTP
    $mail->isSMTP();
    $mail->isHTML(true);

    //FIX PARA SERVIDOR PHP5.6
    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );

    //Enable SMTP debugging
    // 0 = off (for production use)
    // 1 = client messages
    // 2 = client and server messages
    $mail->SMTPDebug = 0;

    //Ask for HTML-friendly debug output
    $mail->Debugoutput = 'html';

    // Set charset for mail display

    $mail->CharSet = 'UTF-8';

    //Set the hostname of the mail server
    //$mail->Host = 'smtp.outlook.office365.com';
    $mail->Host = getenv('MAIL_HOST');
    //Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
    $mail->Port = 587;

    //Set the encryption system to use - ssl (deprecated) or tls
    $mail->SMTPSecure = 'tls';

    //Whether to use SMTP authentication
    $mail->SMTPAuth = true;

    //Username to use for SMTP authentication - use full email address for gmail
    $mail->Username = getenv('MAIL_ADDRESS_USERNAME');

    //Password to use for SMTP authentication
    $mail->Password = getenv('MAIL_ADDRESS_PASS');

    //Set who the message is to be sent from
    $mail->setFrom(getenv('MAIL_ADDRESS_FROM'));

    //Set an alternative reply-to address
    //$mail->addReplyTo('replyto@example.com', 'First Last');

    //Set who the message is to be sent to
    //$mail->addAddress('thiago.darlan@geq.com.br');
    //$mail->addAddress('mario.hercules@geq.com.br');
    //$mail->addAddress('kaio.henrique@geq.com.br');
    $mail->addAddress(getenv('SERVICEDESK_MAIL'));
    //$mail->addAddress('luiz.david@geq.com.br');

    //Set the subject line
    $mail->Subject = 'Atestado cadastrado - '.$info['colaborador'].' - '.$info['matricula'];
    
    //Read an HTML message body from an external file, convert referenced images to embedded,
    //convert HTML into a basic plain-text alternative body
        
    $decoded = base64_decode($info['documento']);
    //$resource = base64_decode(str_replace(" ", "+", substr($base, strpos($base, ","))));
    $mail->AddStringAttachment($decoded, $info['num_documento'].".jpg", "base64", "image/jpeg");
    
    if(strtoupper($info['tipo'])=='ATESTADO')
    {
        $mail->msgHTML("Id: ".$info['id']."<br />
        Matrícula: ".$info['matricula']."<br />
        Tipo: Atestado<br />
        Contato: ".$info['contato']."<br />
        Telefone: ".$info['telefone']."<br />
        CID: ".$info['cid']."<br />
        Afastamento: ".($info['afastamento'])."<br />
        Medico: ".($info['medico_afastamento'])."<br>
        CRM: ".$info['medico_crm']."<br />
        Data inicio: ".$info['data_inicio']."<br />
        Data fim: ".$info['data_fim']."<br />
        Pericia: ".$info['data_pericia']."<br />
        Retorno: ".$info['data_retorno']."<br />
        Pagamento: ".$info['pagamento']."<br />
        Dias: ".$info['qtd_tempo']."<br />
        Documento: ".$info['num_documento']."<br />
        Fim da mensagem");
    }
    else
    {
        $mail->msgHTML("Id: ".$info['id']."<br />
        Matrícula: ".$info['matricula']."<br />
        Tipo: Declaração de Comparecimento"."<br />
        Contato: ".($info['contato'])."<br />
        Telefone: ".$info['telefone']."<br />
        Hora inicio: ".$info['data_inicio']."<br />
        Horas: ".$info['qtd_tempo']."<br />
        Documento: ".$info['num_documento'].'<br />'.
        'Fim da mensagem');
    }

  

    if ($mail->send())
    {
       
        $data[] = array('mensagem_abertura'=> 'Documento enviado com sucesso, suas informações serão validadas pelo RH',
                            'id'=>$info['id'],
                            'status' =>'AGUARDANDO PROCESSAMENTO',
                            'results'=>array('resource'=>'Informações gravadas','code'=>200));
        
        return json_encode($data);

       
    } 
    else 
    {
    $data[] = array('mensagem_abertura'=>'Erro de envio',
                                'id'=>null,
                                'status' =>null,
                                'results'=>array('resource'=>'Erro de envio','code'=>500));

    return json_encode($data);
    }
}


}?>