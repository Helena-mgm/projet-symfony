<?php

namespace App\DataFixtures;

use App\Entity\Comments;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CommentsFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 12; $i++) {
            $comment = new Comments();
            $comment->setContent("Commentaire $i : commentaire");
            $comment->setStatus($i % 3 === 0 ? 'pending' : 'approved'); 

            $comment->setUsers($this->getReference('user-'.($i % 5)));

            $comment->setPost($this->getReference('post-'.($i % 6)));

            $manager->persist($comment);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UsersFixtures::class,
            PostFixtures::class,
        ];
    }
}
