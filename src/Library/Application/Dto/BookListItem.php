<?php

namespace App\Library\Application\Dto;

class BookListItem
{
    const ITEM_URL = '/api/v1/items/';

    private $id;

    private $title;

    private $link;

    public function __construct($id, $title)
    {
        $this->id = $id;
        $this->title = $title;
        $this->link = self::ITEM_URL . $this->id;
    }
}