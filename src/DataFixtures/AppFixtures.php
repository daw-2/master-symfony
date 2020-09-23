<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\Tag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies()
    {
        return [UserFixtures::class];
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        $categories = ['Smartphone', 'PC', 'TV', 'Audio'];
        foreach ($categories as $index => $name) {
            $category = new Category();
            $category->setName($name);
            $manager->persist($category);
            $this->addReference('category-'.$index, $category);
        }

        $tags = ['5G', '64go', 'Noir', 'DontKnow', 'Reconditionné'];
        foreach ($tags as $index => $name) {
            $tag = new Tag();
            $tag->setName($name);
            $manager->persist($tag);
            $this->addReference('tag-'.$index, $tag);
        }

        for ($i = 0; $i < 100; ++$i) { // On génére 100 produits de manière aléatoire
            $product = new Product();
            $product->setName($faker->text(5));
            $product->setDescription($faker->text(50));
            $product->setPrice($faker->numberBetween(10, 1000) * 100);
            $product->setCategory($this->getReference('category-'.rand(0, 3)));
            $product->setUser($this->getReference('user-0'));

            $tagCount = rand(0, 5);
            for ($j = 0; $j < $tagCount; ++$j) {
                $product->addTag($this->getReference('tag-'.$j));
            }

            $manager->persist($product);
        }

        $manager->flush();
    }
}
