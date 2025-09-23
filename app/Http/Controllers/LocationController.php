<?php

namespace App\Http\Controllers;

use App\Models\{Location, User};
use Illuminate\Http\Request;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $getRecord = Location::with('employees')->when(request()->name,function($q){
            $q->where('name','LIKE %',request()->name . '%');
        })->where('company_id',session('company_id'))->paginate(5);
        $employees = User::getRecord(request()->merge(['per_page' => 'all']));
        return view('backend.locations.index', compact('getRecord','employees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.locations.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'name' => 'required|string|max:255',
            'polygon' => 'required|string',
        ]);

        // Decode & re-encode to ensure it's valid JSON
        $polygon = json_decode($request->polygon, true);

        if (!$polygon) {
            return back()->withErrors(['polygon' => __('dashboard.invalid_polygon')]);
        }


        $location = new Location();
        $location->name = $request->name;
        $location->polygon = $polygon;
        $location->company_id = session('company_id');
        $location->branch_id = session('branch_id');
        $location->save();

        return redirect()->route('locations.index')->with('success', __('dashboard.location_created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Location $location)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Location $location)
    {
        return view('backend.locations.edit', compact('location'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Location $location)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'polygon' => 'required|string',
        ]);

        $polygon = json_decode($request->polygon, true);

        if (!$polygon) {
            return back()->withErrors(['polygon' => __('dashboard.invalid_polygon')]);
        }

        $location->update([
            'name' => $request->name,
            'polygon' => $polygon,
        ]);

        return redirect()->route('locations.index')->with('success', __('dashboard.location_updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Location $location)
    {
        $location->delete();
        return redirect()->route('locations.index')->with('success', __('dashboard.location_deleted'));
    }

    public function assignEmployees(Request $request)
    {
        $location = Location::findOrFail($request->location_id);
        $location->employees()->sync($request->employees);
        return back()->with('success', __('dashboard.employees_assigned'));
    }

    public function getAssignedEmployees($locationId)
    {
        $location = Location::with('employees:id')->findOrFail($locationId);
        return response()->json([
            'employees' => $location->employees->pluck('id')
        ]);
    }

}
