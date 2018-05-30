# symfony_base4localuser

    composer create-project symfony/website-skeleton symfony_base4localuser

# Pacotes de desenvolvimento

    composer require --dev symfony/web-server-bundle theofidry/psysh-bundle doctrine/doctrine-fixtures-bundle

campos username e password

php bin/console make:entity User

migrations:

php bin/console doctrine:migrations:diff
php bin/console doctrine:migrations:migrate



bin/console psysh

ls
$em = $container->get('doctrine')->getManager()
$jose = new App\Entity\User
$jose->setUsername('jose')
$jose->setPassword('j123')
$em->persist($jose)
$em->flush()

$users = $em->getRepository('App\Entity\User')->findAll();
$users[0]->getUsername()

$user1 = $em->getRepository('App\Entity\User')->find(1)
$users1->getUsername()


php bin/console make:crud User




