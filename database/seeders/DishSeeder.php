<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Dish;
use App\Models\User;
use Illuminate\Database\Seeder;

class DishSeeder extends Seeder
{
    public function run(): void
    {
        $director = User::whereHas('roles', function ($query) {
            $query->where('name', 'director');
        })->firstOrFail();

        $mainCourses = Category::where('name', 'Dania główne')->firstOrFail();
        $soups = Category::where('name', 'Zupy')->firstOrFail();
        $desserts = Category::where('name', 'Desery')->firstOrFail();

        Dish::create([
            'category_id' => $mainCourses->id,
            'created_by' => $director->id,
            'name' => 'Burger wołowy',
            'description' => 'Burger z wołowiną, warzywami i sosem',
            'net_price' => 35.00,
            'vat_rate' => 23.00,
            'is_available' => true,
        ]);

        Dish::create([
            'category_id' => $soups->id,
            'created_by' => $director->id,
            'name' => 'Zupa pomidorowa',
            'description' => 'Klasyczna zupa pomidorowa',
            'net_price' => 18.00,
            'vat_rate' => 23.00,
            'is_available' => true,
        ]);

        Dish::create([
            'category_id' => $desserts->id,
            'created_by' => $director->id,
            'name' => 'Sernik',
            'description' => 'Sernik na zimno',
            'net_price' => 20.00,
            'vat_rate' => 23.00,
            'is_available' => true,
        ]);
    }
}
