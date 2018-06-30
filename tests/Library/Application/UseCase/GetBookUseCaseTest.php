<?php

namespace App\Library\Application\UseCase;

use App\Library\Application\Dto\BookDetailItem;
use App\Library\Application\Exception\InvalidUuidFormatException;
use App\Library\Domain\Entity\Book;
use App\Library\Domain\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GetBookUseCaseTest extends WebTestCase
{
    public function testGetExistingBook()
    {
        $book = new Book('Test title', 'test image', 'test author', 42.32);

        $bookRepository = $this->createMock(BookRepository::class);
        $bookRepository->expects($this->once())
            ->method('findOneBy')
            ->willReturn($book);

        $getBookUseCase = new GetBookUseCase($bookRepository);

        $bookDetailItem = $getBookUseCase->run('07ed651f-500c-45d1-99a4-65fbaf302494');

        $this->assertEquals('Test title, test image, test author, 42.32', $bookDetailItem);
        $this->assertInstanceOf(BookDetailItem::class, $bookDetailItem);
    }

    /**
     * @expectedException \App\Library\Application\Exception\InvalidUuidFormatException
     * @throws \App\Library\Application\Exception\InvalidUuidFormatException
     */
    public function testCannotGetInvalidUuidBook()
    {
        $bookRepository = $this->createMock(BookRepository::class);

        $getBookUseCase = new GetBookUseCase($bookRepository);

        $getBookUseCase->run(123);
    }

    /**
     * @expectedException \App\Library\Application\Exception\BookWasFoundException
     * @throws \App\Library\Application\Exception\BookWasFoundException
     */
    public function testCannotGetUnexistingBook()
    {
        $bookRepository = $this->createMock(BookRepository::class);
        $bookRepository->expects($this->once())
            ->method('findOneBy')
            ->willReturn(null);

        $getBookUseCase = new GetBookUseCase($bookRepository);

        $getBookUseCase->run('07ed651f-500c-45d1-89a4-65fbaf302494');
    }
}