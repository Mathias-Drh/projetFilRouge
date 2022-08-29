<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $admin = new User();
        $admin->setFirstName('ADMIN000');
        $admin->setLastName('Admin000');
        $admin->setEmail('admin@gg.com');
        $password1 = $this->hasher->hashPassword($admin, 'pass_1234');
        $admin->setPassword($password1);
        $admin->setRoles(['ROLE_ADMIN']);

        $user = new User();
        $user->setFirstName('USER001');
        $user->setLastName('User001');
        $user->setEmail('testuser@gg.com');
        $password = $this->hasher->hashPassword($user, 'user_1234');
        $user->setPassword($password);
        $user->setRoles(['ROLE_USER']);

        $superAdmin = new User();
        $superAdmin->setFirstName('GOD001');
        $superAdmin->setLastName('Dog001');
        $superAdmin->setEmail('superadmin42069@gg.com');
        $password = $this->hasher->hashPassword($superAdmin, 'uwu_trashgirl');
        $superAdmin->setPassword($password);
        $superAdmin->setRoles(['ROLE_SUPER_ADMIN']);

        $manager->persist($admin);
        $manager->persist($user);
        $manager->persist($superAdmin);
        $manager->flush();
    }
}
