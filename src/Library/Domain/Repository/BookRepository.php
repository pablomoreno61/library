<?php

namespace App\Library\Domain\Repository;

use App\Library\Domain\Entity\Book;

interface BookRepository
{
    public function find($id, $lockMode = null, $lockVersion = null);

    public function findOneBy(array $criteria, array $orderBy = null);

    public function findAll();

    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null);

    public function save(Book $book);
}