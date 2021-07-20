<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Classe FuncaoValidar
 * Funções estáticos para validações
 * @package class\esmaltec\
 * @author Leandro Pedrosa Rodrigues <pedrosalpr@gmail.com>
 * @copyright Leandro Pedrosa Rodrigues, Esmaltec
 * @version 2.0 FuncaoValidar.class.php 11/12/2013
 */
class FuncaoValidar {
  
  /**
   * Verifica se a variável está setada
   * @static
   * @param mixed $key A variável que será verificado se está setado
   * @return mixed|NULL Retorna a variável ou nulo
   * @author Leandro Pedrosa Rodrigues <pedrosalpr@gmail.com>
   * @since 1.0
   */
  public static function getPost($key) {
    return isset($_POST[$key]) ? $_POST[$key] : NULL;
  }

  /**
   * Valida se o valor é decimal
   * @static
   * @param mixed $valor
   * @return boolean
   * @author Leandro Pedrosa Rodrigues <pedrosalpr@gmail.com>
   * @since 1.0
   */
  public static function validaDecimal($valor) {
    $pattern = "/^\d*[0-9](\.\d*[0-9])?$/";
    return (preg_match($pattern, $valor)) ? true : false;
  }
  
  
  /**
   * Valida se o valor padrão americano é decimal
   * @static
   * @param mixed $valor
   * @return boolean
   * @author Leandro Pedrosa Rodrigues <pedrosalpr@gmail.com>
   * @since 1.0
   */
  public static function validaDecimalUSA($valor) {
    $pattern = "/^((\d{1,3},)*(\d{3})|(\d{1,3}))((\.)(\d+))?$/";
    return (preg_match($pattern, $valor)) ? true : false;
  }
  
  /**
   * Valida se o valor padrão brasileiro é decimal
   * @static
   * @param mixed $valor
   * @return boolean
   * @author Leandro Pedrosa Rodrigues <pedrosalpr@gmail.com>
   * @since 1.0
   */
  public static function validaDecimalBR($valor) {
    $pattern = "/^((\d{1,3}\.)*(\d{3})|(\d{1,3}))((,)(\d+))?$/";
    return (preg_match($pattern, $valor)) ? true : false;
  }

  /**
   * Valida se o valor está no formato de um preço
   * @static
   * @param mixed $valor
   * @return boolean
   * @author Leandro Pedrosa Rodrigues <pedrosalpr@gmail.com>
   * @since 1.0
   */
  public static function validaPreco($valor) {
    $pattern = "/^\d*[0-9](\.\d{2}?)$/";
    return (preg_match($pattern, $valor)) ? true : false;
  }

  /**
   * Valida se o valor é do tipo Hexadecimal
   * @static
   * @param mixed $valor
   * @return boolean
   * @author Leandro Pedrosa Rodrigues <pedrosalpr@gmail.com>
   * @since 1.0
   */
  public static function validaCorHexadecimal($valor) {
    $pattern = "/^#?([a-f]|[A-F]|[0-9]){3}(([a-f]|[A-F]|[0-9]){3})?$/";
    return (preg_match($pattern, $valor)) ? true : false;
  }

  /**
   * Valida se o valor está no formato de hora (24:00)
   * @static
   * @param mixed $valor
   * @return boolean
   * @author Leandro Pedrosa Rodrigues <pedrosalpr@gmail.com>
   * @since 1.0
   */
  public static function validaHora($valor) {
    $pattern = "/^([0-1][0-9]|[2][0-3])(:([0-5][0-9])){1,2}$/";
    return (preg_match($pattern, $valor)) ? true : false;
  }

  /**
   * Valida o nome do arquivo
   * Aceita apenas caracteres alfanumérico não acentuado, além de "." e "_"
   * @static
   * @param string $valor O nome do documento. 
   * @param array $extensao Extensão do arquivo "zip","pdf": array("zip","pdf")
   * @return boolean
   * @author Leandro Pedrosa Rodrigues <pedrosalpr@gmail.com>
   * @since 1.0
   */
  public static function validaArquivoDocumento($valor, $extensao) {
    $ext = implode('|', $extensao);
    $pattern = "/^[a-zA-Z0-9-_\.]+\.(" . $ext . ")$/";
    return (preg_match($pattern, $valor)) ? true : false;
  }

