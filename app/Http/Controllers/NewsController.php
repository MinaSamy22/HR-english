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

return redirect()->route('news.index')->with('success', __('h_news.created_successfully'));
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

return redirect()->route('news.index')->with('success', __('h_news.updated_successfully'));
    }

    public function destroy(News $news)
    {
        // Delete image if exists
        $this->deleteImage($news->image);

        $news->delete();

return redirect()->route('news.index')->with('success', __('h_news.deleted_successfully'));    }

    // Private function to handle image upload
    private function uploadImage($file)
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
        $destinationPath = $this->getImagePath();

        // Create directory if it doesn't exist
        $this->ensureDirectoryExists($destinationPath);

        // If it's a standard image (jpg, jpeg, png, webp), optimize it using GD
        if (in_array($extension, ['jpg', 'jpeg', 'png', 'webp'])) {
            $this->optimizeAndSaveImage($file->getRealPath(), $destinationPath . $filename, $extension);
        } else {
            $file->move($destinationPath, $filename);
        }

        return $filename;
    }

    // Optimize and compress image using GD
    private function optimizeAndSaveImage($sourcePath, $destinationPath, $extension)
    {
        list($origWidth, $origHeight, $imageType) = @getimagesize($sourcePath);

        if (!$origWidth || !$origHeight) {
            copy($sourcePath, $destinationPath);
            return;
        }

        // Max dimension for display (1600px is more than enough for crisp full-HD view)
        $maxDimension = 1600;
        $width = $origWidth;
        $height = $origHeight;

        if ($origWidth > $maxDimension || $origHeight > $maxDimension) {
            $ratio = $origWidth / $origHeight;
            if ($ratio > 1) {
                $width = $maxDimension;
                $height = (int) round($maxDimension / $ratio);
            } else {
                $height = $maxDimension;
                $width = (int) round($maxDimension * $ratio);
            }
        }

        $newImage = imagecreatetruecolor($width, $height);

        // Preserve transparency for PNG and WebP
        if ($imageType == IMAGETYPE_PNG || $imageType == IMAGETYPE_WEBP) {
            imagealphablending($newImage, false);
            imagesavealpha($newImage, true);
            $transparent = imagecolorallocatealpha($newImage, 255, 255, 255, 127);
            imagefilledrectangle($newImage, 0, 0, $width, $height, $transparent);
        }

        switch ($imageType) {
            case IMAGETYPE_JPEG:
                $sourceImage = @imagecreatefromjpeg($sourcePath);
                break;
            case IMAGETYPE_PNG:
                $sourceImage = @imagecreatefrompng($sourcePath);
                break;
            case IMAGETYPE_WEBP:
                $sourceImage = function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($sourcePath) : null;
                break;
            default:
                $sourceImage = null;
        }

        if (!$sourceImage) {
            copy($sourcePath, $destinationPath);
            return;
        }

        imagecopyresampled($newImage, $sourceImage, 0, 0, 0, 0, $width, $height, $origWidth, $origHeight);

        switch ($imageType) {
            case IMAGETYPE_JPEG:
                imagejpeg($newImage, $destinationPath, 82);
                break;
            case IMAGETYPE_PNG:
                imagepng($newImage, $destinationPath, 6);
                break;
            case IMAGETYPE_WEBP:
                if (function_exists('imagewebp')) {
                    imagewebp($newImage, $destinationPath, 82);
                } else {
                    imagejpeg($newImage, $destinationPath, 82);
                }
                break;
            default:
                copy($sourcePath, $destinationPath);
                break;
        }

        imagedestroy($newImage);
        imagedestroy($sourceImage);
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

    // Function to serve images with caching and TTL
    public function viewImage($filename)
    {
        if (strpos($filename, '..') !== false || strpos($filename, '/') !== false || strpos($filename, '\\') !== false) {
            abort(404);
        }

        $filePath = $this->getImagePath() . $filename;

        if (!File::exists($filePath)) {
            $defaultImagePath = public_path('dist/img/default-news.png');
            if (File::exists($defaultImagePath)) {
                return response()->file($defaultImagePath, [
                    'Cache-Control' => 'public, max-age=86400',
                ]);
            }
            abort(404);
        }

        $type = File::mimeType($filePath);

        return response()->file($filePath, [
            'Content-Type'  => $type,
            'Cache-Control' => 'public, max-age=604800, must-revalidate', // 7 days TTL
        ]);
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
