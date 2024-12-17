<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Recipe;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use FakerRestaurant\Provider\fr_FR\Restaurant;
use Symfony\Component\String\Slugger\SluggerInterface;

class RecipeFixtures extends Fixture
{
    public function __construct(private readonly SluggerInterface $slugger)
    {
        
    }
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $faker->addProvider(new Restaurant($faker));

        $categories = ['Plat chaud' , 'Dessert' , 'Entrée' , 'Goùter' ];

        foreach ($categories as $c) {
            $category = (new Category())
            ->setName($c)
            ->setSlug($this->slugger->slug($c))
            ->setUpdatedAt(\DateTimeImmutable::createFromMutable($faker->dateTime()))
            ->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTime()));
            $manager->persist($category);
            $this->addReference($c ,$category);

        }

        for ($i = 1; $i <= 10; $i++) {
            $title = $faker->foodName();
            $recipe = (new Recipe())
            ->setTitle($title)
            ->setSlug($this->slugger->slug($title))
            ->setUpdatedAt(\DateTimeImmutable::createFromMutable($faker->dateTime()))
            ->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTime()))
            ->setContenu($faker->paragraph(10 , true))
            ->setCategory($this->getReference($faker->randomElement($categories)))
            ->setDuration($faker->numberBetween(2 , 60));
        $manager->persist($recipe);
        }
        $manager->flush();
    }
}
