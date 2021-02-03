<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $admin = new User();
        $admin->setEmail('nicolasflichy@gmail.com');
        $admin->setName('Nicolas');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->passwordEncoder->encodePassword($admin, 'admin'));
        $manager->persist($admin);

        for ($i = 0; $i < 20; $i++) {
            $member = new User();
            $member->setEmail('member' . $i . '@email.com');
            $member->setName('member_' . $i);
            $member->setRoles(['ROLE_USER']);
            $member->setPassword($this->passwordEncoder->encodePassword($member, 'member'));
            $this->addReference('member_' . $i, $member);
            $manager->persist($member);

        }
        $manager->flush();
    }
}
