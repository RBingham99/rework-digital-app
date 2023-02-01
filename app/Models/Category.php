<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Category extends Model
{
    use HasFactory;

    /**
     * @var int|null
     */
    private ?int $id;
    /**
     * @var string|mixed|null
     */
    private ?string $name;

    /**
     * @param int|null $id
     */
    public function __construct(?int $id)
    {
        if (isset($id)) {
            $this->id = $id;
            $this-> name =  DB::table('categories')
                ->where('id', $id)
                ->value('category_name');
        } else {
            $this->id = NULL;
            $this->name = NULL;
        }
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }
}