  /**
   * Valida o tamanho do arquivo em bytes
   * @static
   * @param string $arquivo Caminho do arquivo
   * @param int $tamanho Tamanho do arquivo em bytes
   * @return boolean
   * @author Leandro Pedrosa Rodrigues <pedrosalpr@gmail.com>
   * @since 1.0
   */
  public static function validaTamanhoArquivo($arquivo, $tamanho) {
    $tam = filesize($arquivo);
    return ($tam <= $tamanho) ? true : false;
  }

  /**
   * Valida a dimensão da imagem de uma foto
   * @static
   * @param string $foto Caminho do diretório da foto
   * @param int $largura Tamanho da largura
   * @param int $altura Tamanho da altura
   * @return boolean
   * @author Leandro Pedrosa Rodrigues <pedrosalpr@gmail.com>
   * @since 1.0
   */
  public static function validaDimensaoImagem($foto, $largura, $altura) {
    list($width, $height) = @getimagesize($foto);
    if ($width > $largura) {
      return false;
    }
    if ($height > $altura) {
      return false;
    }
    return true;
  }

  /**
   * Valida se o valor passado não está vazio.
   * @static
   * @param mixed $valor
   * @return boolean
   * @author Leandro Pedrosa Rodrigues <pedrosalpr@gmail.com>
   * @since 1.0
   */
  public static function validaValor($valor) {
    return ($valor != '') ? true : false;
  }

  /**
   * Valida se o valor passado é um ano.
   * Apenas verifica se tem 4 dígitos
   * @static
   * @param mixed $valor
   * @return boolean
   * @author Leandro Pedrosa Rodrigues <pedrosalpr@gmail.com>
   * @since 1.0
   */
  public static function validaAno($valor) {
    $pattern = "/^\d{4}$/";
    return (preg_match($pattern, $valor)) ? true : false;
  }

  /**
   * Verifica se o email é valido
   * @static
   * @param string $email
   * @return boolean
   * @author Leandro Pedrosa Rodrigues <pedrosalpr@gmail.com>
   * @since 1.0
   */
  public static function validaEmail($email) {
    $pattern = "/^([0-9a-zA-Z]+([_.-]?[0-9a-zA-Z]+)*@[0-9a-zA-Z]+[0-9,a-z,A-Z,.,-]*(.){1}[a-zA-Z]{2,4})+$/";
    return (preg_match($pattern, $email)) ? true : false;
  }

  /**
   * Verifica se o valor é uma data no formato DD/MM/YYYY
   * @static
   * @param string $valor
   * @return boolean
   * @author Leandro Pedrosa Rodrigues <pedrosalpr@gmail.com>
   * @since 1.0
   */
  public static function validaData($valor) {
    $pattern = "/^([0,1,2][0-9]|3[0,1])\/(0[0-9]|1[0,1,2])\/\d{4}$/";
    return (preg_match($pattern, $valor)) ? true : false;
  }

  /**
   * Verifica se o valor é uma placa automotiva brasileira
   * @static
   * @param string $valor
   * @return boolean
   * @author Leandro Pedrosa Rodrigues <pedrosalpr@gmail.com>
   * @since 1.0
   */
  public static function validaPlaca($valor) {
    $pattern = "/^[A-Za-z]{3}-[\d]{4}$/";
    return (preg_match($pattern, $valor)) ? true : false;
  }

  /**
   * Valida se o CPF está no formato 000.000.000-00
   * @static
   * @param string $valor
   * @return boolean
   * @author Leandro Pedrosa Rodrigues <pedrosalpr@gmail.com>
   * @since 1.0
   */
  public static function validaCPF($valor) {
    $pattern = "/^\d{3}.\d{3}.\d{3}-\d{2}$/";
    return (preg_match($pattern, $valor)) ? true : false;
  }

  /**
   * Valida se o CEP está no formato 00000-000
   * @static
   * @param string $valor
   * @return boolean
   * @author Leandro Pedrosa Rodrigues <pedrosalpr@gmail.com>
   * @since 1.0
   */
  public static function validaCEP($valor) {
    $pattern = "/^\d{5}-\d{3}$/";
    return (preg_match($pattern, $valor)) ? true : false;
  }

