![](images/logo-esm.png)

### Sistema de logistica integrada

Sistema de controle de entrada, saida e carregamento de veículos em galpões próprios ou terceiros.

### Url's do sistema
Produção :   http://intranet.esmaltec.com.br/sistemas/logistica

Homologação: http://intranet.esmaltec.com.br:8091/sistemas/logistica

Desenvolvimento: http://intranet.esmaltec.com.br:8090/sistemas/logistica

### Requisitos de funcionamento:
1. PHP 5.3.8
2. Banco de dados Oracle 9i
3. 512mb de ram
4. webService esmaltec

### Bibliotecas adicionais instaladas:
1. Ldap
2. SimpleXML
3. OCI8

### Framework utilizado:
CodeIgniter 2.6

### plugins adicionais
1. JQuery 1.8
2. JQuery UI
3. JQuery Datatables
4. Thickbox
5. Masked-Input 1.3

## Procedimento para migração de servidor

Localizar o arquivo __index.php__ na raiz da pasta do sistema, e alterar a linha com o seguinte codigo:
_define('ENVIRONMENT', '...');*_

utilizando uma das 3 opções deploy

1. development
2. testing
3. production

em seguida, localizar o arquivo __database.php__ localizado em _application/config_ e definir os parâmetros de conexão para os bancos utilizados
Observação

O arquivo database.php possui para cada conexão um indice de array semelhante a esta:
_$db['default']['db_debug'] = TRUE;_

Em ambientes de homologação e produção é recomendado que o valor esteja definido como __FALSE__ para evitar que a aplicação mostre algum eventual erro de banco de dados para o usuário.
*cada opção altera diretamente o output de erros do PHP, as variávies de homologação (testing) e produção (production) não apresentam erros, servindo apenas como indicativo de qual o estágio de desenvolvimento o projeto se encontra, enquanto que development exibe todos os erros em tela para facilitar o debug da aplicação.