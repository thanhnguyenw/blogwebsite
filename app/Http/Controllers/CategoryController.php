<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $parent_categories = Category::whereNull('parent_id')
            ->orderBy('created_at', 'desc')
            ->get();
        $categories = Category::all();
        return view('admin.category.index', compact('categories', 'parent_categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);
        $category = new Category();
        $category->name = $request->name;
        $category->parent_id = $request->parent_id ?? null;
        $category->save();
        return redirect()->route('category.index')->with('success', 'Danh mục đã được tạo');
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $cate = Category::find($id);
        $categories = Category::all()->except($id);
        return view('admin.category.edit', compact('categories', 'cate'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required',
        ]);
        $category = Category::find($id);
        $category->name = $request->name;
        $category->parent_id = $request->parent_id ?? null;
        $category->save();
        return redirect()->route('category.index')->with('success', 'Danh mục đã được cập nhật');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // delete category
        $category = Category::find($id);
        if(!$category){
            return redirect()->route('category.index')->with('error', 'Danh mục bạn muốn xóa không tồn tại');
        }
        $category->delete();

        return redirect()->route('category.index');
    }
}