  /**
   * Valida se o CNPJ está no formato 99.999.999/9999-99
   * @static
   * @param string $valor
   * @return boolean
   * @author Leandro Pedrosa Rodrigues <pedrosalpr@gmail.com>
   * @since 1.0
   */
  public static function validaCNPJ($valor) {
    $pattern = "/^\d{2}\.\d{3}\.\d{3}\/\d{4}\-\d{2}$/";
    return (preg_match($pattern, $valor)) ? true : false;
  }

  /**
   * Valida se o telefone está no formato (XX) XXXX-XXXX 
   * @static
   * @param string $telefone
   * @return boolean
   * @author Leandro Pedrosa Rodrigues <pedrosalpr@gmail.com>
   * @since 1.0
   */
  public static function validaTelefone($telefone) {
    $pattern = "/^\(?\d{2}\)?[\s-]?[\d{1}]?\d{4}-?\d{4}$/";
    return (preg_match($pattern, $telefone)) ? true : false;
  }

  /**
   * Valida se o nome está correto contendo apenas caracteres alfa
   * @static
   * @param string $valor
   * @return boolean
   * @author Leandro Pedrosa Rodrigues <pedrosalpr@gmail.com>
   * @since 1.0
   */
  public static function validaNome($valor) {
    $pattern = "/^[a-zA-Z' ']+$/";
    return (preg_match($pattern, $valor)) ? true : false;
  }

  /**
   * Valida o login para conter caracteres alfanuméricos e '._'
   * @static
   * @param string $valor Login a ser validado
   * @return boolean
   * @author Leandro Pedrosa Rodrigues <pedrosalpr@gmail.com>
   * @since 1.0
   */
  public static function validaLogin($valor) {
    $pattern = "/^([a-zA-Z]+([_.]?[0-9a-zA-Z]+))+$/";
    return (preg_match($pattern, $valor)) ? true : false;
  }

  /**
   * Valida a senha para conter caracteres alfanumuricos e '._$#@%'
   * @static
   * @param string $valor
   * @return boolean
   * @author Leandro Pedrosa Rodrigues <pedrosalpr@gmail.com>
   * @since 1.0
   */
  public static function validaSenha($valor) {
    $pattern = "/^([a-zA-Z]+([_.$#@%]?[0-9a-zA-Z]+))+$/";
    return (preg_match($pattern, $valor)) ? true : false;
  }

  /**
   * Valida se o valor é sim ou nao
   * @static
   * @param string $valor
   * @return boolean
   * @author Leandro Pedrosa Rodrigues <pedrosalpr@gmail.com>
   * @since 1.0
   */
  public static function validaAtivo($valor) {
    $pattern = "/^(nao|sim)$/";
    return (preg_match($pattern, $valor)) ? true : false;
  }

  /**
   * Valida se o valor é sim ou nao
   * @static
   * @param string $valor
   * @return boolean
   * @author Leandro Pedrosa Rodrigues <pedrosalpr@gmail.com>
   * @since 1.0
   */
  public static function validaNaoSim($valor) {
    $pattern = "/^(nao|sim)$/";
    return (preg_match($pattern, $valor)) ? true : false;
  }

  /**
   * Valida se o valor é uma Seção válida
   * @static
   * @param string $valor
   * @return boolean
   * @author Leandro Pedrosa Rodrigues <pedrosalpr@gmail.com>
   * @since 1.0
   */
  public static function validaSecao($valor) {
    $pattern = "/^[a-zA-Z]+$/";
    return (preg_match($pattern, $valor)) ? true : false;
  }

  /**
   * Valida se o valor é um nome de arquivo válido
   * @static
   * @param string $valor
   * @return boolean
   * @author Leandro Pedrosa Rodrigues <pedrosalpr@gmail.com>
   * @since 1.0
   */
  public static function validaNomeArquivo($valor) {
    $pattern = "/^[a-zA-Z_-]+$/";
    return (preg_match($pattern, $valor)) ? true : false;
  }

  /**
   * Valida se o valor é M - Masculino ou F - Feminino
   * @static
   * @param string $valor
   * @return boolean
   * @author Leandro Pedrosa Rodrigues <pedrosalpr@gmail.com>
   * @since 1.0
   */
  public static function validaSexo($valor) {
    $pattern = "/^(M|F)$/";
    return (preg_match($pattern, $valor)) ? true : false;
  }
	
