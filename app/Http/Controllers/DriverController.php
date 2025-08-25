<?php

namespace App\Http\Controllers;

use App\Models\Driver; // Ensure you have a Driver model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DriverController extends Controller
{
    // Display a listing of the drivers
    public function index()
    {
        $drivers = Driver::all();
        return view('drivers.index', compact('drivers'));
    }

    public function create()
    {
        return view('drivers.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            // Driver Data
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:drivers',
            'password' => 'required|string|min:8|confirmed',
            'nid' => 'required|string|max:20',
            'status' => 'required|boolean',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'notes' => 'nullable|string|max:500',
            'mobile' => 'required|string',

            // Vehicle Data
            'vehichle_color' => 'required|string|max:50',
            'vehichle_number' => 'required|string|max:50|unique:drivers',
            'vehichle_brand' => 'required|string|max:50',
            'vehichle_type' => 'required|string|max:50',
            'vehichle_model' => 'required|string|max:50',
            'vehichle_model_year' => 'required|numeric|digits:4|min:1900|max:' . date('Y'),
            'vehichle_license_ends_at' => 'required|date|after:today',

            // License Information
            'licence_name' => 'required|string|max:255',
            'licence_grade' => 'required|string|max:50',
            'licence_issue_date' => 'required|date|before:today',
            'licence_end_date' => 'required|date|after:licence_issue_date',
        ]);

        // Prepare data for insertion
        $driverData = $request->except(['password_confirmation']);
        $driverData['password'] = bcrypt($request->input('password'));

        // Handle image upload
        if ($request->hasFile('image')) {
            $imageName = uniqid() . '.' . $request->image->extension();
            $request->image->move(public_path('storage/drivers/images'), $imageName);
            $driverData['image'] = 'drivers/images/' . $imageName;
        }

        // Create the driver
        Driver::create($driverData);

        return redirect()->route('drivers.index')->with('success', __('messages.driver_created_successfully'));
    }

    public function show(Driver $driver)
    {
        return view('drivers.show', compact('driver'));
    }

    public function edit($id)
    {
        $driver = Driver::find($id);
        return view('drivers.edit', compact('driver'));
    }

    public function update(Request $request, $id)
    {
        $driver = Driver::findOrFail($id);

        // Validation
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:drivers,username,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'nid' => 'required|string|unique:drivers,nid,' . $id,
            'mobile' => 'required|string|unique:drivers,mobile,' . $id,
            'vehichle_color' => 'required|string',
            'vehichle_number' => 'required|string|unique:drivers,vehichle_number,' . $id,
            'vehichle_license_ends_at' => 'required|date',
            'status' => 'required|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Prepare data for update
        $driverData = $request->except('password', 'password_confirmation', 'image');

        // Handle password update
        if ($request->filled('password')) {
            $driverData['password'] = bcrypt($request->input('password'));
        }

        // Handle image update
        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            if ($driver->image) {
                Storage::disk('public')->delete($driver->image);
            }

            // Store the new image
            $imagePath = $request->file('image')->store('drivers/images', 'public');
            $driverData['image'] = $imagePath;
        }

        // Update driver
        $driver->update($driverData);

        // Redirect with success message
        return redirect()->route('drivers.index')->with('success', __('messages.driver_updated_successfully'));
    }

    public function toggle(Request $request)
    {
        $driver = Driver::find($request->id);
        if ($driver->status == 0) {
            $driver->update([
                'status' => 1,
            ]);
        } else {
            $driver->update([
                'status' => 0,
            ]);
        }
        return redirect()->route('drivers.index')->with('success', 'driver_status_changes_successfully'); // Redirect to the index with a success message
    }
    public function destroy($id)
    {
        $driver = Driver::findOrFail($id);
        if ($driver->image) {
            $imagePath = public_path('storage/' . $driver->image);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        $driver->delete();
        return redirect()->route('drivers.index')->with('success', __('messages.driver_deleted_successfully'));
    }

}