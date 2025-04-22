<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index() {
        return Category::all();
    }

    public function show($id) {
        return Category::findOrFail($id);
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required|unique:categories',
            'slug' => 'required|unique:categories',
        ]);

        return Category::create($request->all());
    }

    public function update(Request $request, $id) {
        $category = Category::findOrFail($id);
        $category->update($request->all());
        return $category;
    }

    public function destroy($id) {
        return Category::destroy($id);
    }
}
