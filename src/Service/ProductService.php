<?php

namespace App\Service;

use App\Dto\ProductDTO;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

readonly class ProductService
{
    public function __construct(
        private ValidatorInterface $validator,
        private ProductRepository $productRepository,
    )
    {
    }

    /*********************************** PUBLIC METHOD **********************/
    /**
     * @param array $data
     * @return mixed|Response|true
     */
    public function createProduct(array $data): mixed
    {
        // Дубли не сохраняем
        if ($this->productRepository->isExists($data['product_sku'])) {
            return true;
        }

        $dto = $this->getDto($data);
        $product = $this->fromDto($dto);
        $result = $this->validate($product);

        if ($result === true) {
            $this->productRepository->save($product);
        }

        return $result;
    }

    /**
     * @param array $data
     * @return array
     */
    public function getAssociativeData(array $data): array
    {
        return [
            'product_sku' => $data[0] ?? null,
            'name' => $data[1] ?? null,
            'price' => $data[2] ?? null,
            'detail_text' => $data[3] ?? null,
            'level1' => $data[4] ?? null,
            'level2' => $data[5] ?? null,
            'level3' => $data[6] ?? null,
        ];
    }

    /**
     * @param ProductDTO $dto
     * @return Product
     */
    public function fromDto(ProductDTO $dto): Product
    {
        $model = new Product();
        $model->setProductSku($dto->productSku);
        $model->setName($dto->name);
        $model->setDetailText($dto->detailText);
        $model->setPrice($dto->price);
        $model->setLevel1($dto->level1);
        $model->setLevel2($dto->level2);
        $model->setLevel3($dto->level3);

        return $model;
    }

    /*********************************** PRIVATE METHOD **********************/
    /**
     * @param Product $product
     * @return true|Response
     */
    private function validate(Product $product): true|Response
    {
        $errors = $this->validator->validate($product);

        if (count($errors) > 0) {
            return new Response((string) $errors, 400);
        }

        return true;
    }

    /**
     * @param array $data
     * @return ProductDTO
     */
    private function getDto(array $data): ProductDTO
    {
        return new ProductDTO(
            $data['product_sku'] ?? null,
            $data['name'] ?? null,
            $data['price'] ?? null,
            $data['detail_text'] ?? null,
            $data['level1'] ?? null,
            $data['level2'] ?? null,
            $data['level3'] ?? null
        );
    }
}
