![](images/logo-esm.png)

### Sistema de Atendimento do RH

Sistema de controle de atendimento a funcionários da Esmaltec englobando as de Departamento Pessoal, Assistência Social, Medicina do Trabalho.

### Url's do sistema
Produção :   http://intranet.esmaltec.com.br/sistemas/atendimento

Desenvolvimento: http://intranet.esmaltec.com.br:8090/sistemas/atendimento

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
6. jsPdf

## Niveis de acesso ao sistema
 0 - Administrador do Sistema (Utilizado apenas pela T.I)
 
 1 - Atendimento de guichê (Este nível trata de atendimentos primários, tira dúvidas e recebimento de documentos e solicitações)

 2 - Acompanhamento de Acidentes e visitas (Utilizando apenas pela Asistente social da empresa, antes de liberar este nível de acesso a algum colaborador, a liberação deve ser autorizada pelo Departamento pessoal)
 
 3 - Importação de faltas (Este nível pode dar carga do relatório de faltas dos colaboradores)

 4 - Notificação de gestores (Este nível executa as rotinas de notificaçao de cada gestor)

 5- Atendimento de medicina do trabalho (Este nível registra atendimentos e encaminhamentos para atendimento ambulatórial ou antendimento pela empresa de plano de saúde da esmaltec. Em vista desta característica, compartilha da mesma tela de acesso da assitente social, tendo restrição de alguns itens específicos.)

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