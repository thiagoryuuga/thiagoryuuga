<?php

error_reporting(E_ALL);
require 'Slim/Slim.php';
require 'Framework/Framework.php';

\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();

date_default_timezone_set('America/Sao_Paulo');

$app->get('/',function(){

$url = $_SERVER['HTTP_HOST'];
$template = <<<EOT
<!DOCTYPE html>
    <html>
        <head>
            <meta charset="utf-8"/>
            <title>GEQ - Rest API</title>
            <style>
                html,body,div,span,object,iframe,
                h1,h2,h3,h4,h5,h6,p,blockquote,pre,
                abbr,address,cite,code,
                del,dfn,em,img,ins,kbd,q,samp,
                small,strong,sub,sup,var,
                b,i,
                dl,dt,dd,ol,ul,li,
                fieldset,form,label,legend,
                table,caption,tbody,tfoot,thead,tr,th,td,
                article,aside,canvas,details,figcaption,figure,
                footer,header,hgroup,menu,nav,section,summary,
                time,mark,audio,video{margin:0;padding:0;border:0;outline:0;font-size:100%;vertical-align:baseline;background:transparent;}
                body{line-height:1;}
                article,aside,details,figcaption,figure,
                footer,header,hgroup,menu,nav,section{display:block;}
                nav ul{list-style:none;}
                blockquote,q{quotes:none;}
                blockquote:before,blockquote:after,
                q:before,q:after{content:'';content:none;}
                a{margin:0;padding:0;font-size:100%;vertical-align:baseline;background:transparent;}
                ins{background-color:#ff9;color:#000;text-decoration:none;}
                mark{background-color:#ff9;color:#000;font-style:italic;font-weight:bold;}
                del{text-decoration:line-through;}
                abbr[title],dfn[title]{border-bottom:1px dotted;cursor:help;}
                table{border-collapse:collapse;border-spacing:0;}
                hr{display:block;height:1px;border:0;border-top:1px solid #cccccc;margin:1em 0;padding:0;}
                input,select{vertical-align:middle;}
                html{ background: #EDEDED; height: 100%; }
                body{background:#FFF;margin:0 auto;min-height:100%;padding:0 30px;width:100%px;color:#666;font:14px/23px Arial,Verdana,sans-serif;}
                h1,h2,h3,p,ul,ol,form,section{margin:0 0 20px 0;}
                h1{color:#333;font-size:20px;}
                h2,h3{color:#333;font-size:14px;}
                h3{margin:0;font-size:12px;font-weight:bold;}
                ul,ol{list-style-position:inside;color:#999;}
                ul{list-style-type:square;}
                code,kbd{background:#EEE;border:1px solid #DDD;border:1px solid #DDD;border-radius:4px;-moz-border-radius:4px;-webkit-border-radius:4px;padding:0 4px;color:#666;font-size:12px;}
                pre{background:#EEE;border:1px solid #DDD;border-radius:4px;-moz-border-radius:4px;-webkit-border-radius:4px;padding:5px 10px;color:#666;font-size:12px;}
                pre code{background:transparent;border:none;padding:0;}
                a{color:#70a23e;}
                header{padding: 30px 0;text-align:center;}
            </style>
        </head>
        <body>
		  <p><strong>{</strong> <br />
		  <strong>&quot;<font color="#CC3333">API_WELCOME</font>&quot;</strong>: &quot;<font color="#009966">BEM-VINDO</font></font>&quot;<strong>,</strong> <br />
		  <strong>&quot;<font color="#CC3333">API_NAME</font>&quot;</strong>: &quot;<font color="#009966">REST API GEQ</font>&quot;<strong>,</strong> <br />
		  <strong>&quot;<font color="#CC3333">API_VERSION</font>&quot;</strong>: &quot;<font color="#009966">1</font>&quot;<strong>,</strong> <br />
		  <strong>&quot;<font color="#CC3333">DEVELOPER_BY</font>&quot;</strong>: &quot;<font color="#009966">TI GEQ</font>&quot;<strong>,</strong> <br />
		  <strong>&quot;<font color="#CC3333">DATE_CREATED</font>&quot;</strong>: &quot;<font color="#009966">2017-09-20</font>&quot; <br />
		  <strong>&quot;<font color="#CC3333">HELP</font>&quot;</strong>: &quot;<font color="#009966">$url</font>&quot; <br />
		  <strong>}</strong></p>
          </body>
    </html>
EOT;
    echo $template;
 });

  
/** 
 * @Author: Thiago Darlan - thiago.darlan@geq.com.br 
 * 
 * @Date: 2018-05-23 15:19:57 
 * 
 * @Desc: Função que processa login e atualiza o registro do aparelho no banco de dados do sistema 
 * 
 * @Extra: Anteriormente o sistema autenticava verificando os usuários em ambiente AD, depois a 
 *         autenticação foi migrada para uma base própria de usuários
 * 
 * @return mixed
 * Em caso de autenticação com sucesso retorna um array com todos os dados do usuário,
 * em caso de erro de usuário e senha até a 5ª tentativa, retorna erro de usuário ou senha incorretos
 * e em caso de erro acima da 5ª tentativa, retorna a mensagem de usuário bloqueado 
 */
   
 
$app->post('/login', function() use ($app) {
   
    $is_permissao = false;
    $apikey = $app->request()->headers('api_key');
    $signature = $app->request()->headers('signature');
    $securityAPP = new Security($apikey, $signature);
    $securityAPP->loadApplication($app);
    $app->response()->header('Content-type', 'application/json; charset=utf-8');
    if ($securityAPP->validateWithoutSecurity())
    {         

      $matricula    = trim($app->request()->params("username"));
      $senha        = $app->request()->params("password");
      $deviceId     = $app->request()->params("deviceid");
      $brand        = $app->request()->params("brand");
      $device_type  = $app->request()->params("device_type");
      $os_version   = $app->request()->params("os_version");
      $app_version  = $app->request()->params("app_version");
      $app_name     = utf8_decode($app->request()->params("app_name")); 

      $bloqueio     = new Bloqueio();
      $soa          = new SOA($bloqueio);
      //$token        = new Token();
try{
       if(!empty($matricula) && !empty($senha))
       {   
            $usuario = new Usuario();
            $valid_user = $usuario->valida_usuario($matricula);
            $atestados = new AtestadosApiRh();           

           if ($valid_user == true && $usuario->autenticacao($matricula, $senha)) 
            {
                $retorno = array();
                $usuario = new Usuario();
                $atestados = new AtestadosApiRh();             

                                
                $retorno['message'] = $usuario->mensagem();                
              	$retorno['expiration'] = $usuario->dataExpiracao();

                    if($retorno['message']=="Ok")
                    {
                        $seq = $usuario->getProximoIdAparelho();  
                        $retorno['deviceId'] = $usuario->registraAparelho($seq[0],$deviceId,$matricula,$brand,$device_type,$os_version,$app_version,$app_name);
                        //funcão para limpar o bloqueio de usuário em caso de senha correta
                        //$usuario->limpaBloqueio($matricula);
                        $retorno['dados_usuario']     = $usuario->getDadosFuncionario($matricula);

                        if(empty($retorno['dados_usuario']))
                        {
                            $retorno['message'] = 'Sua empresa não está habilitada para este serviço';
                            $retorno['results'][] = array('resource'=>'Unauthorized','code'=>401);
                            $is_permissao = true;
                        }
                        $retorno['usuario_bloqueado'] = $usuario->getBloqueado($matricula);                        
                    }			
               } 
            else
            {                
                    $retorno = array();
                    $retorno['message'] = $usuario->mensagem();
            }
            if($retorno['message']=='Usuario bloqueado')
            {
                $usuario = new Usuario();
                $dados_bloqueio = $usuario->insereBloqueio($matricula);                
            }
        }
        else
        {
            
            $retorno['message'] = 'Usuário e senha devem estar preenchidos';
            $retorno['results'][] = array('resource'=>'Unauthorized','code'=>401);            
        } 

        if($valid_user == false)
        {
            $retorno = array();
            $retorno['message'] = 'Ops! Dados incorretos';
            $retorno['results'][] = array('resource'=>'Unauthorized','code'=>401);
            $is_permissao = false;
        }
    }
        catch (Exception $e)
        {
            $retorno['message'] = 'Erro de processamento';
            $retorno['results'][] = array('resource'=>'Internal Server Error','code'=>500);            
        }                      
    }    

    if($is_permissao)
    {
        $retorno = array();
        $retorno['message'] = 'Sua empresa não está habilitada para este serviço';
        $retorno['results'][] = array('resource'=>'Unauthorized','code'=>401);
    }

    $json_data[] = $retorno;
    echo(json_encode($json_data));
    $securityAPP->setApiUtilizationResponse(json_encode($json_data));
   
});

 /** 
  * @Author: Thiago Darlan - thiago.darlan@geq.com.br
  *
  * @Date: 2018-05-23 15:20:49
  * 
  * @Desc: Função que processa o logout do usuário e limpa os dados do aparelho dentro do banco de dados
  *  
  */ 
	
$app->post('/logout',function() use ($app) {
    
 	$apikey = $app->request()->headers('api_key');
	$signature = $app->request()->headers('signature');
    $access_token  = $app->request()->headers('access_token');

	$securityAPP = new Security($apikey, $signature);
	$securityAPP->loadApplication($app);
	 
	if ($securityAPP->validateWithoutSecurity()) 
    {
        $securityAPP->resetLoginAuthorization();
    }
     
});

 /** 
  * @Author: Thiago Darlan - thiago.darlan@geq.com.br
  * 
  * @Date: 2018-05-23 15:21:15 
  *
  * @Desc:  Função que envia mensagem SMS para o número de telefone informado, validando o aparelho. O SMS contém uma chave
  * que deve ser informada durante o processo de registro do usuário para que o processo seja concluído com sucesso.
  *
  *@return mixed
  *
  */ 

$app->post('/usuario/contato/valida',function() use($app){

    $apikey = $app->request()->headers('api_key');
	$signature = $app->request()->headers('signature');
    $access_token  = $app->request()->headers('access_token');

	$securityAPP = new Security($apikey, $signature);
	$securityAPP->loadApplication($app);
	 
	if ($securityAPP->validateWithoutSecurity()) 
    {
        $usuario = new Usuario();
        $is_enable = "";
        $info['num_cpf'] = str_replace(" ", "",str_replace(".", "",str_replace("-", "",trim($app->request()->params("num_cpf")))));
        $info['num_pis'] = str_replace(" ", "",str_replace(".", "",str_replace("-", "",trim($app->request()->params("num_pis")))));
        $info['dt_nascimento'] = trim($app->request()->params("dt_nascimento"));
        $info['telefone'] = str_replace("-", "",str_replace(" ", "",str_replace(")", "",str_replace("(", "", trim($app->request()->params("telefone"))))));

        
        $is_user = $usuario->getUsuarioPorCPF($info);
        $has_user = $usuario->getNovoUsuarioPorCPF($info);
        $data_validate = $usuario->valida_info_usuario($info);
        
        if($data_validate['cpf'] == false )
        {
            $retorno = array();
                $retorno['message'] = 'CPF inválido';
                $retorno['request_id'] = null;
                $retorno['results'][] = array('resource'=>'Unauthorized','code'=>401);
                
                $json_data[] = $retorno;
                echo(json_encode($json_data));
                exit(0);
                
        }
        
        if($data_validate['num_pis'] == false)
        {
            $retorno = array();
                $retorno['message'] = 'PIS inválido';
                $retorno['request_id'] = null;
                $retorno['results'][] = array('resource'=>'Unauthorized','code'=>401);
                
                $json_data[] = $retorno;
                echo(json_encode($json_data));
                exit(0);
        }
        if($data_validate['nascimento'] == false)
        {
            $retorno = array();
                $retorno['message'] = 'Data de nascimento inválida';
                $retorno['request_id'] = null;
                $retorno['results'][] = array('resource'=>'Unauthorized','code'=>401);
                
                $json_data[] = $retorno;
                echo(json_encode($json_data));
                exit(0);
        }       
        
        if($is_user[0] === false && $has_user[0] === true)
        {
            $is_enable = $usuario->verifica_empresa_ativa($has_user[1]); /*Checando se a empresa / filial está ativa a partir da matricula encontrada*/
          
            if($is_enable === true)
            {
                $usuario = new Usuario();
                $resultado = $usuario->gerarToken($info);
                $token = $usuario->gerarResetToken($info);
                
                $retorno = array();
                $retorno['message'] = "Token Enviado!";
                $retorno['request_id'] = $token;            
                $retorno['results'][] = array('resource'=>'Ok','code'=>200);
                

                $sms = new SMS();
                $sms->montaMensagem($info['telefone'],'Use '.$resultado.' como codigo de seguranca para o aplicativo Conexao GEQ',$retorno['request_id']);
                
                $json_data[] = $retorno;
                echo(json_encode($json_data));
            }
            else
            {
                $retorno = array();
                $retorno['message'] = 'Sua empresa não está habilitada para este serviço';
                $retorno['request_id'] = null;
                $retorno['results'][] = array('resource'=>'Unauthorized','code'=>401);
                
                $json_data[] = $retorno;
                echo(json_encode($json_data));
            }
        }
        if($is_user[0] === true && $has_user[0] === true) /*mesmo que a pessoa já tenha cadastro previo, a filial deve estar ativada*/
        {
            
            $retorno = array();            
            $retorno['message'] = "Usuário já cadastrado";
            $retorno['request_id'] = null;           
            $retorno['results'][] = array('resource'=>'Bad request','code'=>400);
                       
            $json_data[] = $retorno;
            echo(json_encode($json_data));

        }
        /*if($is_user[0] === false && $has_user[0] === false)
        {
            $retorno = array();
            $retorno['message'] = 'Ops! Dados incorretos';
            $retorno['request_id'] = null;
            $retorno['results'][] = array('resource'=>'Unauthorized','code'=>401);
            
            $json_data[] = $retorno;
            echo(json_encode($json_data));
        }*/

        
        
    }

});

 /** 
  * @Author: Thiago Darlan - thiago.darlan@geq.com.br
  * 
  * @Date: 2018-05-23 15:21:56 
  *
  * @Desc: Função que conclui o cadastro do usuário recebendo a chave enviada por SMS e os demais dados informados pelo usuário
  *
  * @See: check_device 
  *
  * @return mixed
  * Em caso de que todos os dados informados no processo de registro estarem corretos, o array de retorno é o mesmo da rota de login,
  * permitindo que o usuário seja redirecionado para a tela inicial do sistema. Em caso de erro de algum dos parametros informados,
  * o retorno informa que os dados estão incorretos. Existe uma etapa adicional de verificação que valida se o usuário que está efetuando 
  * o cadastro já tem registro ativo no sistema, caso aconteça, o retorno da função informa que o usuário já está cadastrado.   
  */ 

$app->post('/usuario/registra',function() use($app){
    $apikey = $app->request()->headers('api_key');
	$signature = $app->request()->headers('signature');
    $access_token  = $app->request()->headers('access_token');

	$securityAPP = new Security($apikey, $signature);
	$securityAPP->loadApplication($app);
	 
	if ($securityAPP->validateWithoutSecurity()) 
    {
        $usuario = new Usuario();
        $valid_requst = $usuario->valida_request($access_token);
        
       
        if($valid_requst === true)
        {
            $usuario = new Usuario();
            $info['token_reset'] = $app->request()->params('token_reset');
            $info['senha'] = trim($app->request()->params("password"));
            $info['email'] = trim($app->request()->params("email"));
            $info['telefone'] = str_replace("-", "",str_replace(" ", "",str_replace(")", "",str_replace("(", "", trim($app->request()->params("telefone"))))));
            $validacao = $usuario->validaToken($info);
            if($validacao[0] === true)
            {
                $dados_usuario = $usuario->getComplementoUsuario($validacao[1]);
                $info['num_cpf'] = $dados_usuario[2];
                $info['num_pis'] = $dados_usuario[0];
                $info['dt_nascimento'] = $dados_usuario[1];

                $device_params['deviceId']     = $app->request()->params("deviceid");
                $device_params['brand']        = $app->request()->params("brand");
                $device_params['device_type']  = $app->request()->params("device_type");
                $device_params['os_version']   = $app->request()->params("os_version");
                $device_params['app_version']  = $app->request()->params("app_version");
                $device_params['app_name']     = utf8_decode($app->request()->params("app_name"));

                $has_user = $usuario->getUsuarioPorCPF($info); /*verifica usuário já existente no banco de dados*/
                if($has_user[0] === false) /*caso seja usuario novo*/
                {
                    $registro = $usuario->registraUsuario($info,$device_params);
                    if($registro[0] === true)
                    {
                        $seq = $usuario->getProximoIdAparelho();
                        $retorno['message'] ="Ok";
                        $retorno['expiration'] = date('d/m/Y', strtotime("+30 days"));  
                        $retorno['deviceId'] = $usuario->registraAparelho($seq[0],$device_params['deviceId'],$registro[1],$device_params['brand'],
                        $device_params['device_type'],$device_params['os_version'],$device_params['app_version'],$device_params['app_name']);
                        
                        $atualiza_senha = $usuario->atualizaSenha($registro[1],$info['senha']);
                        $usuario->limpaBloqueio($registro[1]);
                        $usuario->bloqueiaToken($registro[1]);
                        $retorno['dados_usuario']  = $usuario->getDadosFuncionario($registro[1]);
                        $retorno['usuario_bloqueado'] = 'Usuario desbloqueado';
                        
                        $json_data[] = $retorno;
                        echo(json_encode($json_data));
                        
                    }
                    if($registro[0] === false)
                    {
                        $retorno = array();
                        $retorno['message'] = 'Ops! Dados incorretos';
                        $retorno['results'][] = array('resource'=>'Unauthorized','code'=>401);
                        $is_permissao = false;
            
                        $json_data[] = $retorno;
                        echo(json_encode($json_data));
                    }
                    
                }
                if($has_user[0] == true) { /*se o usuário já tem cadastro*/
                    $retorno = array();
                    $retorno['message'] = 'Usuário já cadastrado';
                    $retorno['results'][] = array('resource'=>'Unauthorized','code'=>401);
                    $is_permissao = false;
        
                    $json_data[] = $retorno;
                    echo(json_encode($json_data));
                }
                
                
            }
            if($validacao[0] === false)
            {
                $retorno = array();
                $retorno['message'] = 'Token inválido';
                $retorno['results'][] = array('resource'=>'Unauthorized','code'=>401);
                
                $json_data[] = $retorno;
                echo(json_encode($json_data));
            }           
            
            
        }

        else
        {
            $retorno = array();
            $retorno['message'] = 'Token inválido';
            $retorno['results'][] = array('resource'=>'Unauthorized','code'=>401);
            
            $json_data[] = $retorno;
            echo(json_encode($json_data));
        }
    }
   

});

/** 
 * @Author: Thiago Darlan - thiago.darlan@geq.com.br
 * @Date: 2018-05-22 15:07:07 
 * @Desc: Função que conclui a operação de login
 * @deprecated
 * @see /registrar   
 */

$app->post('/register',function() use($app){
    
    $apikey = $app->request()->headers('api_key');
	$signature = $app->request()->headers('signature');
    $access_token  = $app->request()->headers('access_token');

	$securityAPP = new Security($apikey, $signature);
	$securityAPP->loadApplication($app);
	 
	if ($securityAPP->validateWithoutSecurity()) 
    {
        $usuario = new Usuario();
        $access_token  = $app->request()->headers('access_token');
        $info['num_cpf'] = str_replace(" ", "",str_replace(".", "",str_replace("-", "",trim($app->request()->params("num_cpf")))));
        $info['num_pis'] = str_replace(" ", "",str_replace(".", "",str_replace("-", "",trim($app->request()->params("num_pis")))));
        $info['dt_nascimento'] = trim($app->request()->params("dt_nascimento"));
        $info['senha'] = trim($app->request()->params("password"));
        $info['telefone'] = str_replace("-", "",str_replace(" ", "",str_replace(")", "",str_replace("(", "", trim($app->request()->params("telefone"))))));
        $info['email'] = trim($app->request()->params("email"));
        $info['token_reset'] = $app->request()->params('token_reset');
        $device_params['deviceId']     = $app->request()->params("deviceid");
        $device_params['brand']        = $app->request()->params("brand");
        $device_params['device_type']  = $app->request()->params("device_type");
        $device_params['os_version']   = $app->request()->params("os_version");
        $device_params['app_version']  = $app->request()->params("app_version");
        $device_params['app_name']     = utf8_decode($app->request()->params("app_name")); 
  
        $bloqueio     = new Bloqueio();
        $soa          = new SOA($bloqueio);        
               
        $has_user = $usuario->getUsuarioPorCPF($info); /*verifica usuário já existente no banco de dados*/
        
        
        if($has_user[0] === false) /*caso seja usuario novo*/
        {         
                $registro = $usuario->registraUsuario($info,$device_params);
                if($registro[0] === true)
                {
                    $seq = $usuario->getProximoIdAparelho();
                    $retorno['message'] ="Ok";
                    $retorno['expiration'] = date('d/m/Y', strtotime("+30 days"));  
                    $retorno['deviceId'] = $usuario->registraAparelho($seq[0],$device_params['deviceId'],$registro[1],$device_params['brand'],
                    $device_params['device_type'],$device_params['os_version'],$device_params['app_version'],$device_params['app_name']);
                    
                    $atualiza_senha = $usuario->atualizaSenha($registro[1],$info['senha']);
                    $usuario->limpaBloqueio($registro[1]);
                    $retorno['dados_usuario']  = $usuario->getDadosFuncionario($registro[1]);
                    $retorno['usuario_bloqueado'] = 'Usuario desbloqueado';
                    
                    $json_data[] = $retorno;
                    echo(json_encode($json_data));
                    
                }
                if($registro[0] === false)
                {
                    $retorno = array();
                    $retorno['message'] = 'Ops! Dados incorretos';
                    $retorno['results'][] = array('resource'=>'Unauthorized','code'=>401);
                    $is_permissao = false;
        
                    $json_data[] = $retorno;
                    echo(json_encode($json_data));
                }
        }
        if($has_user[0] == true) { /*se o usuário já tem cadastro*/
            $retorno = array();
            $retorno['message'] = 'Usuário já cadastrado';
            $retorno['results'][] = array('resource'=>'Unauthorized','code'=>401);
            $is_permissao = false;

            $json_data[] = $retorno;
            echo(json_encode($json_data));
        }
    }

});

/** 
 * @Author: Thiago Darlan - thiago.darlan@geq.com.br 
 * @Date: 2018-05-22 15:13:01 
 * @Desc: Função gera uma chave numérica e envia para o solicitante via SMS para que a mesma seja utilizada como validador
 *        durante o processo de alteração de senha
 * @deprecated 
 * @see function /recovery 
 */

$app->post('/recover',function() use($app){

    $apikey = $app->request()->params('api_key') ? $app->request()->params('api_key') : $app->request()->headers('api_key');
    $signature = $app->request()->params('signature') ? $app->request()->params('signature') : $app->request()->headers('signature');

    $securityAPP = new Security($apikey, $signature);
    $securityAPP->loadApplication($app);
    $app->response()->header('Content-type', 'application/json; charset=utf-8');
    
    $info['num_cpf'] = str_replace(" ", "",str_replace(".", "",str_replace("-", "",trim($app->request()->params("num_cpf")))));
    $info['num_pis'] = str_replace(" ", "",str_replace(".", "",str_replace("-", "",trim($app->request()->params("num_pis")))));
    $info['dt_nascimento'] = trim($app->request()->params("dt_nascimento"));

    if ($securityAPP->validateWithoutSecurity())
    {
        $usuario = new Usuario();
        $has_user = $usuario->getUsuarioPorCPF($info);

        if($has_user[0] === true)
        {
            $usuario = new Usuario();
            $resultado = $usuario->gerarToken($info);
            $token = $usuario->gerarResetToken($info);
            $contato = $usuario->busca_contato($info);
            $retorno = array();
            $retorno['message'] = "Token enviado!";
            $retorno['request_id'] = $token;
            $retorno['results'][] = array('resource'=>'Ok','code'=>200);

            $sms = new SMS();
            $sms->montaMensagem($contato,'Use '.$resultado.' como codigo de seguranca para o aplicativo Conexao GEQ',$retorno['request_id']);
            
            $json_data[] = $retorno;
            echo(json_encode($json_data));
        }
        else
        {
            $retorno = array();
            $retorno['message'] = 'Ops! Dados incorretos';
            $retorno['request_id'] = null;
            $retorno['results'][] = array('resource'=>'Unauthorized','code'=>401);
            
            $json_data[] = $retorno;
            echo(json_encode($json_data));
        }
        
    }

});

/** 
 * @Author: Thiago Darlan - thiago.darlan@geq.com.br
 *  
 * @Date: 2018-05-22 15:15:19 
 * 
 * @Desc: Função que envia um SMS com chave numérica para o solicitante. A chave é utilizada para a confirmação do
 *         processo de alteração de senha
 * 
 * @see /activate 
 */

$app->post('/senha/resgata',function() use($app){
    
        $apikey = $app->request()->params('api_key') ? $app->request()->params('api_key') : $app->request()->headers('api_key');
        $signature = $app->request()->params('signature') ? $app->request()->params('signature') : $app->request()->headers('signature');
    
        $securityAPP = new Security($apikey, $signature);
        $securityAPP->loadApplication($app);
        $app->response()->header('Content-type', 'application/json; charset=utf-8');
        
        $info['num_cpf'] = str_replace(" ", "",str_replace(".", "",str_replace("-", "",trim($app->request()->params("num_cpf")))));
        $info['dt_nascimento'] = trim($app->request()->params("dt_nascimento"));
    
        if ($securityAPP->validateWithoutSecurity())
        {
            $usuario = new Usuario();
            $has_user = $usuario->getUsuarioPorCPF($info);
            $bloqueado = $usuario->getBloqueioPorCPF($info['num_cpf']);
           
            if($bloqueado[0] === true) /*tentamos buscar bloqueio existente pelo CPF informado*/
            {
                $retorno = array();
                $retorno['message'] = 'Usuário Bloqueado';
                $retorno['request_id'] = null;
                $retorno['results'][] = array('resource'=>'Forbidden','code'=>403);
                                        
                $json_data[] = $retorno;
                echo(json_encode($json_data));
            }
            else
            { /*Se não encontrar bloqueio anterior passamos para as demais validações*/
            if($has_user[0] === true )
            {
                $resultado = $usuario->gerarToken($info);
                $token = $usuario->gerarResetToken($info);
                $contato = $usuario->busca_contato($info);
                $retorno = array();
                $retorno['message'] = "Token enviado!";
                $retorno['request_id'] = $token;
                $retorno['results'][] = array('resource'=>'Ok','code'=>200);
    
                $sms = new SMS();
                $sms->montaMensagem($contato,'Use '.$resultado.' como codigo de seguranca para o aplicativo Conexao GEQ',$retorno['request_id']);
                
                $json_data[] = $retorno;
                echo(json_encode($json_data));
            }
            if($has_user[0] === false) /*Caso os dados estejam errados, tentamos fazer o bloqueio*/
            {
                $matricula = $usuario->getMatriculaPorCPF($info['num_cpf']);
                
                if($matricula[0] === true)
                {
                    //print_r('you must put me to blacklist and watch my steps... ');
                    $bloqueio = new Bloqueio();
                    $result_bloq = $bloqueio->adiciona($matricula[1]);

                    if($result_bloq == true)
                    {
                        //print_r('you added me to block list, but i am still going...');
                        $retorno = array();
                        $retorno['message'] = 'Ops! Dados incorretos';
                        $retorno['request_id'] = null;
                        $retorno['results'][] = array('resource'=>'Unauthorized','code'=>401);
                        
                        $json_data[] = $retorno;
                        echo(json_encode($json_data));
                    }

                    if ($result_bloq == false) 
                    {    
                        //print_r('you locked me, and i cant log again... ');
                        $usuario->insereBloqueio($matricula[1]);                    
                        $retorno = array();
                        $retorno['message'] = 'Usuário Bloqueado';
                        $retorno['request_id'] = null;
                        $retorno['results'][] = array('resource'=>'Forbidden','code'=>403);
                        
                        $json_data[] = $retorno;
                        echo(json_encode($json_data));                       
                    }
                   
                }
                if($matricula[0] === false)
                {
                    $retorno = array();
                    $retorno['message'] = 'Ops! Dados incorretos';
                    $retorno['request_id'] = null;
                    $retorno['results'][] = array('resource'=>'Unauthorized','code'=>401);
                    
                    $json_data[] = $retorno;
                    echo(json_encode($json_data));
                }
              


            }
            
        }
    
     
        }
    
    });

    /** 
     * @Author: Thiago Darlan - thiago.darlan@geq.com.br 
     * @Date: 2018-05-22 15:38:24 
     * @Desc: Função que envia SMS para o solicitante durante o processo de alteração de contato 
     *        registrado dentro do aplicativo
     * @see /change 
     */    

$app->post('/usuario/fone',function() use($app){
    $apikey = $app->request()->params('api_key') ? $app->request()->params('api_key') : $app->request()->headers('api_key');
    $signature = $app->request()->params('signature') ? $app->request()->params('signature') : $app->request()->headers('signature');

    $securityAPP = new Security($apikey, $signature);
    $securityAPP->loadApplication($app);
    $app->response()->header('Content-type', 'application/json; charset=utf-8');
    if ($securityAPP->validateWithoutSecurity())
    {
        $usuario = new Usuario();
        $sms = new Sms();
        $info['matricula'] = trim($app->request()->params('matricula'));
        $info['telefone'] = str_replace("-", "",str_replace(" ", "",str_replace(")", "",str_replace("(", "", trim($app->request()->params("telefone"))))));
        $changeable = $usuario->comparaContatos($info);
        if($changeable === true)
        {
            $resultado = $usuario->gerarTokenComCelular($info);
            $token = $usuario->gerarResetTokenComCelular($info);
            
            $sms->montaMensagem($info['telefone'],'Use '.$resultado.' como codigo de seguranca para o aplicativo Conexao GEQ',$token);

            $retorno = array();
            $retorno['message'] = 'Token Enviado!';
            $retorno['request_id'] = $token;
            $retorno['results'][] = array('resource'=>'Ok','code'=>200);
            
            $json_data[] = $retorno;
            echo(json_encode($json_data));
        }
        else
        {
            $retorno = array();
            $retorno['message'] = 'O telefone informado é o mesmo cadastrado!';
            $retorno['request_id'] = null;
            $retorno['results'][] = array('resource'=>'Not modified','code'=>302);
            
            $json_data[] = $retorno;
            echo(json_encode($json_data));
        }
        
    }

});

/** 
 * @Author: Thiago Darlan - thiago.darlan@geq.com.br 
 * @Date: 2018-05-22 15:40:56 
 * @Desc:  
 */

$app->post('/usuario/contato/altera',function() use($app){
    $apikey = $app->request()->params('api_key') ? $app->request()->params('api_key') : $app->request()->headers('api_key');
    $signature = $app->request()->params('signature') ? $app->request()->params('signature') : $app->request()->headers('signature');

    $securityAPP = new Security($apikey, $signature);
    $securityAPP->loadApplication($app);
    $app->response()->header('Content-type', 'application/json; charset=utf-8');
    if ($securityAPP->validateWithoutSecurity())
    {
        $usuario = new Usuario();
        
        $token = $app->request()->headers('access_token');
        $info['matricula'] = trim($app->request()->params('matricula'));
        $info['telefone'] = str_replace("-", "",str_replace(" ", "",str_replace(")", "",str_replace("(", "", trim($app->request()->params("telefone"))))));
        $info['token_reset'] = trim($app->request()->params('change_token'));
        $info['email'] = trim($app->request()->params('email'));
        $has_token = $usuario->valida_request($token);

        if($has_token === true)
        {
            $validacao = $usuario->validaToken($info);

            if($validacao[0] === true)
            {
                $dados = $usuario->cadastraDados($info);
                $usuario->bloqueiaToken($info['matricula']);
                $retorno = array();
                $retorno['message'] = 'Dados alterados com sucesso!';
                $retorno['results'][] = array('resource'=>'Ok','code'=>200);        
                $json_data[] = $retorno;
                echo(json_encode($json_data));
            }
            if($validacao[0] === false)
            {
                $retorno = array();
                $retorno['message'] = 'Token inválido';
                $retorno['results'][] = array('resource'=>'Unauthorized','code'=>401);        
                $json_data[] = $retorno;
                echo(json_encode($json_data));
            }
        }
        else
        {
            $retorno = array();
            $retorno['message'] = 'Ops! Dados incorretos';
            $retorno['results'][] = array('resource'=>'Unauthorized','code'=>401);        
            $json_data[] = $retorno;
            echo(json_encode($json_data));
        }
        
    }

});

$app->post('/senha/altera', function() use($app){

    $apikey = $app->request()->params('api_key') ? $app->request()->params('api_key') : $app->request()->headers('api_key');
    $signature = $app->request()->params('signature') ? $app->request()->params('signature') : $app->request()->headers('signature');

    $securityAPP = new Security($apikey, $signature);
    $securityAPP->loadApplication($app);
    $app->response()->header('Content-type', 'application/json; charset=utf-8');
    if ($securityAPP->validateWithoutSecurity())
    {
        $usuario = new Usuario();
        $info['hash'] = $app->request()->headers('access_token');
        $info['token_reset'] = trim($app->request()->params("token_reset"));
        $info['password'] = trim($app->request()->params("password"));
        $has_token = $usuario->valida_request($info['hash']);
        if($has_token === false)
        {
            
            $retorno = array();
            $retorno['message'] ='Ops! Token inválido';
            $retorno['results'][] = array('resource'=>'Bad request','code'=>400);

            $json_data[] = $retorno;
            echo(json_encode($json_data));
        }
        else
        {
           
            $validacao = $usuario->validaToken($info);            
            
            if($validacao[0] === false) /*verifica se é válido(is_valid = S e data_limite > SYSDATE)*/
            {
                $retorno = array();
                $retorno['message'] ='Ops! Token inválido';
                $retorno['results'][] = array('resource'=>'Forbidden','code'=>403);
    
                $json_data[] = $retorno;
                echo(json_encode($json_data));
            }
            else
            {    /*atualiza o usuário e invalida os demais tokens abertos para a matrícula*/
                $usuario->atualizaSenha($validacao[1],$info['password']);
                $usuario->bloqueiaToken($validacao[1]);                
               
                $retorno = array();
                $retorno['message'] ='Senha alterada com sucesso!';
                $retorno['results'][] = array('resource'=>'Ok','code'=>200);
    
                $json_data[] = $retorno;
                echo(json_encode($json_data));             
                
            }
        }
    }

});

$app->get('/parametros/:matricula',function($matricula) use($app){
	
    $apikey = $app->request()->params('api_key') ? $app->request()->params('api_key') : $app->request()->headers('api_key');
    $signature = $app->request()->params('signature') ? $app->request()->params('signature') : $app->request()->headers('signature');

    $securityAPP = new Security($apikey, $signature);
    $securityAPP->loadApplication($app);
    $app->response()->header('Content-type', 'application/json; charset=utf-8');
    if ($securityAPP->validateWithoutSecurity())
    {
        $usuario = new Usuario();
        $dados['dados_empresa'] = $usuario->parametrosEmpresa($matricula);
        $json_data = $dados;
        echo(json_encode($json_data));
        $securityAPP->setApiUtilizationResponse(json_encode($json_data));
    }

});

//$app->get('/usuario/:matricula',function($matricula) use ($app){
    $app->get('/usuario',function() use ($app){
    $apikey = $app->request()->params('api_key') ? $app->request()->params('api_key') : $app->request()->headers('api_key');
        $signature = $app->request()->params('signature') ? $app->request()->params('signature') : $app->request()->headers('signature');
    
        $securityAPP = new Security($apikey, $signature);
        $securityAPP->loadApplication($app);
        $app->response()->header('Content-type', 'application/json; charset=utf-8');
        if ($securityAPP->validateWithoutSecurity())
        {
           $usuario = new Usuario();
           $hash = $app->request()->headers('access_token');
           $matricula = $usuario->getMatriculaByHash($hash);
           //print_r('this is you id by hash: '.$matricula);
           //die(' and this is where it stops ...');
           $dados['dados_usuario'] = $usuario->getDadosFuncionario($matricula);
           $json_data[] = $dados;
           echo(json_encode($json_data));
           $securityAPP->setApiUtilizationResponse(json_encode($json_data));
        }
    
    });



$app->post('/atestado', function() use ($app){
    $apikey = $app->request()->params('api_key') ? $app->request()->params('api_key') : $app->request()->headers('api_key');
	$signature = $app->request()->params('signature') ? $app->request()->params('signature') : $app->request()->headers('signature');

	$securityAPP = new Security($apikey, $signature);
	$securityAPP->loadApplication($app);
    $app->response()->header('Content-type', 'application/json; charset=utf-8');
    if ($securityAPP->validateWithoutSecurity())
    {
        $info['matricula'] = "";
        $info['tipo'] = "";
        $info['contato'] = "";
        $info['telefone'] = "";
        $info['cid'] = "";
        $info['afastamento'] = "";
        $info['medico_afastamento'] = "";
        $info['medico_crm'] = "";
        $info['data_inicio'] = "";
        $info['data_fim'] = "";
        $info['pagamento'] = "";
        $info['data_pericia'] = "";
        $info['data_retorno'] = "";
        $info['qtd_tempo'] = "";
        $info['unid_tempo'] = "";
        $info['num_documento'] = "";
        $info['documento'] = "";

        $date_original = explode('/',$app->request()->params('data_retorno'));
       // date($date_original,'Y-m-d H:i:s');

        $date = new DateTime($date_original[2].'-'.$date_original[1]."-".$date_original[0]);
        $date_original = $date->format('Y-m-d H:i:s');

        $info['matricula']          = strtoupper($app->request()->params('matricula'));
        $info['tipo']               = strtoupper($app->request()->params('tipo'));       
        $info['contato']            = strtoupper($app->request()->params('nome_contato'));
        $info['telefone']           = $app->request()->params('telefone_contato');
        $info['cid']                = strtoupper($app->request()->params('cid'));
        $info['afastamento']        = $app->request()->params('motivo_afastamento');
        $info['medico_afastamento'] = strtoupper($app->request()->params('medico_atendimento'));
        $info['medico_crm']         = strtoupper($app->request()->params('medico_crm'));
        $info['data_inicio']        = $app->request()->params('data_inicio');
        $info['data_fim']           = $app->request()->params('data_fim');
        $info['pagamento']          = $app->request()->params('pagamento');
        $info['data_pericia']       = $app->request()->params('data_pericia');
        $info['data_retorno']       = $app->request()->params('data_retorno');
        $info['qtd_tempo']          = $app->request()->params('qtd_tempo');
        $info['unid_tempo']         = strtoupper($app->request()->params('unid_tempo'));
        $info['num_documento']      = $date_original;
        $info['tipo_arquivo']       = explode('.',$_FILES['documento']['name'])[1];
        $info['hash']               = $app->request()->params('hash');

       

    if(strtolower($info['tipo_arquivo'])=='png'||strtolower($info['tipo_arquivo'])=='jpg'||strtolower($info['tipo_arquivo'])=='jpeg')
    {
            $fileHandler = new FileHandler();
            $documentoConvertido = $fileHandler->fileConverter($_FILES);

            if($documentoConvertido['status']==true)
            {
            $usuario = new Usuario();
            $info['documento'] = $documentoConvertido['documento'];                        
            $atestado = new AtestadosApiRh();
           
           
            //$info['id'] = $atestado->getProximoIdAtestado();

            $info['colaborador'] = $usuario->getNome($info['matricula']);
           
            //$response = $atestado->insereNovo($info);
            
            $info['id'] = $atestado->insereNovo($info);
           
            //$securityAPP->setApiUtilizationResponse(json_encode($response));
            $mail = new Mail();
            $envio_email = $mail->emailOTRS($info);
            $securityAPP->setApiUtilizationResponse(json_encode($envio_email));
           
            $gravar_chamado = $atestado->insereChamado($info['id'], $info['matricula']);
            
            /* Buscando dados dos aparelhos para envio da notificação de recebimento do chamado */
           
           $params_aparelho = $usuario->getAparelho($info['matricula']);
           $securityAPP->setApiUtilizationResponse(json_encode($params_aparelho));

           /*notificação para android */
           
        if(!empty($params_aparelho) && $params_aparelho[0][1] == 2 )
        {
            $notificador = new FirebaseNotification();
            $dados_encontrados = array();
            $dados_encontrados[0][0] = 'Conexão Geq';
            $dados_encontrados[0][1] = 'Mensagem de Confirmação';
            $dados_encontrados[0][2] = $params_aparelho[0][0]; /*deviceId do aparelho que está abrindo o chamado*/
            $valor_badge = $usuario->badgeCounter($params_aparelho[0][2]);
            $params_msg = array(
                'title'		=> 'Notificação de Recebimento',
                'body'      => 'Recebemos sua solicitação. Continue acompanhando o seu status pelo aplicativo',
                'tag'       => (int) $valor_badge
                );
                if(!empty($dados_encontrados[0][2]))
                {
                    $envio_mensagem = $notificador->sendMessage($dados_encontrados, $params_msg);
                    $securityAPP->setApiUtilizationResponse(json_encode($envio_mensagem));
                }           
                                 
        }

        /*notificacao para IOS*/
        if(!empty($params_aparelho) && $params_aparelho[0][1] == 1 )
        {
            $notificador = new FirebaseNotification();
                $dados_encontrados = array();
                $dados_encontrados[0][0] = 'Conexão Geq';
                $dados_encontrados[0][1] = 'Mensagem de Confirmação';
                $dados_encontrados[0][2] = $params_aparelho[0][0]; /*deviceId do aparelho que está abrindo o chamado*/
                $valor_badge = $usuario->badgeCounter($params_aparelho[0][2]);
                $params_msg = array(
                    'title'		=> 'Notificação de Recebimento',
                    'body'      => 'Recebemos sua solicitação. Continue acompanhando o seu status pelo aplicativo',
                    'sound'     => 'default',
                    'badge'     => (int) $valor_badge
                    );
                     
                if(!empty($dados_encontrados[0][2]))
                {                   
                    $envio_mensagem = $notificador->sendMessage($dados_encontrados, $params_msg);
                    $securityAPP->setApiUtilizationResponse(json_encode($envio_mensagem));
                }            
                 
            }
            $securityAPP->setApiUtilizationResponse(json_encode($envio_email)); 
            echo($envio_email);           
        }     
    }    
    else
    {
        $retorno = array();
        $retorno['message'] = 'Erro ao processar arquivo';
        $retorno['results'] = array('resource'=>'Unsupported Media Type','code'=>415);
        $securityAPP->setApiUtilizationResponse(json_encode($retorno));
    }

                             
           
    }

});

$app->get('/atestado/foto/:hash',function($hash) use ($app){
    $apikey = $app->request()->params('api_key') ? $app->request()->params('api_key') : $app->request()->headers('api_key');
	$signature = $app->request()->params('signature') ? $app->request()->params('signature') : $app->request()->headers('signature');

	$securityAPP = new Security($apikey, $signature);
	$securityAPP->loadApplication($app);
	$app->response()->header('Content-type', 'image/jpeg');
    if ($securityAPP->validateWithoutSecurity())
    {
        $atestado = new AtestadosApiRh();
        $dados = $atestado->consultaImagem($hash);
        echo ($dados);
        $securityAPP->setApiUtilizationResponse(json_encode($dados));
    }


});


$app->get('/notificacoes/:tokenid',function($tokenid) use ($app){
    $apikey = $app->request()->params('api_key') ? $app->request()->params('api_key') : $app->request()->headers('api_key');
	$signature = $app->request()->params('signature') ? $app->request()->params('signature') : $app->request()->headers('signature');

	$securityAPP = new Security($apikey, $signature);
	$securityAPP->loadApplication($app);
	$app->response()->header('Content-type', 'application/json; charset=utf-8');
    if ($securityAPP->validateWithoutSecurity())
    {
        $atestado = new AtestadosApiRh();
        $dados = $atestado->getNotificacoesPorMatricula($tokenid);
        //$securityAPP->setApiUtilizationResponse(json_encode($dados));
        echo json_encode($dados);
    }
});

$app->get('/notificacoes/badge/:user',function($user) use($app){
    $apikey = $app->request()->params('api_key') ? $app->request()->params('api_key') : $app->request()->headers('api_key');
	$signature = $app->request()->params('signature') ? $app->request()->params('signature') : $app->request()->headers('signature');

	$securityAPP = new Security($apikey, $signature);
	$securityAPP->loadApplication($app);
	$app->response()->header('Content-type', 'application/json; charset=utf-8');
    if ($securityAPP->validateWithoutSecurity())
    {
        $usuario = new Usuario();
        $dados[] = $usuario->badgeReset($user);
        echo json_encode($dados);
    }

});


$app->get('/notificacoes/gerencia/:cod_gerencia',function($cod_gerencia) use ($app){
        
    $apikey = $app->request()->params('api_key') ? $app->request()->params('api_key') : $app->request()->headers('api_key');
	$signature = $app->request()->params('signature') ? $app->request()->params('signature') : $app->request()->headers('signature');

    $access_token  = $app->request()->headers('access_token');
    
	$securityAPP = new Security($apikey, $signature);
	$securityAPP->loadApplication($app);
	$app->response()->header('Content-type', 'application/json; charset=utf-8');
    if ($securityAPP->validateWithoutSecurity())
    {
        $atestado = new AtestadosApiRh();
        $dados = $atestado->getNotificacoesPorGerencia($cod_gerencia,$access_token);
        //$securityAPP->setApiUtilizationResponse(json_encode($dados));     
        echo json_encode($dados);
    }


});

$app->get('/atestado/:matricula',function($matricula) use ($app){
    $apikey = $app->request()->params('api_key') ? $app->request()->params('api_key') : $app->request()->headers('api_key');
	$signature = $app->request()->params('signature') ? $app->request()->params('signature') : $app->request()->headers('signature');

	$securityAPP = new Security($apikey, $signature);
	$securityAPP->loadApplication($app);
	$app->response()->header('Content-type', 'application/json; charset=utf-8');
    if ($securityAPP->validateWithoutSecurity())
    {
        $atestado = new AtestadosApiRh();
        $dados = $atestado->getAtestadosPorMatricula($matricula);
        echo (json_encode($dados));
    }
});

$app->get('/gerencia/:cod_gerencia', function($cod_gerencia) use($app){

 $apikey = $app->request()->params('api_key') ? $app->request()->params('api_key') : $app->request()->headers('api_key');
	$signature = $app->request()->params('signature') ? $app->request()->params('signature') : $app->request()->headers('signature');
    
    $access_token  = $app->request()->headers('access_token');
	$securityAPP = new Security($apikey, $signature);
	$securityAPP->loadApplication($app);
	$app->response()->header('Content-type', 'application/json; charset=utf-8');
    if ($securityAPP->validateWithoutSecurity())
    {
        $atestado = new AtestadosApiRh();
        $dados = $atestado->getAtestadosPorGerencia($cod_gerencia,$access_token);
        echo(json_encode($dados));
    }


});

$app->post('/contato',function() use($app){
    $apikey = $app->request()->params('api_key') ? $app->request()->params('api_key') : $app->request()->headers('api_key');
    $signature = $app->request()->params('signature') ? $app->request()->params('signature') : $app->request()->headers('signature');

    $securityAPP = new Security($apikey, $signature);
    $securityAPP->loadApplication($app);
    $app->response()->header('Content-type', 'application/json; charset=utf-8');
    if ($securityAPP->validateWithoutSecurity())
    {
        $usuario = new Usuario();
        $info['matricula']          = $app->request()->params('matricula');
        $info['telefone']           = $app->request()->params('telefone');       
        $info['email']              = $app->request()->params('email');
        
        $dados = $usuario->cadastraDados($info);
        //echo($dados);
        $retorno = array();
        $retorno['message'] ='Dados atualizados com sucesso';
        $retorno['results'] = array('resource'=>'Ok','code'=>200);
        $json_data[] = $retorno;
           
        echo(json_encode($json_data));
        
    }  

});

$app->get('/alteracao', function() use($app){
    
     $apikey = $app->request()->params('api_key') ? $app->request()->params('api_key') : $app->request()->headers('api_key');
        $signature = $app->request()->params('signature') ? $app->request()->params('signature') : $app->request()->headers('signature');
    
        $securityAPP = new Security($apikey, $signature);
        $securityAPP->loadApplication($app);
        $app->response()->header('Content-type', 'application/json; charset=utf-8');
        if ($securityAPP->validateWithoutSecurity())
        {
            echo('here');
            exit(0);
           // $usuario = new Usuario();
           // $dados = $usuario->getUsuarioPorGerencia($cd_ccusto);
            //$securityAPP->setApiUtilizationResponse(json_encode($dados));
           // echo(json_encode($dados));
        }   
    });



$app->get('/funcionario/gerencia/:cd_ccusto', function($cd_ccusto) use($app){
    
     $apikey = $app->request()->params('api_key') ? $app->request()->params('api_key') : $app->request()->headers('api_key');
        $signature = $app->request()->params('signature') ? $app->request()->params('signature') : $app->request()->headers('signature');
    
        $securityAPP = new Security($apikey, $signature);
        $securityAPP->loadApplication($app);
        $app->response()->header('Content-type', 'application/json; charset=utf-8');
        if ($securityAPP->validateWithoutSecurity())
        {
            $usuario = new Usuario();
            $dados = $usuario->getUsuarioPorGerencia($cd_ccusto);
            //$securityAPP->setApiUtilizationResponse(json_encode($dados));
            echo(json_encode($dados));
        }   
    });

    $app->post('/notificacoes/deletar', function() use ($app){
        $apikey = $app->request()->params('api_key') ? $app->request()->params('api_key') : $app->request()->headers('api_key');
        $signature = $app->request()->params('signature') ? $app->request()->params('signature') : $app->request()->headers('signature');
        $lista_id_notificacoes = $app->request()->getBody();
        $securityAPP = new Security($apikey, $signature);
        $securityAPP->loadApplication($app);
        $app->response()->header('Content-type', 'application/json; charset=utf-8');
        if ($securityAPP->validateWithoutSecurity())
        {
            $atestado = new AtestadosApiRh();
            $dados = $atestado->DesalibitaNotificacoes($lista_id_notificacoes);
            echo(json_encode($dados));
        }
    });

    $app->get('/notificacoes/recuperar/:id_notificacao', function($id_notificacao) use ($app){
        $apikey = $app->request()->params('api_key') ? $app->request()->params('api_key') : $app->request()->headers('api_key');
        $signature = $app->request()->params('signature') ? $app->request()->params('signature') : $app->request()->headers('signature');
    
        $securityAPP = new Security($apikey, $signature);
        $securityAPP->loadApplication($app);
        $app->response()->header('Content-type', 'application/json; charset=utf-8');
        if ($securityAPP->validateWithoutSecurity())
        {
            $atestado = new AtestadosApiRh();
            $dados = $atestado->HalibitaNotificacoes($id_notificacao);
            //$securityAPP->setApiUtilizationResponse(json_encode($dados));
            echo(json_encode($dados));
        }

    });

    $app->post('/device', function() use ($app){
        
        $apikey = $app->request()->headers('api_key');
	    $signature = $app->request()->headers('signature');
	    $access_token  = $app->request()->headers('access_token');
        
        $securityAPP = new Security($apikey, $signature);
        $securityAPP->loadApplication($app);
        $app->response()->header('Content-type', 'application/json; charset=utf-8 ');
        if($securityAPP->validateWithoutSecurity())
        {
            $info['tipo']     = $app->request()->params('tipo');
            $info['user']     = $app->request()->params('user');
            $info['aparelho'] = $app->request()->params('aparelho');
            $atestado = new AtestadosApiRh();
            $dados = $atestado->atualizaAparelho($info);
            echo(json_encode($dados));
        }
    });

    $app->get('/afastamento',function() use($app){
        $apikey = $app->request()->headers('api_key');
	    $signature = $app->request()->headers('signature');
	    $access_token  = $app->request()->headers('access_token');
        $app->response()->header('Content-type', 'application/json; charset=utf-8');
        $securityAPP = new Security($apikey, $signature);
        $securityAPP->loadApplication($app);

        if ($securityAPP->validateWithoutSecurity())
        {
            
            echo('[
                    {
                    "resultCount":"3",
                    "descricao":
                        [
                            "Acidente de trabalho",
                            "Doença",
                            "Licença maternidade"
                        ]
                    }
                ]');

		}

    });
   

    $app->get('/feriados', function() use($app){
        $apikey = $app->request()->headers('api_key');
	    $signature = $app->request()->headers('signature');
	    $access_token  = $app->request()->headers('access_token');
        $app->response()->header('Content-type', 'application/json; charset=utf-8');
        $securityAPP = new Security($apikey, $signature);
        $securityAPP->loadApplication($app);

        if ($securityAPP->validateWithoutSecurity())
        {
            $usuario = new Usuario();
            $lista_feriados = array();
            $lista_feriados = $usuario->getFeriados($access_token);
           echo (json_encode($lista_feriados));
        }

    });  
   


/**
 * Step 4: Run the Slim application
 *
 * This method should be called last. This executes the Slim application
 * and returns the HTTP response to the HTTP client.
 */

$app->run();

?>