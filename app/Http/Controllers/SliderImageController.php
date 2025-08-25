<?php

namespace App\Http\Controllers;

use App\Models\SliderImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SliderImageController extends Controller
{
    /**
     * Display a listing of the slider images.
     */
    public function index()
    {
        $sliderImages = SliderImage::all();
        return view('slider_images.index', compact('sliderImages'));
    }

    /**
     * Show the form for creating a new slider image.
     */
    public function create()
    {
        return view('slider_images.create');
    }

    /**
     * Store a new slider image in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'order' => 'required',
        ]);

   

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('slider/images'), $imageName);

            // Insert the new record
            SliderImage::create([
                'image' => $imageName,
                'order' => $request->order,
            ]);

            return redirect()->route('slider_images.index')
                ->with('success', __('messages.upload_success'));
        }

        return redirect()->route('slider_images.index')
            ->with('error', __('messages.upload_error'));
    }
    public function show($id)
    {
        $sliderImage = SliderImage::findOrFail($id);
        return view('slider_images.show', compact('sliderImage'));
    }
    public function edit($id)
    {
        $sliderImage = SliderImage::findOrFail($id);
        return view('slider_images.edit', compact('sliderImage'));
    }
    public function update(Request $request, $id)
    {
        $sliderImage = SliderImage::findOrFail($id);

        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'order' => 'nullable',
        ]);

        if ($request->hasFile('image')) {
            if ($sliderImage->image) {
                Storage::disk('public')->delete('slider/images/' . $sliderImage->image);
            }

            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('slider/images'), $imageName);
            $sliderImage->image = $imageName;
        }
        $sliderImage->order = $request->order;
        $sliderImage->save();

        return redirect()->route('slider_images.index')
            ->with('success', __('messages.update_success'));
    }
    public function destroy($id)
    {
        $sliderImage = SliderImage::findOrFail($id);

        // Delete the image file
        Storage::disk('public')->delete($sliderImage->image);

        // Delete the database record
        $sliderImage->delete();

        return redirect()->route('slider_images.index')
            ->with('success', 'Slider image deleted successfully.');
    }
}
