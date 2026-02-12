<?php

namespace App\DataFixtures;

use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PostFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $admin = $this->getReference(UsersFixtures::ADMIN_USER_REFERENCE);

        for ($i = 0; $i < 6; $i++) {
            $post = new Post();

            $post->setTitle("Post numéro $i");
            $post->setContent("Contenu du post $i.\n\nTexte de démonstration.");
            if (method_exists($post, 'setPicture')) {
                $post->setPicture(null);
            }
            if (method_exists($post, 'setPublishedAt')) {
                $post->setPublishedAt(new \DateTimeImmutable());
            }

            $post->setCategory($this->getReference('cat-'.($i % 3)));

            if (method_exists($post, 'setUsers')) {
                $post->setUsers($admin);
            } elseif (method_exists($post, 'setAuthor')) {
                $post->setAuthor($admin);
            }

            $manager->persist($post);
            $this->addReference("post-$i", $post);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UsersFixtures::class,
            CategoryFixtures::class,
        ];
    }
}
