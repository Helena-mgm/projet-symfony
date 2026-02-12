<?php

namespace App\DataFixtures;

use App\Entity\Users;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UsersFixtures extends Fixture
{
    public const ADMIN_REF = 'admin-user';

    public function __construct(
        private UserPasswordHasherInterface $userPasswordHasher
    ) {}

    public function load(ObjectManager $manager): void
    {
        $admin = new Users();
        $admin->setEmail('admin@gmail.com');
        $admin->setFirstName('Admin');
        $admin->setLastName('User');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword(
            $this->userPasswordHasher->hashPassword($admin, 'qwerty')
        );

        $manager->persist($admin);
        $this->addReference(self::ADMIN_REF, $admin);

        for ($i = 0; $i < 5; $i++) {
            $user = new Users();
            $user->setEmail("user$i@gmail.com");
            $user->setFirstName("User$i");
            $user->setLastName("Test$i");
            $user->setRoles(['ROLE_USER']);
            $user->setPassword(
                $this->userPasswordHasher->hashPassword($user, 'qwerty')
            );

            $manager->persist($user);
            $this->addReference("user-$i", $user);
        }

        $manager->flush();
    }
}
