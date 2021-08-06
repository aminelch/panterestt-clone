<?php

namespace App\DataFixtures;

use App\Entity\Pin;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        
        $pin = new Pin;

        $user = new User; 

        $user->setEmail('contact@aminelch.xyz')
        ->setPassword("$2y$13$.iUPjLCn7L9Yl6/yn516l.M3ZCHpM5n351NiN3T76zRMEebC2qXAS")
        ->setFirstName('Amine')
        ->setLastName("lch")
        ->setIsVerified(true)
        ->setRoles(['ROLE_ADMIN'])
        ;

        $pin->setTitle('PIN de test')
        ->setDescription(' mollitia sapiente. Fugit eos aut, vitae quas, voluptatum omnis repudiandae, nostrum sint deleniti obcaecati dolores fuga ut accusantium deserunt voluptatem.')
        ->setUser($user);

        $manager->persist($user);
        $manager->persist($pin);
        $manager->flush();
    }
}
