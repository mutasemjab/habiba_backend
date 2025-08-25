<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'order' => 'required|unique:categories,order',
            'ar_category_name' => 'required|unique:categories,ar_category_name',
            'category_name' => 'required|unique:categories,category_name',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_it_smoke' => 'required|in:1,2',
        ]);

        $imageName = time() . '.' . $request->image->extension();
        $request->image->move(public_path('categories/images'), $imageName);

        Category::create([
            'order' => $request->order,
            'ar_category_name' => $request->ar_category_name,
            'category_name' => $request->category_name,
            'image' => $imageName,
            'is_it_smoke' => $request->is_it_smoke
        ]);

        return redirect()->route('categories.index')->with('success', 'Category created successfully');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $this->validate($request, [
            'order' => 'required|unique:categories,order,' . $id,
            'ar_category_name' => 'required|unique:categories,ar_category_name,' . $id,
            'category_name' => 'required|unique:categories,category_name,' . $id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_it_smoke' => 'required|in:1,2',
        ]);

        $imageName = $category->image;  // Default to current image

        if ($request->hasFile('image')) {
            // Generate a new image name
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('categories/images'), $imageName);

            // Remove the old image if it exists
            if (file_exists(public_path('categories/images/' . $category->image))) {
                unlink(public_path('categories/images/' . $category->image));
            }
        }

        // Update the category details
        $category->update([
            'order' => $request->order,
            'ar_category_name' => $request->ar_category_name,
            'category_name' => $request->category_name,
            'image' => $imageName,
            'is_it_smoke' => $request->is_it_smoke
        ]);

        return redirect()->route('categories.index')->with('success', 'Category updated successfully');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        if($category->products->count() > 0){
            return redirect()->route('categories.index')->with('error', 'You must remove the products related to this category first');
        }else{
            if (file_exists(public_path('categories/images/' . $category->image))) {
                unlink(public_path('categories/images/' . $category->image));
            }
            $category->delete();
            return redirect()->route('categories.index')->with('success', 'Category deleted successfully');
        }
    }
}
