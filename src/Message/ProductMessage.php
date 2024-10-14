<?php

namespace App\Message;

readonly class ProductMessage
{
    public function __construct(
        private array $message,
    )
    {
    }

    /**
     * @return array
     */
    public function getMessage()
    {
        return $this->message;
    }
}
