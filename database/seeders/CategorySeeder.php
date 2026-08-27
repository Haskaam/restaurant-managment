<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $director = User::whereHas('roles', function ($query) {
            $query->where('name', 'director');
        })->firstOrFail();

        $categories = [
            [
                'name' => 'Przystawki',
                'description' => 'Przystawki i małe dania',
            ],
            [
                'name' => 'Zupy',
                'description' => 'Zupy',
            ],
            [
                'name' => 'Dania główne',
                'description' => 'Dania główne',
            ],
            [
                'name' => 'Desery',
                'description' => 'Desery',
            ],
            [
                'name' => 'Napoje',
                'description' => 'Napoje zimne i gorące',
            ],
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'description' => $category['description'],
                'is_active' => true,
                'created_by' => $director->id,
            ]);
        }
    }
}
