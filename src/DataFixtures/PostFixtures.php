<?php
 
namespace App\DataFixtures;
 
use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\Category;
 
class PostFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
            for ($i=1; $i<=6; $i++) {
            $post = new Post();
            $post->setTitle("Post $i");
            $post->setContent('Mon premier post de contenu');
            $post->setPicture("https://picsum.photos/id/10$i/400/300");
 
                $category = $this->getReference("category_" . (($i % 4) + 1), Category::class);

                $post->setCategory($category);

                $manager->persist($post);
                $this->addReference('post-'.$i, $post);
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