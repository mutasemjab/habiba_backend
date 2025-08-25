<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::all();
        return view('brand.index', compact('brands'));
    }

    public function create()
    {
        return view('brand.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'ar_brand_name' => 'required|unique:brands',
            'brand_name' => 'required|unique:brands',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $imageName = time() . '.' . $request->image->extension();
        $request->image->move(public_path('brands/images'), $imageName);
        Brand::create([
            'ar_brand_name' => $request->ar_brand_name,
            'brand_name' => $request->brand_name,
            'image' => $imageName
        ]);
        return redirect()->route('brands.index')->with('success', 'Brand created successfully');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $brand = Brand::findOrFail($id);
        return view('brand.edit', compact('brand'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'brand_name' => 'required|unique:brands,brand_name,' . $id,
            'ar_brand_name' => 'required|unique:brands,ar_brand_name,' . $id,
        ]);
        $brand = Brand::find($id);
        if ($request->file('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('brands/images'), $imageName);
            if (file_exists(public_path('brands/images/' . $brand->image))) {
                unlink(public_path('brands/images/' . $brand->image));
            }
            $imageName = $imageName;
        } else {
            $imageName = $brand->image;
        }


        Brand::where('id', $id)->update([
            'ar_brand_name' => $request->ar_brand_name,
            'brand_name' => $request->brand_name,
            'image' => $imageName
        ]);
        return redirect()->route('brands.index')->with('success', 'Brand updated successfully');
    }

    public function destroy($id)
    {

        $brand = Brand::where('id', $id)->first();
        try{
            if (file_exists(public_path('brands/images/' . $brand->image))) {
                unlink(public_path('brands/images/' . $brand->image));
            }
            Brand::where('id', $id)->delete();
            return redirect()->route('brands.index')->with('success', 'Brand deleted successfully');
        }catch(\Throwable $th){
            return redirect()->route('brands.index')->with('error', 'You must remove the related products first');
        }
    }
}
