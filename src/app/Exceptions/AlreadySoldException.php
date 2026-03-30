<?php

namespace App\Exceptions;

class AlreadySoldException extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct('この商品はすでに購入済みです。');
    }
}