<?php

namespace App\DataFixtures;
use App\DataFixtures\UserFixtures;
use App\Entity\Category;
use App\Entity\Recipe;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use FakerRestaurant\Provider\fr_FR\Restaurant;
use Symfony\Component\String\Slugger\SluggerInterface;

class RecipeFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private readonly SluggerInterface $slugger
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $faker->addProvider(new Restaurant($faker));

        // Step 1: Categories
        $categories = ['Plat chaud', 'Dessert', 'Entrée', 'Goûter'];
        $categoryObjects = []; // Store Category objects directly

        foreach ($categories as $c) {
            $category = (new Category())
                ->setName($c)
                ->setSlug($this->slugger->slug($c)->lower())
                ->setUpdatedAt(new \DateTimeImmutable())
                ->setCreatedAt(new \DateTimeImmutable());

            $manager->persist($category);
            $categoryObjects[] = $category; // Save Category objects
        }

        // Step 2: Recipes
        for ($i = 1; $i <= 10; $i++) {
            $title = $faker->foodName();

            $randomCategory = $faker->randomElement($categoryObjects); // Pick a random Category object

            $recipe = (new Recipe())
                ->setTitle($title)
                ->setSlug($this->slugger->slug($title)->lower())
                ->setContenu($faker->paragraph(10, true))
                ->setDuration($faker->numberBetween(2, 60))
                ->setUpdatedAt(new \DateTimeImmutable())
                ->setCreatedAt(new \DateTimeImmutable())
                ->setCategory($randomCategory); // Assign directly

            $manager->persist($recipe);
        }

        $manager->flush();
    }
    public function getDependencies(): array
    {
        return [UserFixtures::class];
    }
}
