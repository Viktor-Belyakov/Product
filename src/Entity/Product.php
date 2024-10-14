<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::INTEGER)]
    private ?int $productSku = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $name = null;

    #[ORM\Column(type: Types::FLOAT)]
    private ?float $price = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $detailText = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $level1 = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $level2 = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $level3 = null;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return int|null
     */
    public function getProductSku(): ?int
    {
        return $this->productSku;
    }

    /**
     * @param int $productSku
     * @return $this
     */
    public function setProductSku(int $productSku): static
    {
        $this->productSku = $productSku;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getPrice(): ?float
    {
        return $this->price;
    }

    /**
     * @param float $price
     * @return $this
     */
    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDetailText(): ?string
    {
        return $this->detailText;
    }

    /**
     * @param string $detailText
     * @return $this
     */
    public function setDetailText(string $detailText): static
    {
        $this->detailText = $detailText;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLevel1(): ?string
    {
        return $this->level1;
    }

    /**
     * @param string $level1
     * @return $this
     */
    public function setLevel1(string $level1): static
    {
        $this->level1 = $level1;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLevel2(): ?string
    {
        return $this->level2;
    }

    /**
     * @param string|null $level2
     * @return $this
     */
    public function setLevel2(?string $level2): static
    {
        $this->level2 = $level2;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLevel3(): ?string
    {
        return $this->level3;
    }

    /**
     * @param string|null $level3
     * @return $this
     */
    public function setLevel3(?string $level3): static
    {
        $this->level3 = $level3;

        return $this;
    }
}
