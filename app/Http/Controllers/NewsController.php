<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class NewsController extends Controller
{
    public function index()
{
    $companyId = auth()->user()->company_id;
    $news = News::where('company_id', $companyId)
                ->orderBy('news_date', 'desc')
                ->paginate(10);

    return view('backend.News.list', compact('news'));
}

    public function create()
    {
        return view('backend.News.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'news_date' => 'required|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        $data = $request->all();
        $data['company_id'] = auth()->user()->company_id;

        // Handle image upload
        if ($request->hasFile('image')) {
            $data['image'] = $this->uploadImage($request->file('image'));
        }

        News::create($data);

        return redirect()->route('news.index')->with('success', 'News created successfully!');
    }

    public function show(News $news)
    {
        return view('backend.News.show', compact('news'));
    }

    public function edit(News $news)
    {
        return view('backend.News.edit', compact('news'));
    }

    public function update(Request $request, News $news)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'news_date' => 'required|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        $data = $request->all();
        $data['company_id'] = auth()->user()->company_id;

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            $this->deleteImage($news->image);

            // Upload new image
            $data['image'] = $this->uploadImage($request->file('image'));
        }

        $news->update($data);

        return redirect()->route('news.index')->with('success', 'News updated successfully!');
    }

    public function destroy(News $news)
    {
        // Delete image if exists
        $this->deleteImage($news->image);

        $news->delete();

        return redirect()->route('news.index')->with('success', 'News deleted successfully!');
    }

    // Private function to handle image upload
    private function uploadImage($file)
    {
        $filename = time() . '_' . $file->getClientOriginalName();
        $destinationPath = $this->getImagePath();

        // Create directory if it doesn't exist
        $this->ensureDirectoryExists($destinationPath);

        // Move the file to the destination
        $file->move($destinationPath, $filename);

        return $filename;
    }

    // Private function to delete image
    private function deleteImage($imageName)
    {
        if ($imageName && File::exists($this->getImagePath() . $imageName)) {
            File::delete($this->getImagePath() . $imageName);
        }
    }

    // Private function to get image path
    private function getImagePath()
    {
        return public_path('../../HR-Uploads/newsimages/');
    }

    // Private function to ensure directory exists
    private function ensureDirectoryExists($path)
    {
        if (!File::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }
    }

    // Function to serve images (similar to your logo route)
    public function viewImage($filename)
    {
        $filePath = $this->getImagePath() . $filename;

        if (!File::exists($filePath)) {
            abort(404);
        }

        $file = File::get($filePath);
        $type = File::mimeType($filePath);

        return response($file, 200)->header("Content-Type", $type);
    }

    // Static function to get recent news for dashboard or other uses
    static public function getRecentNewsForDashboard($limit = 5)
    {
        return News::getRecentNews($limit);
    }

    // Function to get news by current user's company
    public function getCompanyNews($limit = 10)
    {
        $companyId = auth()->user()->company_id;
        return News::getByCompany($companyId, $limit);
    }

    // Function to filter news (can be used for AJAX requests)
    public function filterNews(Request $request)
    {
        $query = News::query()->with('company');

        // Filter by date range
        if ($request->has('start_date') && $request->start_date) {
            $query->whereDate('news_date', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date) {
            $query->whereDate('news_date', '<=', $request->end_date);
        }

        // Filter by company (if admin wants to see all companies)
        if ($request->has('company_id') && $request->company_id) {
            $query->where('company_id', $request->company_id);
        } else {
            // Default to current user's company
            $query->where('company_id', auth()->user()->company_id);
        }

        // Search by title or description
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $news = $query->orderBy('news_date', 'desc')->paginate(10);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('backend.News.partials.news_list', compact('news'))->render(),
                'pagination' => $news->links()->render()
            ]);
        }

        return view('backend.News.list', compact('news'));
    }

    // Function to toggle news status (if you want to add active/inactive functionality)
    public function toggleStatus(News $news)
    {
        $news->update(['is_active' => !$news->is_active]);

        $status = $news->is_active ? 'activated' : 'deactivated';
        return response()->json([
            'success' => true,
            'message' => "News {$status} successfully!",
            'status' => $news->is_active
        ]);
    }

    // Function to bulk delete news
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'news_ids' => 'required|array',
            'news_ids.*' => 'exists:news,id'
        ]);

        $newsItems = News::whereIn('id', $request->news_ids)->get();

        foreach ($newsItems as $news) {
            $this->deleteImage($news->image);
            $news->delete();
        }

        return response()->json([
            'success' => true,
            'message' => count($request->news_ids) . ' news items deleted successfully!'
        ]);
    }
}
