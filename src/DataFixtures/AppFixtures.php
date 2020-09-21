<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
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

        for ($i = 0; $i < 100; ++$i) { // On génére 100 produits de manière aléatoire
            $product = new Product();
            $product->setName($faker->text(5));
            $product->setDescription($faker->text(50));
            $product->setPrice($faker->numberBetween(10, 1000) * 100);
            $product->setCategory($this->getReference('category-'.rand(0, 3)));
            $manager->persist($product);
        }

        $manager->flush();
    }
}
