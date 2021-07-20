<html>
<head>
<title>Sistema de Logistica Integrada</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<?php $url = base_url();?>

<link rel="stylesheet" type="text/css" href="<?php echo $url;?>css/login.css" />

</head>

<body>

<div class="tudo">
                    <div class="header">
                    
                        <div>
                        </div>
                    
                    </div>
	
		<div class="conteudo">
    
       
				<?php echo form_open("login/validSession", "name='form'");?>

						<div class="form-login">
                        
                          <div class="login">
                          
                          <font size="1" face="Verdana, Arial, Helvetica, sans-serif" color="#FF0000"><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php if(isset($msn)){echo $msn;}?></font></strong></font> <br />
                          
                            <span>Usuario: </span>
                           	 	<input name="user" type="text" id="user"><br />
							<span>Senha: &nbsp;&nbsp; </span>
                            	<input name="password" type="password" id="password"> <br />
                                 <span class="button"><input type="image" value="ok" width="94px" style="width:94px; border:none;" src="<?php echo $url; ?>images/entrar-btn2.png"/></span>
         
                                <input name="flag" type="hidden" id="flag" value="verify">
                                <input name="idSystem" type="hidden" id="idSystem" value="56">

                          </div>
                          
						</div>
								</form>
	
		</div>

                  <div class="rodape">
                                    
                            <p align="center"> Sistemas Esmaltec - Nome do Sistema </p>
                                        
                            <div>
                            </div>
                                        
                    </div>
    
</div>

</body>
</html>
