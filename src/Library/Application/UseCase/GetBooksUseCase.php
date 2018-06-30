<?php

namespace App\Library\Application\UseCase;

use App\Library\Application\Dto\BookListItem;
use App\Library\Domain\Repository\BookRepository;

class GetBooksUseCase
{
    private $bookRepository;

    public function __construct(BookRepository $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    public function run($limit, $offset)
    {
        $books = $this->bookRepository->findBy(
            array(),
            array('title' => 'DESC'),
            $limit,
            $offset
        );

        $bookListItems = [];
        foreach ($books as $book) {
            $bookListItems[] = new BookListItem(
                $book->getId(),
                $book->getTitle()
            );
        }

        return $bookListItems;
    }
}