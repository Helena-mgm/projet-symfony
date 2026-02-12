<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 3; $i++) {
            $cat = new Category();
            $cat->setName("Categorie $i");

            if (method_exists($cat, 'setDescription')) {
                $cat->setDescription("Description de la catégorie $i");
            }

            $manager->persist($cat);
            $this->addReference("cat-$i", $cat);
        }

        $manager->flush();
    }
}
