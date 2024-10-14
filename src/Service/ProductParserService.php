<?php

namespace App\Service;

readonly class ProductParserService
{
    /**
     * @param string $filePath
     * @return array
     */
    public static function parseProducts(string $filePath): array
    {
        $products = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $parsedProducts = [];
        $tempProduct = '';
        array_shift($products);

        foreach ($products as $line) {
            if (preg_match('/^\d+\s*\|/', $line)) {
                if (!empty($tempProduct)) {
                    $parsedProducts[] = $tempProduct;
                }
                $tempProduct = $line;
            } else {
                $tempProduct .= ' ' . $line;
            }
        }

        if (!empty($tempProduct)) {
            $parsedProducts[] = $tempProduct;
        }

        return $parsedProducts;
    }
}
