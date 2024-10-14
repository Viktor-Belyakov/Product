<?php

namespace App\Message;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class ProductMessageDispatcher extends AbstractController
{
    public function __construct(
        private readonly MessageBusInterface $messageBus
    )
    {
    }

    /**
     * @throws ExceptionInterface
     */
    public function sendMessage($message): void
    {
        $this->messageBus->dispatch($message);
    }
}
