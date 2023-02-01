<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Illuminate\Support\Collection;

class Product extends Model
{
    use HasFactory;

    /**
     * @var string|mixed
     */
    private string $name;
    /**
     * @var int|mixed
     */
    private int $quantity;
    /**
     * @var float|mixed
     */
    private float $price;
    /**
     * @var string|mixed
     */
    private string $description = '';
    /**
     * @var array
     */
    private array $categories;

    /**
     * @param string $name
     * @param float $price
     * @param int $quantity
     * @param string $description
     */
    public function __construct(array $newProduct, array $ids)
    {
        $productsInDB = Product::getAllProductNames();
        foreach ($productsInDB as $product) {
            if ($product->product_name == $newProduct['productName']) {
                throw new InvalidArgumentException(
                    'Product name already exists in database, please try a different name'
                );
            }
        }

        if ($newProduct['productName']) {
            if ($newProduct['quantity']) {
                if ($newProduct['price']) {
                    if ($ids) {
                        $this->name = $newProduct['productName'];
                        $this->quantity = $newProduct['quantity'];
                        $this->price = $newProduct['price'];
                        $this->description = $newProduct['description'];
                        foreach ($ids as $id) {
                            $this->categories[] = new Category($id);
                        }
                    } else {
                        throw new InvalidArgumentException('Please select at least 1 category');
                    }
                } else {
                    throw new InvalidArgumentException('Please input a valid price');
                }
            } else {
                throw new InvalidArgumentException('Please input a valid quantity');
            }
        } else {
            throw new InvalidArgumentException('Please input a valid product name');
        }
    }

    /**
     * @return Collection
     */
    public static function getAllProductNames(): Collection
    {
        return DB::table('products')
            ->select('product_name')
            ->get();
    }

    /**
     * @param array $newProduct
     * @return array
     */
    public static function formatNewProductCategories(array $newProduct): array
    {
        $newProductCategories = [];

        if (isset($newProduct['meat'])) {
            $newProductCategories[] += 1;
        } if (isset($newProduct['fish'])) {
            $newProductCategories[] += 2;
        } if (isset($newProduct['baked'])) {
            $newProductCategories[] += 3;
        } if (isset($newProduct['tinned'])) {
            $newProductCategories[] += 4;
        }

        return $newProductCategories;
    }

    /**
     * @param Product $newProduct
     * @return void
     */
    public static function addNewProduct(Product $newProduct): void
    {
        for ($i = count($newProduct->categories); $i < 4 ;$i++) {
            $newProduct->categories[$i] = new Category(NULL);
        }

        $lastId = DB::table('products')
            ->insertGetId([
                'product_name' => $newProduct->name,
                'quantity' => $newProduct->quantity,
                'price' => $newProduct->price,
                'description' => $newProduct->description
            ]);

        DB::table('junction_table')
            ->insert([
                'product_id' => $lastId,
                'category_id1' => $newProduct->categories[0]->getId(),
                'category_id2' => $newProduct->categories[1]->getId(),
                'category_id3' => $newProduct->categories[2]->getId(),
                'category_id4' => $newProduct->categories[3]->getId()
            ]);
    }
}
