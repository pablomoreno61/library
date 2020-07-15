<?php

namespace App\Library\Infrastructure\Repository;

use App\Library\Domain\Entity\Book;
use App\Library\Domain\Repository\BookRepository as BookRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class BookRepository extends ServiceEntityRepository implements BookRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    public function save(Book $book)
    {
        $this->_em->persist($book);
        $this->_em->flush();

        return $book;
    }
}
