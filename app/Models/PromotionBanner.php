<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromotionBanner extends Model
{
    use HasFactory;

    protected $table = 'promotion_banners';

    protected $fillable = [
        'title',
        'tag',
        'image_path',
        'external_link',
        'sort_order',
        'status',
    ];

    /**
     * Get the full URL for the banner image.
     */
    public function getImageUrlAttribute(): string
    {
        if (empty($this->image_path)) {
            return asset('uassets/mw_banners/b1.jpeg');
        }

        if (str_starts_with($this->image_path, 'http://') || str_starts_with($this->image_path, 'https://')) {
            return $this->image_path;
        }

        if (file_exists(public_path('uploads/banners/'.$this->image_path))) {
            return asset('uploads/banners/'.$this->image_path);
        }

        if (file_exists(public_path('uassets/mw_banners/'.$this->image_path))) {
            return asset('uassets/mw_banners/'.$this->image_path);
        }

        if (file_exists(public_path('uploads/'.$this->image_path))) {
            return asset('uploads/'.$this->image_path);
        }

        return asset($this->image_path);
    }
}
