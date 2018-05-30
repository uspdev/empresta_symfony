# Symfony 4 Traditional Login Form with user from Database

This project is an basic implementation of traditional login form 
with users from database on symfony, as described in the
https://symfony.com/doc/current/security/form_login_setup.html.

Included:

 - login and logout routes configured
 - crud for manage users in database
 - passwords encoded with bcrypt

Not Included:

 - Registration to anonymous
 - Area to user to change the own password (for while)

The main reason for this project is to be a start point to
another projects thet depends of local users to work.

# Deploy

Download:

    git clone git@github.com:thiagogomesverissimo/symfony_traditional_login_form_users_from_db.git
    cd symfony_traditional_login_form_users_from_db
    composer install

Run migrations:

    php bin/console doctrine:migrations:migrate

Create a user *admin* with password *admin* to manage the others:

    php bin/console doctrine:fixtures:load

If you prefer to create the user with console:

    bin/console psysh
    $em = $container->get('doctrine')->getManager()
    $admin = new App\Entity\User
    $admin->setUsername('admin')
    $password = $container->get('security.password_encoder')->encodePassword($jose, 'admin')
    $admin->setPassword($password)
    $em->persist($admin)
    $em->flush()

Up server:

    php bin/console server:run

