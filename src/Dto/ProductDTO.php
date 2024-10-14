<?php

namespace App\Dto;

/**
 * @property int $productSku
 * @property string $name
 * @property float $price
 * @property string $detailText
 * @property string $level1
 * @property string $level2
 * @property string $level3
 */
class ProductDTO
{
    public ?int $productSku;
    public ?string $name;
    public ?float $price;
    public ?string $detailText;
    public ?string $level1;
    public ?string $level2;
    public ?string $level3;

    public function __construct(
        ?int $productSku,
        ?string $name,
        ?float $price,
        ?string $detailText,
        ?string $level1,
        ?string $level2,
        ?string $level3
    ) {
        $this->productSku = $productSku;
        $this->name = $name;
        $this->price = $price;
        $this->detailText = $detailText;
        $this->level1 = $level1;
        $this->level2 = $level2;
        $this->level3 = $level3;
    }
}
