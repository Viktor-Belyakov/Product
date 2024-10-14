<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry
    )
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * @param Product $product
     * @return void
     */
    public function save(Product $product): void
    {
        $this->getEntityManager()->persist($product);
        $this->getEntityManager()->flush();
    }

    /**
     * @param int $sku
     * @return array
     */
    public function getProductBySkuAsArray(int $sku): array
    {
        return $this->createQueryBuilder('p')
            ->where('p.productSku = :sku')
            ->setParameter('sku', $sku)
            ->getQuery()
            ->getArrayResult();
    }

    /**
     * @param int $sku
     * @return bool
     */
    public function isExists(int $sku): bool
    {
        $product = $this->findBy(['productSku' => $sku]);
        return !empty($product);
    }
}
