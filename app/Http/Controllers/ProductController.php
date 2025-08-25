<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function getSubcategories($categoryId)
    {
        $subcategories = \App\Models\SubCategory::where('category_id', $categoryId)->get();
        return response()->json($subcategories);
    }
    public function updateBarcode(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:products,id',
            'barcode' => 'required|string|unique:products,barcode'
        ]);

        $product = Product::withoutGlobalScope('active')->findOrFail($request->id);
        $product->barcode = $request->barcode;
        $product->save();

        return response()->json(['success' => true, 'message' => 'Barcode updated successfully!']);
    }
    
   public function index(Request $request)
{
    $search = $request->input('search');
    $hasBarcode = $request->input('has_barcode');

    $products = Product::withoutGlobalScope('active')
        ->with(['category', 'sub_category', 'brand']) // Eager loading to solve N+1 problem
        ->when($search, function ($query) use ($search) {
            return $query->where(function ($q) use ($search) {
                $q->where('product_name', 'LIKE', "%{$search}%")
                  ->orWhere('barcode', 'LIKE', "%{$search}%")
                  ->orWhere('price', 'LIKE', "%{$search}%");
            });
        })
        ->when($hasBarcode !== null, function ($query) use ($hasBarcode) {
            if ($hasBarcode == '1') {
                return $query->whereNotNull('barcode')->where('barcode', '!=', '');
            } else {
                return $query->where(function($q) {
                    $q->whereNull('barcode')->orWhere('barcode', '');
                });
            }
        })
        ->paginate(50) // Show 50 products per page instead of all 4000
        ->appends($request->query()); // Preserve search parameters in pagination links

    return view('products.index', compact('products', 'search', 'hasBarcode'));
}

    public function create()
    {
        return view('products.create');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('products.edit', compact('product'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'product_name' => 'required',
            'brand_id' => 'nullable|exists:brands,id',
            'category_id' => 'required|exists:categories,id',
            'sub_category_id' => 'nullable|exists:sub_categories,id',
            'price' => 'required',
            'product_description' => 'required',
            'product_unit' => 'required',
            'product_status' => 'required',
            'image' => 'required|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'gallary' => 'required',
            'gallary.*' => 'file|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $input = $request->all();

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('products/images'), $imageName);
            $input['image'] = $imageName;
        }

        $gallary = [];
        if ($request->hasFile('gallary')) {
            foreach ($request->file('gallary') as $gallImage) {
                $gallImageName = time() . '_' . uniqid() . '.' . $gallImage->getClientOriginalExtension();
                $gallImage->move(public_path('products/gallary'), $gallImageName);
                $gallary[] = $gallImageName;
            }
        }
        $input['gallary'] = $gallary;
        Product::create($input);
        return redirect()->route('products.index')->with('success', 'Product created successfully');
    }
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'product_name' => 'required',
            'brand_id' => 'nullable|exists:brands,id',
            'category_id' => 'required|exists:categories,id',
            'sub_category_id' => 'nullable|exists:sub_categories,id',
            'price' => 'required',
            'product_description' => 'required',
            'product_unit' => 'required',
            'product_status' => 'required',
            'image' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:2048', // Nullable means the image is optional
            'gallary' => 'nullable',
            'gallary.*' => 'file|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $product = Product::withoutGlobalScope('active')->findOrFail($id);
        $input = $request->all();

        // Handle main image replacement
        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            if ($product->image && file_exists(public_path("products/images/{$product->image}"))) {
                unlink(public_path("products/images/{$product->image}"));
            }

            // Upload the new image
            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('products/images'), $imageName);
            $input['image'] = $imageName;
        }

        // Handle gallery replacement
        if ($request->hasFile('gallary')) {
            // Delete the old gallery images
            foreach ($product->gallary as $gallaryImage) {
                if (file_exists(public_path("products/gallary/{$gallaryImage}"))) {
                    unlink(public_path("products/gallary/{$gallaryImage}"));
                }
            }

            // Upload new gallery images
            $gallary = [];
            foreach ($request->file('gallary') as $gallImage) {
                $gallImageName = time() . '_' . uniqid() . '.' . $gallImage->getClientOriginalExtension();
                $gallImage->move(public_path('products/gallary'), $gallImageName);
                $gallary[] = $gallImageName;
            }
            $input['gallary'] = $gallary;
        }

        // Update the product with the new input
        $product->update($input);

        return redirect()->route('products.index')->with('success', 'Product updated successfully');
    }

    public function destroy($id)
    {
        $product = Product::withoutGlobalScope('active')->findOrFail($id);

        // Delete the main product image
        if (file_exists(public_path("products/images/{$product->image}"))) {
            unlink(public_path("products/images/{$product->image}"));
        }

        // Delete gallery images
        foreach ($product->gallary as $gallaryImage) {
            if (file_exists(public_path("products/gallary/{$gallaryImage}"))) {
                unlink(public_path("products/gallary/{$gallaryImage}"));
            }
        }

        // Delete associated product rates
        $product->productRates()->delete();

        // Delete the product record from the database
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product and associated rates deleted successfully');
    }
    public function toggle(Request $request)
    {
        $product = Product::withoutGlobalScope('active')->find($request->id);
        if($product->product_status == 0){
            $product->update([
                'product_status'=>1
            ]);
        }else{
            $product->update([
                'product_status'=>0
            ]);
        }
        return redirect()->route('products.index')->with('success', 'product_status_changes_successfully'); // Redirect to the index with a success message
    }
}
