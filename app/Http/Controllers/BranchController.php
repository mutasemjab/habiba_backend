<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BranchController extends Controller
{
    /**
     * Display a listing of branches.
     */
    public function index()
    {
        $branches = Branch::all();
        return view('branches.index', compact('branches')); // Return to branches listing view
    }

    /**
     * Show the form for creating a new branch.
     */
    public function create()
    {
        return view('branches.create'); // Return the view for creating a new branch
    }

    /**
     * Store a newly created branch.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'branch_title' => 'required|string|max:255',
            'branch_long' => 'required|string',
            'branch_lat' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput(); // Redirect back with validation errors
        }

        Branch::create($request->only(['branch_title', 'branch_long', 'branch_lat']));

        return redirect()->route('branches.index')->with('success', 'Branch created successfully'); // Redirect to branches index with success message
    }

    /**
     * Display the specified branch.
     */
    public function show($id)
    {
        $branch = Branch::find($id);

        if (!$branch) {
            return redirect()->route('branches.index')->with('error', 'Branch not found'); // Redirect to branches index with error message
        }

        return view('branches.show', compact('branch')); // Return branch details view
    }

    /**
     * Show the form for editing the specified branch.
     */
    public function edit($id)
    {
        $branch = Branch::find($id);

        if (!$branch) {
            return redirect()->route('branches.index')->with('error', 'Branch not found');
        }

        return view('branches.edit', compact('branch')); // Return the branch edit view
    }

    /**
     * Update the specified branch.
     */
    public function update(Request $request, $id)
    {
        $branch = Branch::find($id);

        if (!$branch) {
            return redirect()->route('branches.index')->with('error', 'Branch not found');
        }

        $validator = Validator::make($request->all(), [
            'branch_title' => 'required|string|max:255',
            'branch_long' => 'required|string',
            'branch_lat' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $branch->update($request->only(['branch_title', 'branch_long', 'branch_lat']));

        return redirect()->route('branches.index')->with('success', 'Branch updated successfully');
    }

    /**
     * Remove the specified branch from storage.
     */
    public function destroy($id)
    {
        $branch = Branch::find($id);

        if (!$branch) {
            return redirect()->route('branches.index')->with('error', 'Branch not found');
        }

        $branch->delete();

        return redirect()->route('branches.index')->with('success', 'Branch deleted successfully');
    }
}