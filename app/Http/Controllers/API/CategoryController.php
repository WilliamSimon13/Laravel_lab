<?php

namespace App\Http\Controllers\API;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    $search = request('search');
    $query = Category::query()
        ->when($search, function(Builder $query, $search) {
            return $query->where('name', 'like', '%'.$search);
        });

    // Ghi log tìm kiếm danh mục
    Log::info('Tìm kiếm danh mục với từ khóa: ' . $search);

    return $query->simplePaginate();
}


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|unique:categories|max:255',
        'description' => 'required|max:255',
    ]);

    // Ghi log khi tạo mới danh mục
    Log::info('Tạo mới danh mục', ['name' => $validated['name'], 'description' => $validated['description']]);

    return Category::create($validated);
}


    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return $category;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
{
    $validated = $request->validate([
        'name' => 'nullable|unique:categories|max:255',
        'description' => 'nullable|max:255',
    ]);

    $category->update($validated);

    // Ghi log khi cập nhật danh mục
    Log::info('Cập nhật danh mục', ['category_id' => $category->id, 'name' => $validated['name'], 'description' => $validated['description']]);

    return $category;
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
{
    // Ghi log khi xóa danh mục
    Log::info('Xóa danh mục', ['category_id' => $category->id, 'name' => $category->name]);

    $category->delete();

    return $category;
}

}
