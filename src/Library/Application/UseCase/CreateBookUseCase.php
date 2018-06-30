<?php

namespace App\Library\Application\UseCase;

use App\Library\Application\Dto\BookDetailItem;
use App\Library\Domain\Entity\Book;
use App\Library\Domain\Repository\BookRepository;

class CreateBookUseCase
{
    private $bookRepository;

    public function __construct(BookRepository $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    public function run(Book $book)
    {
        $book = $this->bookRepository->save($book);

        return new BookDetailItem(
            $book->getId(),
            $book->getTitle(),
            $book->getImage(),
            $book->getAuthor(),
            $book->getPrice()
        );
    }
}