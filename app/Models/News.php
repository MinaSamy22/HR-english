<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    protected $table = 'news';

    protected $fillable = [
        'title',
        'description',
        'image',
        'news_date',
        'company_id'
    ];

    protected $casts = [
        'news_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationship with Company
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    // Static method to retrieve records with filtering options
    static public function getRecord()
    {
        $query = self::select('news.*')
                    ->with('company'); // Include company relationship

        // Retrieve records ordered by news_date (most recent first) and paginated
        return $query->orderBy('news_date', 'desc')
                    ->orderBy('id', 'desc')
                    ->paginate(10);
    }

    // Method to handle images from shared folder
   public function getImageUrlAttribute()
{
    if ($this->image) {
        $filePath = public_path('../../HR-Uploads/newsimages/' . $this->image);
        if (file_exists($filePath)) {
            // Check current route to determine which interface
            $currentRoute = request()->route()->getName();

            if (str_contains($currentRoute, 'employee') || request()->is('employee/*')) {
                return route('employee.news.image', $this->image);
            } else {
                return route('view.news.image', $this->image);
            }
        }
    }
    return asset('dist/img/default-news.png');
}

    public function getImagePathAttribute()
    {
        return public_path('../../HR-Uploads/newsimages/' . $this->image);
    }

    // Method to check if image is SVG
    public function getImageIsSvgAttribute()
    {
        if ($this->image) {
            $extension = pathinfo($this->image, PATHINFO_EXTENSION);
            return strtolower($extension) === 'svg';
        }
        return false;
    }

    // Method to get image MIME type
    public function getImageMimeTypeAttribute()
    {
        if ($this->image) {
            $extension = strtolower(pathinfo($this->image, PATHINFO_EXTENSION));
            switch ($extension) {
                case 'svg':
                    return 'image/svg+xml';
                case 'png':
                    return 'image/png';
                case 'jpg':
                case 'jpeg':
                    return 'image/jpeg';
                case 'gif':
                    return 'image/gif';
                case 'webp':
                    return 'image/webp';
                default:
                    return 'image/png';
            }
        }
        return 'image/png';
    }

    // Enhanced method to check if news has image
    public function hasImage()
    {
        return !empty($this->image) && file_exists($this->getImagePathAttribute());
    }

    // Method to get formatted news date
    public function getFormattedDateAttribute()
    {
        return $this->news_date ? $this->news_date->format('M d, Y') : null;
    }

    // Method to get excerpt from description
    public function getExcerptAttribute($length = 150)
    {
        if (!$this->description) {
            return null;
        }

        return strlen($this->description) > $length
            ? substr($this->description, 0, $length) . '...'
            : $this->description;
    }

    // Method to check if news is recent (within last 7 days)
    public function isRecent()
    {
        if (!$this->news_date) {
            return false;
        }

        return $this->news_date->diffInDays(now()) <= 7;
    }

    // Static method to get recent news
    static public function getRecentNews($limit = 5)
    {
        return self::with('company')
                  ->whereDate('news_date', '>=', now()->subDays(30))
                  ->orderBy('news_date', 'desc')
                  ->limit($limit)
                  ->get();
    }

    // Static method to get news by company
    static public function getByCompany($companyId, $limit = 10)
    {
        return self::where('company_id', $companyId)
                  ->orderBy('news_date', 'desc')
                  ->limit($limit)
                  ->get();
    }
}
