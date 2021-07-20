<?php

require_once 'Classes/class.Oracle.php';
require_once 'Classes/class.Security.php';
require_once 'Classes/class.Usuario.php';
require_once 'Classes/class.Bloqueio.php';
require_once 'Classes/class.SOA.php';
require_once 'Classes/class.AtestadosApiRh.php';
require_once 'Classes/PHPMailer/PHPMailerAutoload.php';
require_once 'Classes/class.OTRS.php';
require_once 'Classes/class.Mail.php';
require_once 'Classes/class.Token.php';
require_once 'Classes/class.FirebaseNotification.php';
require_once 'Classes/class.FileHandler.php';
require_once 'Classes/class.SMS.php';
require_once 'Classes/vendor/autoload.php';

try {
    $dotenv = new Dotenv\Dotenv($_SERVER["DOCUMENT_ROOT"]);
    $dotenv->load();
	
	
} catch (Exception $e) {
    exit('Could not find a .env file.');
}
// VALID PHP FILE
?>