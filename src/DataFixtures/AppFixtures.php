<?php
 
namespace App\DataFixtures;
 
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
 
class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $userPasswordHasher) {
        throw new \Exception('Not implemented');
    }
    public function load(ObjectManager $manager): void
    {
        $admin = new User();
        $admin->setEmail('admini@gmail.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->userPasswordHasher->hashPassword($admin, 'qwerty'));
        $manager->persist($admin);
 
        for ($i=0; $i<5;$i++) {
            $user = new User();
            $user->setEmail("user$i@gmail.com");
            $user->setRoles(['ROLE_USER']);
            $user->setPassword($this->userPasswordHasher->hashPassword($user, 'qwerty'));
            $manager->persist($user);
        }
 
        $manager->flush();
    }
}