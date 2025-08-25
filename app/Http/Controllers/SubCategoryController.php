<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class SubCategoryController extends Controller
{
    public function index()
    {
        $sub_categories = SubCategory::with('category')->get();  // Load sub_categories with their categories
        return view('sub_categories.index', compact('sub_categories'));
    }

    public function create()
    {
        $categories = Category::all();  // Fetch all categories for dropdown
        return view('sub_categories.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'ar_sub_category_name' => 'required|unique:sub_categories,sub_category_name',
            'sub_category_name' => 'required|unique:sub_categories,sub_category_name',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'category_id' => 'required',

        ]);
        $imageName = time() . '.' . $request->image->extension();
        $request->image->move(public_path('sub_categories/images'), $imageName);

        SubCategory::create([
            'ar_sub_category_name' => $request->ar_sub_category_name,
            'sub_category_name' => $request->sub_category_name,
            'category_id' => $request->category_id,
            'image' => $imageName
        ]);

        return redirect()->route('sub_categories.index')->with('success', 'SubCategory created successfully');
    }

    public function edit($id)
    {
        $subCategory = SubCategory::findOrFail($id);
        $categories = Category::all();  // Fetch categories for dropdown
        return view('sub_categories.edit', compact('subCategory', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $subCategory = SubCategory::findOrFail($id);

        $this->validate($request, [
            'ar_sub_category_name' => 'required|unique:sub_categories,ar_sub_category_name,' . $id,
            'sub_category_name' => 'required|unique:sub_categories,sub_category_name,' . $id,
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imageName = $subCategory->image;

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('sub_categories/images'), $imageName);

            if (file_exists(public_path('sub_categories/images/' . $subCategory->image))) {
                unlink(public_path('sub_categories/images/' . $subCategory->image));
            }
        }

        $subCategory->update([
            'ar_sub_category_name' => $request->ar_sub_category_name,
            'sub_category_name' => $request->sub_category_name,
            'category_id' => $request->category_id,
            'image' => $imageName
        ]);

        return redirect()->route('sub_categories.index')->with('success', 'SubCategory updated successfully');
    }

    public function destroy($id)
    {
        $subCategory = SubCategory::findOrFail($id);
        if($subCategory->products->count() > 0){
            return redirect()->route('sub_categories.index')->with('error', 'You must remove the related products first');
        }else{
            if (file_exists(public_path('sub_categories/images/' . $subCategory->image))) {
                unlink(public_path('sub_categories/images/' . $subCategory->image));
            }
            $subCategory->delete();
            return redirect()->route('sub_categories.index')->with('success', 'SubCategory deleted successfully');
        }

    }
}
