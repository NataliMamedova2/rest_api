<?php

declare(strict_types=1);

namespace App\CommandBus\CommandHandler;

use App\Repository\AuthorRepository;
use App\CommandBus\Command\BookUpdateCommand;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class BookUpdateHandler
{
    /** @var AuthorRepository */
    protected $bookRepository;

    protected $em;

    /**
     * BookCreateHandler constructor.
     * @param BookRepository $bookRepository
     * @param EntityManagerInterface $em
     */
    public function __construct(BookRepository $bookRepository, EntityManagerInterface $em)
    {
        $this->bookRepository = $bookRepository;
        $this->em = $em;
    }

    /**
     * @param BookUpdateCommand $command
     */
    public function handle(BookUpdateCommand $command): void
    {
        $book = $this->bookRepository->find($command->id);

        if ($book === null) {
            throw new BadRequestHttpException('There is no such book');
        }

        $book->setTitle($command->title);

        $this->em->persist($book);
        $this->em->flush();
    }
}