<?php

namespace App\DataFixtures;

use App\Entity\Wishlist;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class WishlistFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        for ($i = 0; $i < 20; $i++) {
            $wishlist = new Wishlist();
            $wishlist->setName($faker->name);
            $wishlist->setUser($this->getReference('member_' . rand(0, 19)));
            $manager->persist($wishlist);
            $this->addReference('wishlist_' . $i, $wishlist);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [UserFixtures::class];
    }
}
