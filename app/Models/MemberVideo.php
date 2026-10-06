<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberVideo extends Model
{
    use HasFactory;

    protected $table = 'member_videos';

    protected $fillable = [
        'video_type',
        'title',
        'description',
        'tag',
        'video_source',
        'video_file',
        'video_url',
        'thumbnail',
        'duration',
        'sort_order',
        'status',
    ];

    /**
     * Check if this is an external YouTube URL.
     */
    public function isYouTube(): bool
    {
        if ($this->video_source !== 'url' || empty($this->video_url)) {
            return false;
        }

        return (bool) preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/|shorts\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/i', $this->video_url);
    }

    /**
     * Extract YouTube Video ID.
     */
    public function getYouTubeId(): ?string
    {
        if (! $this->isYouTube()) {
            return null;
        }

        if (preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/|shorts\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/i', $this->video_url, $matches)) {
            return $matches[1];
        }

        return null;
    }

    /**
     * Get Embed URL for iframes.
     */
    public function getEmbedUrlAttribute(): ?string
    {
        $ytId = $this->getYouTubeId();
        if ($ytId) {
            return 'https://www.youtube.com/embed/'.$ytId.'?autoplay=0&rel=0';
        }

        if ($this->video_source === 'url' && ! empty($this->video_url)) {
            // Check for Vimeo
            if (preg_match('/vimeo\.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|video\/|)(\d+)/', $this->video_url, $matches)) {
                return 'https://player.vimeo.com/video/'.$matches[3];
            }

            return $this->video_url;
        }

        return null;
    }

    /**
     * Get Playable Video URL (for HTML5 video player or iframe).
     */
    public function getVideoPlayUrlAttribute(): string
    {
        if ($this->video_source === 'url' && ! empty($this->video_url)) {
            return $this->video_url;
        }

        if (! empty($this->video_file)) {
            if (str_starts_with($this->video_file, 'http://') || str_starts_with($this->video_file, 'https://')) {
                return $this->video_file;
            }

            if (file_exists(public_path('uploads/videos/'.$this->video_file))) {
                return asset('uploads/videos/'.$this->video_file);
            }

            if (file_exists(public_path('uploads/'.$this->video_file))) {
                return asset('uploads/'.$this->video_file);
            }

            if ($this->video_type === 'plan' && file_exists(public_path('uassets/mw_plan_video/'.$this->video_file))) {
                return asset('uassets/mw_plan_video/'.$this->video_file);
            }

            if ($this->video_type === 'tutorial' && file_exists(public_path('uassets/mw_Tutorial_video/'.$this->video_file))) {
                return asset('uassets/mw_Tutorial_video/'.$this->video_file);
            }

            return asset($this->video_file);
        }

        return '';
    }

    /**
     * Get Thumbnail URL.
     */
    public function getThumbnailUrlAttribute(): string
    {
        if (! empty($this->thumbnail)) {
            if (str_starts_with($this->thumbnail, 'http://') || str_starts_with($this->thumbnail, 'https://')) {
                return $this->thumbnail;
            }

            if (file_exists(public_path('uploads/thumbnails/'.$this->thumbnail))) {
                return asset('uploads/thumbnails/'.$this->thumbnail);
            }

            if (file_exists(public_path('uploads/'.$this->thumbnail))) {
                return asset('uploads/'.$this->thumbnail);
            }

            return asset($this->thumbnail);
        }

        // Auto fallback to YouTube thumbnail if YouTube
        $ytId = $this->getYouTubeId();
        if ($ytId) {
            return 'https://img.youtube.com/vi/'.$ytId.'/hqdefault.jpg';
        }

        // Fallback default poster
        return asset('uassets/images/default-video-thumbnail.png');
    }

    /**
     * Get Disk Path for downloads if uploaded file.
     */
    public function getDiskPathAttribute(): ?string
    {
        if (empty($this->video_file)) {
            return null;
        }

        $candidates = [
            public_path('uploads/videos/'.$this->video_file),
            public_path('uploads/'.$this->video_file),
            public_path('uassets/mw_plan_video/'.$this->video_file),
            public_path('uassets/mw_Tutorial_video/'.$this->video_file),
            public_path($this->video_file),
        ];

        foreach ($candidates as $path) {
            if (file_exists($path)) {
                return $path;
            }
        }

        return null;
    }

    /**
     * Check if this video can be downloaded.
     */
    public function canDownload(): bool
    {
        return ! empty($this->disk_path);
    }
}
