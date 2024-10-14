<?php

namespace App\Service;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use RuntimeException;

readonly class DataBaseService
{
    public function __construct(
        private EntityManagerInterface $entityManager
    )
    {
    }

    /**
     * @return Connection
     */
    public function connection(): Connection
    {
        try {
            $connection = $this->entityManager->getConnection();

            if (!$connection->isConnected()) {
                $connection->connect();
            }

            return $connection;
        } catch (Exception $e) {
            throw new RuntimeException('Не удалось подключиться к базе данных. Попробуйте позже.', 0, $e);
        }
    }

    /**
     * @return bool
     */
    public function isConnected(): bool
    {
        return $this->connection()->isConnected();
    }
}
