<?php
namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        // Using the getRecord method to retreve the filtering and pagination
        $companies = Company::getRecord();

        return view('admins.companies.index', compact('companies'));
    }

    public function destroy(Company $company)
    {
        $company->delete();
        return back()->with('success', 'Company deleted successfully!'); // Redirect to the same page
    }
}