	/**
   * Valida o tempo
   * @static
   * @param string $valor
   * @return boolean
   * @author Leandro Pedrosa Rodrigues <pedrosalpr@gmail.com>
   * @since 1.0
   */
  public static function validaTempo($valor) {
    $pattern = "/^([0-1][0-9]|[2][0-3])(:([0-5][0-9])){1,2}$/";
    return (preg_match($pattern, $valor)) ? true : false;
  }

  /**
   * Valida a quantidade de dígitos de um valor
   * @static
   * @param int $valor
   * @param int $qtd
   * @return boolean
   * @author Leandro Pedrosa Rodrigues <pedrosalpr@gmail.com>
   * @since 1.0
   */
  public static function validaDigitos($valor, $qtd) {
    return preg_match('/\d{' . $qtd . '}$/', $valor) ? true : false;
  }

  /**
   * Valida conjunto de Códigos
   * @static
   * @param mixed $valor 2 ou 2,3,4,5,6,7
   * @return boolean
   * @author Leandro Pedrosa Rodrigues <pedrosalpr@gmail.com>
   * @since 1.0
   */
  public static function validaCodigo($valor) {
    $pattern = "/^\d+$/";
    $cont_virgula = substr_count($valor, ',');
    if ($cont_virgula > 0) {
      $explode = explode(',', $valor);
      $array = array();
      foreach ($explode as $digito) {
        $array[] = preg_match($pattern, $digito) ? true : false;
      }
      if (in_array(false, $array)) {
        return false;
      } else {
        return true;
      }
    } else {
      return preg_match($pattern, $valor) ? true : false;
    }
  }

  /**
   * Valida se o valor é um dígito
   * @static
   * @param mixed $valor
   * @return boolean
   * @author Leandro Pedrosa Rodrigues <pedrosalpr@gmail.com>
   * @since 1.0
   */
  public static function validaDigito($valor) {
    $pattern = "/^\d+$/";
    return preg_match($pattern, $valor) ? true : false;
  }

  /**
   * Valida se o número está no intervalo - Maior e igual
   * @static
   * @param int $valor
   * @param int $min
   * @param int $max
   * @return boolean
   * @author Leandro Pedrosa Rodrigues <pedrosalpr@gmail.com>
   * @since 1.0
   */
  public static function validaIntervalo($valor, $min, $max) {
    return ($valor >= $min && $valor <= $max) ? true : false;
  }

  /**
   * Valida se o número é maior de que um valor
   * @static
   * @param int $valor Valor a ser comparado
   * @param int $max Valor Máximo
   * @return boolean
   * @author Leandro Pedrosa Rodrigues <pedrosalpr@gmail.com>
   * @since 1.0
   */
  public static function validaMaximo($valor, $max) {
    return ($valor <= $max) ? true : false;
  }

  /**
   * Valida se o número é menor de que um valor
   * @static
   * @param int $valor Valor a ser comparado
   * @param int $min Valor mínimo
   * @return boolean
   * @author Leandro Pedrosa Rodrigues <pedrosalpr@gmail.com>
   * @since 1.0
   */
  public static function validaMinimo($valor, $min) {
    return ($valor >= $min) ? true : false;
  }

  /**
   * Valida a quantidade de caracteres num intervalo
   * @static
   * @param mixed $valor Conjunto de palavras ou frases
   * @param int $min Valor mínimo
   * @param int $max Valor Máximo
   * @return boolean
   * @author Leandro Pedrosa Rodrigues <pedrosalpr@gmail.com>
   * @since 1.0
   */
  public static function validaQuantidadeDeCaracteres($valor, $min, $max) {
    return (strlen($valor) >= $min && strlen($valor) <= $max) ? true : false;
  }

  /**
   * Valida se o valor passado é um estado Brasileiro
   * @static
   * @param string $valor O estado brasileiro
   * @return boolean
   * @author Leandro Pedrosa Rodrigues <pedrosalpr@gmail.com>
   * @since 1.0
   */
  public static function validaEstadoBR($valor) {
    $pattern = "/^(AC|AL|AP|AM|BA|CE|DF|ES|GO|MA|MT|MS|MG|PA|PB|PR|PE|PI|RJ|RN|RS|RO|RR|SC|SP|SE|TO)$/";
    return (preg_match($pattern, $valor)) ? true : false;
  }

