<?php

namespace App\Library\Application\UseCase;

use App\Library\Application\Dto\BookDetailItem;
use App\Library\Application\Exception\BookWasFoundException;
use App\Library\Application\Exception\InvalidUuidFormatException;
use App\Library\Domain\Repository\BookRepository;
use Ramsey\Uuid\Uuid;

class GetBookUseCase
{
    private $bookRepository;

    public function __construct(BookRepository $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    public function run($uuid)
    {
        if (!Uuid::isValid($uuid)) {
            throw new InvalidUuidFormatException($uuid);
        }

        $book = $this->bookRepository->findOneBy(array('id' => $uuid));

        if (!$book) {
            throw new BookWasFoundException($uuid);
        }

        return new BookDetailItem(
            $book->getId(),
            $book->getTitle(),
            $book->getImage(),
            $book->getAuthor(),
            $book->getPrice()
        );
    }
}