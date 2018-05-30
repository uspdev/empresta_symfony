# Empresta

Sistema para empréstimo de materiais (armários, CDs, Fones etc) durante a
permanência dos usuários na Biblioteca. O material deve ter um código 
identificador inteiro que será lido com código de barra.

Ações:

 - Gerenciamento dos materiais que serão emprestados
 - Empréstimo para pessoa USP (dados da replicação, tabelas PESSOA e/ou CATR_CRACHA)
 - Empréstimo para pessoa visitante (externa à USP) - mediante cadastro prévio
 - Cadastro local de pessoas externas à USP
 - Listagem dos materiais emprestados e não devolvidos
 - Não permite emprestar mais que um material por pessoa
 - Ativar ou desativar material

# Deploy

Download:

    git clone https://github.com/uspdev/empresta.git
    cd empresta
    composer install

Aplicar esquema no banco de dados:

    php bin/console doctrine:migrations:migrate

Criar usuário *admin* com senha *admin*:

    php bin/console doctrine:fixtures:load
