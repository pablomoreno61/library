<?php

namespace App\Tests\Library\Application\UseCase;

use App\Library\Application\Dto\BookDetailItem;
use App\Library\Application\UseCase\CreateBookUseCase;
use App\Library\Domain\Entity\Book;
use App\Library\Domain\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CreateBookUseCaseTest extends WebTestCase
{
    public function testCreateBook()
    {
        $bookRepository = $this->createMock(BookRepository::class);

        $book = new Book('Test title', 'test image', 'test author', 42.32);
        $bookRepository->expects($this->once())
            ->method('save')
            ->willReturn($book);

        $createBookUseCase = new CreateBookUseCase($bookRepository);

        $bookDetailItem = $createBookUseCase->run($book);

        $this->assertInstanceOf(BookDetailItem::class, $bookDetailItem);
    }
}
