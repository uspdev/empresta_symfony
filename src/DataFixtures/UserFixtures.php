<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;

class UserFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        // admin user
        $admin = new User();
        $encoded = $this->encoder->encodePassword($admin, 'admin');
        $admin->setPassword($encoded);
        $admin->setUsername('admin');
        $admin->setRoles(['ROLE_ADMIN','ROLE_USER']);
        $manager->persist($admin);
        $manager->flush();

        // balcao user
        $balcao = new User();
        $encoded = $this->encoder->encodePassword($balcao, 'balcao');
        $balcao->setPassword($encoded);
        $balcao->setUsername('balcao');
        $balcao->setRoles(['ROLE_USER']);
        $manager->persist($balcao);
        $manager->flush();
    }
}
