<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail('fiorella@boxydev.com');
        $user->setPassword($this->encoder->encodePassword($user, 'daddy'));

        $manager->persist($user);

        $user = new User();
        $user->setEmail('matthieu@boxydev.com');
        $user->setRoles(['ROLE_ADMIN']);
        $user->setPassword($this->encoder->encodePassword($user, 'test'));
        $this->addReference('user-0', $user);

        $manager->persist($user);

        $manager->flush();
    }
}
