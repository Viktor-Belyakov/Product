<?php

namespace App\Controller;

use App\Message\ProductMessage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class ProductMessageController extends AbstractController
{
    /**
     * @param Request $request
     * @param MessageBusInterface $messageBus
     * @return void
     * @throws ExceptionInterface
     */
    public function sendParsedProducts(Request $request, MessageBusInterface $messageBus): void
    {
        $parsedProducts = $request->request->all('parsedProducts');

        foreach ($parsedProducts as $product) {
            $product = explode('|', $product);
            $message = new ProductMessage($product);
            $messageBus->dispatch($message);
        }
    }
}
