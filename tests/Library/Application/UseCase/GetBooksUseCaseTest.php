<?php

namespace App\Library\Application\UseCase;

use App\Library\Application\Dto\BookListItem;
use App\Library\Domain\Entity\Book;
use App\Library\Domain\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GetBooksUseCaseTest extends WebTestCase
{
    public function testGetBooks()
    {
        $books = array(
            new Book('Lord of the Onions', 'test image', 'test author 1', 42.32),
            new Book('The Widow of Oz', 'test image', 'test author 2', 41.32),
            new Book('12 rules for Knive', 'test image', 'test author 3', 40.32),
            new Book('Children of Mood and Sand', 'test image', 'test author 4', 46.32)
        );

        $bookRepository = $this->createMock(BookRepository::class);
        $bookRepository->expects($this->once())
            ->method('findBy')
            ->willReturn($books);

        $getBooksUseCase = new GetBooksUseCase($bookRepository);

        $bookListItem = $getBooksUseCase->run(0, 0);

        $this->assertCount(4, $bookListItem);
        $this->assertInstanceOf(BookListItem::class, $bookListItem[0]);
    }
}