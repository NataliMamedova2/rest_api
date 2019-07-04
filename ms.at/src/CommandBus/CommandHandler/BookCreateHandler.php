<?php
declare(strict_types=1);

namespace App\CommandBus\CommandHandler;

use App\Repository\AuthorRepository;
use App\Entity\Book;
use App\CommandBus\Command\BookCreateCommand;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class BookCreateHandler
{
    /** @var AuthorRepository */
    protected $authorRepository;

    protected $em;

    /**
     * BookCreateHandler constructor.
     * @param AuthorRepository $authorRepository
     * @param EntityManagerInterface $em
     */
    public function __construct(AuthorRepository $authorRepository, EntityManagerInterface $em)
    {
        $this->authorRepository = $authorRepository;
        $this->em = $em;
    }

    /**
     * @param BookCreateCommand $command
     */
    public function handle(BookCreateCommand $command): void
    {
        $author = $this->authorRepository->find($command->authorId);

        if ($author === null) {
            throw new BadRequestHttpException('There is no such author');
        }

        $book = new Book();
        $book->setTitle($command->title);
        $book->addAuthor($author);

        $this->em->persist($book);
        $this->em->flush();
    }
}