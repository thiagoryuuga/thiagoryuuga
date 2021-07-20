<?php


class Upload{
	
    private $diretorio = './upload/'; // Pasta onde o arquivo vai ser salvo

    private $tamanho = 2097152; // Tamanho máximo do arquivo (em Bytes)  //1024 * 1024 * 2 - 2MB

    private $extensoes = array('jpg', 'png', 'gif'); // Array com as extensões permitidas

    private $renomeia = true;	// Renomeia o arquivo? (Se true, o arquivo será salvo como .jpg e um nome único)

    private $erros = array();

    private $files;

    private $nome;

    
    public function __construct(){}
    
    
    /**
     * Faz upload do arquivo
     * @param string $files
     * @param string $nome 
     * @param string $pasta null
     * @param int $tamanho 2
     * @param boolean $renomeia true
     * @return array
     */
    public function fazerUpload($files, $nome, $pasta = null, $tamanho = 2, $renomeia = true){
        
        $this->diretorio .= ($pasta != null) ? $pasta : '';
        $this->tamanho    = $tamanho * 1024 * 1024;
        $this->renomeia  = $renomeia;
        $this->files = $files;
        $this->nome = $nome;
        // Array com os tipos de erros de upload do PHP
        $this->erros['erros'][0] = 'Não houve erro';
        $this->erros['erros'][1] = 'O arquivo no upload é maior do que o limite do PHP';
        $this->erros['erros'][2] = 'O arquivo ultrapassa o limite de tamanho especifiado no HTML';
        $this->erros['erros'][3] = 'O upload do arquivo foi feito parcialmente';
        $this->erros['erros'][4] = 'Não foi feito o upload do arquivo';	
        // Verifica se houve algum erro com o upload. Se sim, exibe a mensagem do erro
        if ($files['error'] != 0) {
            return array(false,"Não foi possível fazer o upload, erro:<br />" . $this->erros['erros'][$files['error']]);
            //exit; // Para a execução do script
        }

        // Caso script chegue a esse ponto, não houve erro com o upload e o PHP pode continuar

        // Faz a verificação da extensão do arquivo
        @$extensao = strtolower(end(explode('.', $files['name'])));
        if (array_search($extensao, $this->extensoes) === false) {
            return array(false,"Por favor, envie arquivos com as seguintes extensões: jpg, png ou gif");
        }

        // Faz a verificação do tamanho do arquivo
        else if ($this->tamanho < $files['size']) {
            return array(false,"O arquivo enviado é muito grande, envie arquivos de até $this->tamanho Mb.");
        }

        // O arquivo passou em todas as verificações, hora de tentar movê-lo para a pasta
        else {
            // Primeiro verifica se deve trocar o nome do arquivo
            if ($this->renomeia == true) {
                // Cria um nome baseado no UNIX TIMESTAMP atual e com extensão .jpg
                $nome_final = $nome.'.'.$extensao;
            } else {
                // Mantém o nome original do arquivo
                $nome_final = $files['name'];
            }

            // Depois verifica se é possível mover o arquivo para a pasta escolhida
            if (move_uploaded_file($files['tmp_name'], $this->diretorio . $nome_final)) {
                return array(true,$nome_final);

                //echo '<br /><a href="' . $_UP['pasta'] . $nome_final . '">Clique aqui para acessar o arquivo</a>';
            } else {
                // Não foi possível fazer o upload, provavelmente a pasta está incorreta
                return array(false,"Não foi possível enviar o arquivo, tente novamente");
            }

        }
    }
}

?>