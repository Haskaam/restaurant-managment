<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Dish;
use App\Models\User;

class Category extends Model
{
    protected $fillable = [
        'name',
        'description',
        'is_active',
        'created_by',
    ];

    public function dishes()
    {
        return $this->hasMany(Dish::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
