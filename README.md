# Empresta (deprecado em favor do laravel)

Sistema para empréstimo de materiais (armários, CDs, Fones etc) durante a
permanência dos usuários na Biblioteca. O material deve ter um código 
identificador único que será lido com código de barra.

Vídeo de apresentação: 
https://youtu.be/ruuHcMZPNxY
## Features

 Ações do grupo balcão:

 - Gerenciar visitantes
 - Empréstimo para visitante (externa à USP) - mediante cadastro prévio
 - Empréstimo para pessoa USP
 - Listagem dos itens emprestados e ainda não devolvidos
 - Devolução
 - Impressão dos códigos de barras
 - Não permite empréstimo de dois materiais do mesmo tipo para mesma pessoa
 - Relatório

Ações do grupo administrador:

 - Gerenciar usuários do sistema
 - Gerenciamento dos tipos de materiais que serão emprestados: armários, fones etc
 - Gerenciamento dos materiais: criar, editar, apagar ou desativar.
 - Histórico dos empréstimos por material

## Integração com outros serviços:

### Replicado

 - Inserir *true* na variável USAR_REPLICADO no arquivo .env
 - Configurar as variáveis correspondentes
    - A tabela CRACHA é utilizada para pessoas externas a unidade
 - baixar a estrutura das tabelas do replicado:
    
    git clone git@git.uspdigital.usp.br:uspdev/replicado_queries vendor/uspdev/replicado/src/replicado_queries

### WSfoto

 - Inserir *true* na variável USAR_WSFOTO em .env 
 - Configurar as variáveis correspondentes

# Deploy

O arquivo .env é gerado automaticamente depois do composer install, mas
caso isso não ocorra, pode-se gerá-lo a partir do template:

    cp .env.dist .env 

Download:

    git clone https://github.com/uspdev/empresta.git
    cd empresta
    composer install

Configurar variáveis no .env, sendo que 
a string de conexão DATABASE_URL é obrigatória:

    DATABASE_URL=mysql://USUARIO:SENHA@IP:3306/BANCO
    APP_NAME='Sistema de Empréstimo de Armários - Biblioteca'
    APP_LOGO_URL='http://www.fflch.usp.br/logo.png'

Configurar em public/js/dataTables.js pageLength a quantidade de registros 
padrão por página. Caso queira por padrão mostrar todos, deixe o valor em -1

    pageLength  	: 25

Aplicar esquema no banco de dados

    php bin/console doctrine:migrations:migrate

Criar usuário *admin* e *balcao* e cadastrar materiais de exemplo:

    php bin/console doctrine:fixtures:load

Executando o servidor PHP
    
    php bin/console server:run


