<?php

namespace App\Http\Controllers;

use App\Models\PerformanceCriteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PerformanceCriteriaController extends Controller
{
    public function index()
    {
        $companyId = Auth::user()->company_id;

        $criteria = PerformanceCriteria::forCompany($companyId)
            ->ordered()
            ->paginate(15);

        return view('backend.performance-criteria.index', compact('criteria'));
    }

    public function create()
    {
        return view('backend.performance-criteria.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $companyId = Auth::user()->company_id;

        // Get the next sort order
        $maxSortOrder = PerformanceCriteria::forCompany($companyId)->max('sort_order');

        PerformanceCriteria::create([
            'company_id' => $companyId,
            'name' => $request->name,
            'description' => $request->description,
            'is_active' => $request->boolean('is_active', true),
            'sort_order' => ($maxSortOrder ?? 0) + 1,
        ]);

return redirect()->route('performance-criteria.index')
    ->with('success', __('h_criteria.created_successfully'));
}

    public function edit($id)
    {
        $criteria = PerformanceCriteria::forCompany(Auth::user()->company_id)
            ->findOrFail($id);

        return view('backend.performance-criteria.edit', compact('criteria'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $criteria = PerformanceCriteria::forCompany(Auth::user()->company_id)
            ->findOrFail($id);

        $criteria->update([
            'name' => $request->name,
            'description' => $request->description,
            'is_active' => $request->boolean('is_active', true),
        ]);

return redirect()->route('performance-criteria.index')
    ->with('success', __('h_criteria.updated_successfully'));

  }

    public function destroy($id)
    {
        $criteria = PerformanceCriteria::forCompany(Auth::user()->company_id)
            ->findOrFail($id);

        $criteria->delete();

return redirect()->route('performance-criteria.index')
    ->with('success', __('h_criteria.deleted_successfully'));
 }



}
