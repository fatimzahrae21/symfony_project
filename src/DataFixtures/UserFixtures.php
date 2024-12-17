<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(
        private readonly UserPasswordHasherInterface $hasher
    )
    {
        
    }
    public function load(ObjectManager $manager): void
    {
        $user = (new User());
        $user->setRoles(['ROLE_ADMIN'])
        ->setEmail('admin@fa.fr')
        ->setUsername('admin')
        ->setVerified(true)
        ->setPassword($this->hasher->hashPassword($user , 'admin'))
        ->setApiToken('admin_token');
        $manager->persist($user);

        for ($i = 1; $i <= 10; $i ++) {
            $user->setRoles([])
            ->setEmail("user{$i}@fa.fr")
            ->setUsername("user{$i}")
            ->setVerified(true)
            ->setPassword($this->hasher->hashPassword($user, '0000'))
            ->setApiToken("user{$i}");
            $manager->persist($user);
        }





        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
