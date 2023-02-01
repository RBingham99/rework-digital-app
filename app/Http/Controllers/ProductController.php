<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductController extends Controller
{
    public static function formatNewProductCategories(array $newProduct): array
    {
        return Product::formatNewProductCategories($newProduct);

    }

    public static function addNewProduct(Product $newProduct): void
    {
        Product::addNewProduct($newProduct);
    }
}