  /**
   * Valida se o valor passado é um código de uma cidade brasileira
   * @static
   * @param int $valor Código da cidade
   * @return boolean
   * @author Leandro Pedrosa Rodrigues <pedrosalpr@gmail.com>
   * @since 1.0
   */
  public static function validaCidadeBR($valor) {
    return ($valor >= 1 && $valor <= 5507) ? true : false;
  }

  /**
   * Retorna o hash
   * Método do captcha
   * @static
   * @param string $value
   * @return string
   * @author Leandro Pedrosa Rodrigues <pedrosalpr@gmail.com>
   * @since 1.0
   */
  function rpHash($value) {
    $hash = 5381;
    $value = strtoupper($value);
    for ($i = 0; $i < strlen($value); $i++) {
      $hash = (($hash << 5) + $hash) + ord(substr($value, $i));
    }
    return $hash;
  }

  /**
   * Retorna o hash
   * Método do captcha para 64bits
   * @static
   * @param string $value
   * @return string
   * @author Leandro Pedrosa Rodrigues <pedrosalpr@gmail.com>
   * @since 1.0
   */
  public static function rpHash64($value) {
    $hash = 5381;
    $value = strtoupper($value);
    for ($i = 0; $i < strlen($value); $i++) {
      $hash = (self::leftShift32($hash, 5) + $hash) + ord(substr($value, $i));
    }
    return $hash;
  }

  /**
   * Perform a 32bit left shift
   * Utilizado pelo rpHash64
   * @static
   * @param int $number
   * @param int $steps
   * @return string
   * @author Leandro Pedrosa Rodrigues <pedrosalpr@gmail.com>
   * @since 1.0
   */
  public static function leftShift32($number, $steps) {
    // convert to binary (string) 
    $binary = decbin($number);
    // left-pad with 0's if necessary 
    $binary = str_pad($binary, 32, "0", STR_PAD_LEFT);
    // left shift manually 
    $binary = $binary . str_repeat("0", $steps);
    // get the last 32 bits 
    $binary = substr($binary, strlen($binary) - 32);
    // if it's a positive number return it 
    // otherwise return the 2's complement 
    return ($binary{0} == "0" ? bindec($binary) :
                    -(pow(2, 31) - bindec(substr($binary, 1))));
  }

  /**
   * Verifica se o cpf é valido
   * @static
   * @param string $cpf
   * @return boolean
   * @author Leandro Pedrosa Rodrigues <pedrosalpr@gmail.com>
   * @since 1.0
   */
  public static function verificaCPF($cpf) {
    if (!self::validaCPF($cpf)) {
      return false;
    } else {
      $cpf = str_replace('.', '', $cpf);
      $cpf = str_replace('-', '', $cpf);

      if (strlen($cpf) != 11 || $cpf == "00000000000" || $cpf == "11111111111" ||
              $cpf == "22222222222" || $cpf == "33333333333" || $cpf == "44444444444" ||
              $cpf == "55555555555" || $cpf == "66666666666" || $cpf == "77777777777" ||
              $cpf == "88888888888" || $cpf == "99999999999") {
        return false;
      }

      $soma = 0;
      for ($i = 0; $i < 9; $i++) {
        $soma += substr($cpf, $i, 1) * (10 - $i);
      }
      $resto = 11 - ($soma % 11);
      if ($resto == 10 || $resto == 11)
        $resto = 0;
      if ($resto != substr($cpf, 9, 1)) {
        return false;
      }

      $soma = 0;
      for ($i = 0; $i < 10; $i++)
        $soma += substr($cpf, $i, 1) * (11 - $i);
      $resto = 11 - ($soma % 11);
      if ($resto == 10 || $resto == 11)
        $resto = 0;
      if ($resto != substr($cpf, 10, 1)) {
        return false;
      }
      return true;
    }
  }

