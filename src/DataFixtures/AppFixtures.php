<?php

namespace App\DataFixtures;

use App\Factory\CategoryFactory;
use App\Factory\CommentFactory;
use App\Factory\PublicationFactory;
use App\Factory\RatingFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        UserFactory::createMany(50);

        CategoryFactory::createOne(['tag' => 'Hardstyle']);
        CategoryFactory::createOne(['tag' => 'Hard Trance']);
        CategoryFactory::createOne(['tag' => 'Techno']);

        PublicationFactory::createMany(20);

        RatingFactory::createMany(100);

        CommentFactory::createMany(60);

        $manager->flush();
    }
}
