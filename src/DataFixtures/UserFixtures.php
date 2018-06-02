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
        $user1 = new User();
        $encoded = $this->encoder->encodePassword($user1, 'admin');
        $user1->setPassword($encoded);
        $user1->setUsername('admin');
        $user1->setRoles(['ROLE_ADMIN','ROLE_USER']);
        $manager->persist($user1);
        $manager->flush();

        $user2 = new User();
        $encoded = $this->encoder->encodePassword($user2, 'balcao');
        $user2->setPassword($encoded);
        $user2->setUsername('balcao');
        $user2->setRoles(['ROLE_USER']);
        $manager->persist($user2);
        $manager->flush();
    }
}
