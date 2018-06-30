<?php

namespace App\Library\Application\Dto;

class BookDetailItem
{
    private $id;

    private $title;

    private $image;

    private $author;

    private $price;

    public function __construct($id, $title, $image, $author, $price)
    {
        $this->id = $id;
        $this->title = $title;
        $this->image = $image;
        $this->author = $author;
        $this->price = $price;
    }

    public function __toString()
    {
        return implode(', ', array($this->title, $this->image, $this->author, $this->price));
    }
}
