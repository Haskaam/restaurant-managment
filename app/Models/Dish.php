<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\User;

class Dish extends Model
{
    protected $fillable = [
        'category_id',
        'created_by',
        'name',
        'description',
        'net_price',
        'vat_rate',
        'is_available',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
