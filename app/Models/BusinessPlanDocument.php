<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessPlanDocument extends Model
{
    use HasFactory;

    protected $table = 'business_plan_documents';

    protected $fillable = [
        'title',
        'language',
        'native_language',
        'flag',
        'flag_code',
        'badge',
        'badge_color',
        'gradient',
        'border_color',
        'icon_color',
        'file_path',
        'file_size',
        'pages_hint',
        'description',
        'sort_order',
        'status',
    ];

    /**
     * Get the full URL for viewing/downloading the PDF.
     */
    public function getFileUrlAttribute(): string
    {
        if (empty($this->file_path)) {
            return '';
        }

        if (str_starts_with($this->file_path, 'http://') || str_starts_with($this->file_path, 'https://')) {
            return $this->file_path;
        }

        if (file_exists(public_path('uploads/pdfs/'.$this->file_path))) {
            return asset('uploads/pdfs/'.$this->file_path);
        }

        if (file_exists(public_path('uassets/mw_pdf/'.$this->file_path))) {
            return asset('uassets/mw_pdf/'.rawurlencode($this->file_path));
        }

        if (file_exists(public_path('uploads/'.$this->file_path))) {
            return asset('uploads/'.$this->file_path);
        }

        return asset($this->file_path);
    }

    /**
     * Get absolute path on disk for downloads.
     */
    public function getDiskPathAttribute(): ?string
    {
        if (empty($this->file_path)) {
            return null;
        }

        $candidates = [
            public_path('uploads/pdfs/'.$this->file_path),
            public_path('uassets/mw_pdf/'.$this->file_path),
            public_path('uploads/'.$this->file_path),
            public_path($this->file_path),
        ];

        foreach ($candidates as $path) {
            if (file_exists($path)) {
                return $path;
            }
        }

        return null;
    }
}
