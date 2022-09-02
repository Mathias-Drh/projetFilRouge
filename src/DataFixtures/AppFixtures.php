<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');
        $admin = new User();
        $admin->setFirstName($faker->firstName('female'));
        $admin->setLastName($faker->lastName());
        $admin->setUserName('ADMIN001');
        $admin->setEmail('admin@gg.com');
        $password1 = $this->hasher->hashPassword($admin, 'pass_1234');
        $admin->setPassword($password1);
        $admin->setRoles(['ROLE_ADMIN']);

        for ($i = 0 ; $i < 10 ; $i++) {
            $user = new User();
            $user->setFirstName($faker->firstName());
            $user->setLastName($faker->lastName());
            $user->setUserName('user-00'.$i);
            $user->setEmail(strtolower($user->getFirstName().'.'.$user->getLastName()).'@gg.com');
            $password = $this->hasher->hashPassword($user, 'user_1234');
            $user->setPassword($password);
            $user->setRoles(['ROLE_USER']);
            $manager->persist($user);
        }

        $superAdmin = new User();
        $superAdmin->setFirstName('GOD001');
        $superAdmin->setLastName('Dog001');
        $superAdmin->setEmail('superadmin42069@gg.com');
        $superAdmin->setUserName('superadmin42069');
        $password = $this->hasher->hashPassword($superAdmin, 'uwu_trashgirl');
        $superAdmin->setPassword($password);
        $superAdmin->setRoles(['ROLE_SUPER_ADMIN']);

        $manager->persist($admin);
        $manager->persist($superAdmin);
        $manager->flush();
    }
}
