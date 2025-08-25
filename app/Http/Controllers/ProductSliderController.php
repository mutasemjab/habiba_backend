<?php

namespace App\Http\Controllers;

use App\Models\ProductSlider;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductSliderController extends Controller
{
    /**
     * Display a listing of the slider images.
     */
    public function index()
    {
        $sliderProducts= ProductSlider::all();
        return view('slider_products.index', compact('sliderProducts'));
    }

    /**
     * Show the form for creating a new slider image.
     */
    public function create()
    {
         $products= SubCategory::all();
        return view('slider_products.create', compact('products'));
    }

    /**
     * Store a new slider image in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'sub_category' => 'required|exists:sub_categories,id'
        ]);

        if (ProductSlider::count() >= 5) {
            return redirect()->route('slider_products.index')
                ->with('error', __('messages.upload_error'));
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('slider/images'), $imageName);

            // Insert the new record
            ProductSlider::create([
                'image' => $imageName,
                'sub_category_id' => $request->sub_category,
            ]);

            return redirect()->route('slider_products.index')
                ->with('success', __('messages.upload_success'));
        }

        return redirect()->route('slider_products.index')
            ->with('error', __('messages.upload_error'));
    }
    public function show($id)
    {
        $ProductSlider = ProductSlider::findOrFail($id);
        return view('slider_products.show', compact('ProductSlider'));
    }
    public function edit($id)
    {
        $sliderImage = ProductSlider::findOrFail($id);
        $products= SubCategory::all();
        return view('slider_products.edit', compact('sliderImage','products'));
    }
    public function update(Request $request, $id)
    {
        $ProductSlider = ProductSlider::findOrFail($id);

        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
              'sub_category' => 'required|exists:sub_categories,id'
        ]);

        if ($request->hasFile('image')) {
            if ($ProductSlider->image) {
                Storage::disk('public')->delete('slider/images/' . $ProductSlider->image);
            }

            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('slider/images'), $imageName);
            $ProductSlider->image = $imageName;
        }
         $ProductSlider->sub_category_id = $request->sub_category;
        $ProductSlider->save();

        return redirect()->route('slider_products.index')
            ->with('success', __('messages.update_success'));
    }
    public function destroy($id)
    {
        $ProductSlider = ProductSlider::findOrFail($id);

        // Delete the image file
        Storage::disk('public')->delete($ProductSlider->image);

        // Delete the database record
        $ProductSlider->delete();

        return redirect()->route('slider_products.index')
            ->with('success', 'Slider image deleted successfully.');
    }
}
