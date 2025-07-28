<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class CompanyInfoController extends Controller
{
    public function index(Request $request)
    {
        $user = User::with('company')
            ->where('company_id', Auth::user()->company_id)
            ->where('id', Auth::user()->id)
            ->first();

        if (!$user || !$user->company) {
            return redirect()->back()->with('error', 'User or company not found.');
        }

        $data['getRecord'] = $user;
        return view('backend.company-info.update', $data);
    }

    public function edit_update(Request $request)
    {
        $request->validate([
            'company_name'             => 'required|string|max:255',
            'company_phone'            => 'nullable|string|max:20',
            'company_address'          => 'nullable|string|max:500',
            'company_country'          => 'nullable|string|max:255',
            'commercial_registration'  => 'nullable|string|max:255',
            'tax_card'                 => 'nullable|string|max:255',
            // Updated validation to include SVG files
            'company_logo'             => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $user = User::with('company')
            ->where('company_id', Auth::user()->company_id)
            ->where('id', Auth::user()->id)
            ->first();

        if (!$user || !$user->company) {
            return redirect()->back()->with('error', 'User or company not found.');
        }

        // تحديث بيانات الشركة
        $company                            = $user->company;
        $company->name                      = trim($request->company_name);

        // Handle nullable fields safely
        $company->phone_number              = $request->company_phone ? trim($request->company_phone) : null;
        $company->address                   = $request->company_address ? trim($request->company_address) : null;
        $company->country                   = $request->company_country ? trim($request->company_country) : null;
        $company->commercial_registration   = $request->commercial_registration ? trim($request->commercial_registration) : null;
        $company->tax_card                  = $request->tax_card ? trim($request->tax_card) : null;

        // Handle logo upload with SVG support
if ($request->hasFile('company_logo')) {
    // Delete old logo if exists
    if ($company->logo) {
        $oldLogoPath = public_path('../../HR-Uploads/company_logos/' . $company->logo);
        if (file_exists($oldLogoPath)) {
            unlink($oldLogoPath);
        }
    }

    // Create directory if it doesn't exist
    $destinationPath = public_path('../../HR-Uploads/company_logos/');
    if (!file_exists($destinationPath)) {
        mkdir($destinationPath, 0755, true);
    }

    // Upload new logo - consistent with your PDF naming pattern
    $file = $request->file('company_logo');
    $filename = time() . '_' . $file->getClientOriginalName();

    // Additional validation for SVG files
    if ($file->getClientOriginalExtension() === 'svg') {
        // Validate SVG content for security
        $svgContent = file_get_contents($file->getRealPath());
        if ($this->validateSvgContent($svgContent)) {
            $file->move($destinationPath, $filename);
            $company->logo = $filename;
        } else {
            return redirect()->back()->with('error', 'Invalid SVG file. Please upload a valid SVG.');
        }
    } else {
        // For non-SVG files (images), proceed normally
        $file->move($destinationPath, $filename);
        $company->logo = $filename;
    }
}

        $company->save();

        return redirect('admin/company-info')->with('success', 'Company Information Successfully Updated!');
    }

    /**
     * Validate SVG content for security
     * This is a basic validation - you might want to use a more robust SVG sanitizer
     */
    private function validateSvgContent($svgContent)
    {
        // Basic security check - reject SVG files with script tags or dangerous elements
        $dangerousElements = ['<script', 'javascript:', 'onload=', 'onclick=', 'onerror=', 'onmouseover='];

        foreach ($dangerousElements as $element) {
            if (stripos($svgContent, $element) !== false) {
                return false;
            }
        }

        // Check if it's a valid XML structure
        $previousValue = libxml_use_internal_errors(true);
        $xml = simplexml_load_string($svgContent);
        libxml_use_internal_errors($previousValue);

        if ($xml === false) {
            return false;
        }

        // Check if root element is svg
        return $xml->getName() === 'svg';
    }
}
