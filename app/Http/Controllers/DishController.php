<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dish;
use App\Models\Category;

class DishController extends Controller
{
    public function index(Request $request)
{
    $query = Dish::with('category');

    if($request->filled('search')) {
        $query->where(
            'name',
            'like',
            '%'. $request->search . '%'
        );
    }

    $allowedSorts = [
        'name',
        'net_price',
        'vat_rate',
        'is_available',
    ];

    $sort = in_array($request->sort, $allowedSorts)
        ? $request->sort
        : 'name';

    $direction = $request->direction === 'desc'
        ? 'desc'
        : 'asc';



    $dishes = $query->orderBy($sort, $direction)->get();

    return view('dishes.index', compact('dishes', 'sort', 'direction'));
}



public function create()
{
    $categories = Category::where('is_active', true)->get();

    return view('dishes.create', compact('categories'));
}



public function store(Request $request)
{
    $data = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'description' => ['nullable', 'string'],
        'category_id' => ['required', 'exists:categories,id'],
        'net_price' => ['required', 'numeric', 'min:0'],
        'vat_rate' => ['required', 'numeric', 'min:0', 'max:100'],
    ]);

    Dish::create([
        'name' => $data['name'],
        'description' => $data['description'],
        'category_id' => $data['category_id'],
        'net_price' => $data['net_price'],
        'vat_rate' => $data['vat_rate'],
        'created_by' => $request->user()->id,
        'is_available' => true,
    ]);

    return redirect()
        ->route('dishes.index')
        ->with('success', 'Danie zostało dodane.');
}



public function edit(Dish $dish)
{
    $categories = Category::where('is_active', true)->get();

    return view('dishes.edit', compact('dish', 'categories'));
}



public function update(Request $request, Dish $dish)
{
    $data = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'description' => ['nullable', 'string'],
        'category_id' => ['required', 'exists:categories,id'],
        'net_price' => ['required', 'numeric', 'min:0'],
        'vat_rate' => ['required', 'numeric', 'min:0', 'max:100'],
    ]);

    $dish->update([
        'name' => $data['name'],
        'description' => $data['description'],
        'category_id' => $data['category_id'],
        'net_price' => $data['net_price'],
        'vat_rate' => $data['vat_rate'],
    ]);

    return redirect()
        ->route('dishes.index')
        ->with('success', 'Danie zostało zaktualizowane.');
}



public function toggleAvailability(Dish $dish)
{
    $dish->update([
        'is_available' => ! $dish->is_available,
    ]);

    return redirect()
        ->route('dishes.index')
        ->with('success', 'Dostępność dania została zmieniona.');
}
}
