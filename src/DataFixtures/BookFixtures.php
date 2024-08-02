<?php

namespace App\DataFixtures;

use App\Entity\Book;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BookFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 1000; $i++) {
            $book = new Book();
            $book->setTitle('Book Title ' . $i);
            $book->setAuthor('Author ' . $i);
            $book->setDescription('Description of Book ' . $i);
            $book->setPublicationYear(2000 + ($i % 20));
            $book->setIsbn('ISBN' . $i);

            $manager->persist($book);
        }

        $manager->flush();
    }
}
