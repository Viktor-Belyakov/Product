<?php

namespace App\Serializer;

use App\Message\ProductMessage;
use Elastic\Elasticsearch\Exception\ClientResponseException;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;

class ProductMessageSerializer implements SerializerInterface
{
    /**
     * @param array $encodedEnvelope
     * @return Envelope
     */
    public function decode(array $encodedEnvelope): Envelope
    {
        $record = json_decode($encodedEnvelope['body']);
        return new Envelope(
            new ProductMessage($record)
        );
    }

    /**
     * @param Envelope $envelope
     * @return array
     * @throws ClientResponseException
     */
    public function encode(Envelope $envelope): array
    {
        /** @var ProductMessage $event */
        $event = $envelope->getMessage();
        if (empty($event->getMessage())) {
           throw new ClientResponseException('Отсутствуют параметры для импорта!');
        }

        return [
            'body' => json_encode(
                $event->getMessage()
            ),
        ];
    }
}