  /**
   * Método para validar a data hora no formato brasileiro
   * @static
   * @param string $data_hora Data e hora no formato brasileiro (20/08/2011 21:00:00)
   * @return boolean Retorna true se é a string é válido, caso contrário, falso
   * @author Leandro Pedrosa Rodrigues <pedrosalpr@gmail.com>
   * @version 2.0 
   * @since 2.0 
   */
  public static function validaDataHoraBR($data_hora) {
    $pattern = "/^([1-9]|0[1-9]|[1,2][0-9]|3[0,1])/(0[1-9]|1[0,1,2])/\d{4} ([0-1][0-9]|[2][0-3])(:([0-5][0-9])){1,2}(:([0-5][0-9])){1,2}$/";
    $pattern2 = "#^([1-9]|0[1-9]|[1,2][0-9]|3[0,1])/(0[1-9]|1[0,1,2])/\d{4} ([0-1][0-9]|[2][0-3])(:([0-5][0-9])){1,2}(:([0-5][0-9])){1,2}$#";
    return (preg_match($pattern2, $data_hora)) ? true : false;
  }
  
  
  public static function validaDataHoraBR2($data_hora) {
    $pattern = "/^([1-9]|0[1-9]|[1,2][0-9]|3[0,1])/(0[1-9]|1[0,1,2])/\d{4} ([0-1][0-9]|[2][0-3])(:([0-5][0-9])){1,2}(:([0-5][0-9])){1,2}$/";
    $pattern2 = "#^([1-9]|0[1-9]|[1,2][0-9]|3[0,1])/(0[1-9]|1[0,1,2])/\d{4} ([0-1][0-9]|[2][0-3])(:([0-5][0-9])){1,2}(:([0-5][0-9])){1,2}$#";
    return (preg_match($pattern, $data_hora)) ? true : false;
  }

  /**
   * Método para validar a data hora no formato SQL
   * @static
   * @param string $data_hora Data e hora no formato sql (2011-08-20 21:00:00)
   * @return boolean 
   * @author Leandro Pedrosa Rodrigues <pedrosalpr@gmail.com>
   * @version 2.0 
   * @since 2.0 
   */
  public static function validaDataHoraSQL($data_hora) {
    $pattern = "/^\d{4}-(0[0-9]|1[0,1,2])-([0,1,2][0-9]|3[0,1]) ([0-1][0-9]|[2][0-3])(:([0-5][0-9])){1,2}(:([0-5][0-9])){1,2}$/";
    if (preg_match($pattern, $data_hora)) {
      return true;
    } else {
      return false;
    }
  }

  /**
   * Método para validar a data no formato brasileiro
   * @static
   * @param string $data Data no formato brasileiro (20/08/2011)
   * @return boolean 
   * @author Leandro Pedrosa Rodrigues <pedrosalpr@gmail.com>
   * @version 1.0 
   * @since 1.0 
   */
  public static function validaDataBR($data) {
    //$pattern = "/^([1-9]|0[1-9]|[1,2][0-9]|3[0,1])\/([1-9]|1[0,1,2])\/\d{4}$/";
    $pattern = "/^([0,1,2][0-9]|3[0,1])\/(0[0-9]|1[0,1,2])\/\d{4}$/";
    return (preg_match($pattern, $data)) ? true : false;
  }

  /**
   * Método para validar a data no formato SQL
   * @static
   * @param string $data Data no formato sql (2011-08-20)
   * @return boolean 
   * @author Leandro Pedrosa Rodrigues <pedrosalpr@gmail.com>
   * @version 1.0 
   * @since 1.0 
   */
  public static function validaDataSQL($data) {
    $pattern = "/^\d{4}-(0[0-9]|1[0,1,2])-([0,1,2][0-9]|3[0,1])$/";
    if (preg_match($pattern, $data)) {
      return true;
    } else {
      return false;
    }
  }

  /**
   * Método para validar o ip
   * Note: Serve para qualquer classe que precisa validar o ip
   * @static
   * @param string $ip IP
   * @return boolean 
   * @author Leandro Pedrosa Rodrigues <pedrosalpr@gmail.com>
   * @version 1.0 
   * @since 1.0 
   */
  public static function validaRedeIP($ip) {
    $pattern = "/^((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){3}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})$/";
    if (preg_match($pattern, $ip)) {
      return true;
    } else {
      return false;
    }
  }

  /**
   * Método para validar o MAC
   * Note: Serve para qualquer classe que precisa validar o MAC
   * @static
   * @param String $mac MAC a ser validado
   * @return boolean 
   * @author Leandro Pedrosa Rodrigues <pedrosalpr@gmail.com>
   * @since 1.0 22/08/2011
   * @version 1.0 22/08/2011
   */
  public static function validaRedeMAC($mac) {
    $pattern = "/^([A-F0-9]{2}\:){5}[A-F0-9]{2}$/";
    if (preg_match($pattern, $mac)) {
      return true;
    } else {
      return false;
    }
  }

}

?>
