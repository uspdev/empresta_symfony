# Empresta

Sistema para empréstimo de materiais (armários, CDs, Fones etc) durante a
permanência dos usuários na Biblioteca. O material deve ter um código 
identificador único que será lido com código de barra.

## Features

 Ações do grupo balcão:

 - Gerenciar visitantes
 - Empréstimo para visitante (externa à USP) - mediante cadastro prévio
 - Empréstimo para pessoa USP
 - Listagem dos itens emprestados e ainda não devolvidos
 - Devolução
 - Impressão dos códigos de barras
 - Não permite empréstimo de dois materiais do mesmo tipo para mesma pessoa

Ações do grupo administrador:

 - Gerenciar usuários do sistema
 - Gerenciamento dos tipos de materiais que serão emprestados: armários, fones etc
 - Gerenciamento dos materiais: criar, editar, apagar ou desativar.
 - Histórico dos empréstimos por material
 
## Integração com outros serviços:

### Replicado

 - Inserir *true* na variável USAR_REPLICADO no arquivo .env
 - Configurar as variáveis correspondentes
 - baixar a estrutura das tabelas:

    git clone git@git.uspdigital.usp.br:uspdev/replicado_queries vendor/uspdev/replicado/src/replicado_queries

### WSfoto

 - Inserir *true* na variável USAR_WSFOTO em .env 
 - Configurar as variáveis correspondentes

# Deploy

Download:

    git clone https://github.com/uspdev/empresta.git
    cd empresta
    composer install

Configurar .env e aplicar esquema no banco de dados:

    php bin/console doctrine:migrations:migrate

Criar usuário *admin* e *balcao* e materiais de exemplo:

    php bin/console doctrine:fixtures:load
