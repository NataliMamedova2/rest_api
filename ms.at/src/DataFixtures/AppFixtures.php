<?php

namespace App\DataFixtures;

use App\Entity\Book;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Author;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $authors = $this->getAuthors();

        $book = new Book();
        $book->setTitle('Discovering Modern C++');

        foreach ($authors as [$name]) {
            $author = new Author();
            $author->setName($name);

            $book->addAuthor($author);

            $manager->persist($author);
            $manager->persist($book);
        }

        $manager->flush();
    }

    private function getAuthors(): array
    {
        return [
            ['Nil Stevenson'],
            ['Alex Allain'],
            ['Peter Cottsching']
        ];
    }
}
