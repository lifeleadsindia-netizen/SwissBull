<?php

namespace App\Http\Controllers;

use App\Models\BusinessPlanDocument;
use App\Models\MemberVideo;
use App\Models\PromotionBanner;
use Database\Seeders\MediaManagementSeeder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class AdminMediaController extends Controller
{
    /**
     * Bootstrap default media assets if tables are empty.
     */
    public static function ensureBootstrapped(): void
    {
        if (PromotionBanner::count() === 0 || BusinessPlanDocument::count() === 0) {
            $seeder = new MediaManagementSeeder;
            $seeder->run();
        }
    }

    // ==========================================
    // 1. PROMOTION BANNERS MANAGEMENT
    // ==========================================

    public function banners()
    {
        self::ensureBootstrapped();
        $banners = PromotionBanner::orderBy('sort_order', 'asc')->orderBy('id', 'desc')->get();

        return view('admin.media.banners', compact('banners'));
    }

    public function saveBanner(Request $request)
    {
        $rules = [
            'title' => 'required|string|max:255',
            'tag' => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer',
            'status' => 'required|in:active,inactive',
        ];

        if ($request->filled('id')) {
            $rules['image'] = 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:10240';
            $banner = PromotionBanner::findOrFail($request->input('id'));
        } else {
            $rules['image'] = 'required|image|mimes:jpeg,png,jpg,webp,svg|max:10240';
            $banner = new PromotionBanner;
        }

        $request->validate($rules);

        $banner->title = $request->input('title');
        $banner->tag = $request->input('tag');
        $banner->sort_order = $request->input('sort_order', 0) ?? 0;
        $banner->status = $request->input('status');
        $banner->external_link = $request->input('external_link');

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = 'banner_'.time().'_'.uniqid().'.'.$file->getClientOriginalExtension();
            $destPath = public_path('uploads/banners');
            if (! File::isDirectory($destPath)) {
                File::makeDirectory($destPath, 0755, true, true);
            }
            $file->move($destPath, $filename);
            $banner->image_path = $filename;
        }

        $banner->save();

        session()->flash('successMsg', 'Promotion Banner saved successfully.');

        return redirect()->back();
    }

    public function toggleBannerStatus($id)
    {
        $banner = PromotionBanner::findOrFail($id);
        $banner->status = ($banner->status === 'active') ? 'inactive' : 'active';
        $banner->save();

        session()->flash('successMsg', 'Banner status updated to '.ucfirst($banner->status).'.');

        return redirect()->back();
    }

    public function deleteBanner($id)
    {
        $banner = PromotionBanner::findOrFail($id);
        if ($banner->image_path && file_exists(public_path('uploads/banners/'.$banner->image_path))) {
            @unlink(public_path('uploads/banners/'.$banner->image_path));
        }
        $banner->delete();

        session()->flash('delMsg', 'Promotion Banner deleted successfully.');

        return redirect()->back();
    }

    // ==========================================
    // 2. BUSINESS PLAN PDF MANAGEMENT
    // ==========================================

    public function pdfs()
    {
        self::ensureBootstrapped();
        $pdfs = BusinessPlanDocument::orderBy('sort_order', 'asc')->orderBy('id', 'desc')->get();

        return view('admin.media.pdfs', compact('pdfs'));
    }

    public function savePdf(Request $request)
    {
        $rules = [
            'title' => 'required|string|max:255',
            'language' => 'required|string|max:100',
            'native_language' => 'nullable|string|max:100',
            'flag' => 'nullable|string|max:20',
            'badge' => 'nullable|string|max:100',
            'badge_color' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer',
            'status' => 'required|in:active,inactive',
        ];

        if ($request->filled('id')) {
            $rules['pdf_file'] = 'nullable|file|mimes:pdf|max:51200';
            $pdf = BusinessPlanDocument::findOrFail($request->input('id'));
        } else {
            $rules['pdf_file'] = 'required|file|mimes:pdf|max:51200';
            $pdf = new BusinessPlanDocument;
        }

        $request->validate($rules);

        $pdf->title = $request->input('title');
        $pdf->language = $request->input('language');
        $pdf->native_language = $request->input('native_language') ?: $request->input('language');
        $pdf->flag = $request->input('flag') ?: '📄';
        $pdf->badge = $request->input('badge') ?: 'Official Edition';
        $pdf->badge_color = $request->input('badge_color') ?: '#3b82f6';
        $pdf->description = $request->input('description');
        $pdf->sort_order = $request->input('sort_order', 0) ?? 0;
        $pdf->status = $request->input('status');

        if ($request->hasFile('pdf_file')) {
            $file = $request->file('pdf_file');
            $sizeInBytes = $file->getSize();
            $fileSizeFormatted = round($sizeInBytes / (1024 * 1024), 2).' MB';

            $filename = 'plan_'.time().'_'.uniqid().'.'.$file->getClientOriginalExtension();
            $destPath = public_path('uploads/pdfs');
            if (! File::isDirectory($destPath)) {
                File::makeDirectory($destPath, 0755, true, true);
            }
            $file->move($destPath, $filename);

            $pdf->file_path = $filename;
            $pdf->file_size = $fileSizeFormatted;
        }

        $pdf->save();

        session()->flash('successMsg', 'Business Plan PDF saved successfully.');

        return redirect()->back();
    }

    public function togglePdfStatus($id)
    {
        $pdf = BusinessPlanDocument::findOrFail($id);
        $pdf->status = ($pdf->status === 'active') ? 'inactive' : 'active';
        $pdf->save();

        session()->flash('successMsg', 'PDF status updated to '.ucfirst($pdf->status).'.');

        return redirect()->back();
    }

    public function deletePdf($id)
    {
        $pdf = BusinessPlanDocument::findOrFail($id);
        if ($pdf->file_path && file_exists(public_path('uploads/pdfs/'.$pdf->file_path))) {
            @unlink(public_path('uploads/pdfs/'.$pdf->file_path));
        }
        $pdf->delete();

        session()->flash('delMsg', 'Business Plan PDF deleted successfully.');

        return redirect()->back();
    }

    // ==========================================
    // 3. PLAN VIDEOS MANAGEMENT
    // ==========================================

    public function planVideos(Request $request)
    {
        self::ensureBootstrapped();
        $query = MemberVideo::where('video_type', 'plan');

        if ($request->filled('search')) {
            $term = trim($request->input('search'));
            $query->where(function ($q) use ($term) {
                $q->where('title', 'like', '%'.$term.'%')
                    ->orWhere('tag', 'like', '%'.$term.'%')
                    ->orWhere('description', 'like', '%'.$term.'%');
            });
        }

        $videos = $query->orderBy('sort_order', 'asc')->orderBy('id', 'desc')->paginate(15)->withQueryString();

        return view('admin.media.plan-videos', compact('videos'));
    }

    public function savePlanVideo(Request $request)
    {
        return $this->saveVideoInternal($request, 'plan');
    }

    // ==========================================
    // 4. TUTORIAL VIDEOS MANAGEMENT
    // ==========================================

    public function tutorialVideos(Request $request)
    {
        self::ensureBootstrapped();
        $query = MemberVideo::where('video_type', 'tutorial');

        if ($request->filled('search')) {
            $term = trim($request->input('search'));
            $query->where(function ($q) use ($term) {
                $q->where('title', 'like', '%'.$term.'%')
                    ->orWhere('tag', 'like', '%'.$term.'%')
                    ->orWhere('description', 'like', '%'.$term.'%');
            });
        }

        $videos = $query->orderBy('sort_order', 'asc')->orderBy('id', 'desc')->paginate(15)->withQueryString();

        return view('admin.media.tutorial-videos', compact('videos'));
    }

    public function saveTutorialVideo(Request $request)
    {
        return $this->saveVideoInternal($request, 'tutorial');
    }

    /**
     * Shared logic for saving video (Plan or Tutorial).
     */
    protected function saveVideoInternal(Request $request, string $videoType)
    {
        @ini_set('memory_limit', '512M');
        @ini_set('max_execution_time', '600');
        if (function_exists('set_time_limit')) {
            @set_time_limit(600);
        }

        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'tag' => 'nullable|string|max:100',
            'video_source' => 'required|in:upload,url',
            'duration' => 'nullable|string|max:50',
            'sort_order' => 'nullable|integer',
            'status' => 'required|in:active,inactive',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ];

        if ($request->input('video_source') === 'url') {
            $rules['video_url'] = 'required|url|max:500';
        } else {
            $videoValidation = 'file|max:204800';
            if (! $request->filled('id')) {
                $rules['video_file'] = 'required|'.$videoValidation;
            } else {
                $rules['video_file'] = 'nullable|'.$videoValidation;
            }
        }

        $messages = [
            'video_file.max' => 'The video file size must not exceed 200MB.',
            'video_file.required' => 'Please select a video file to upload.',
        ];

        $request->validate($rules, $messages);

        if ($request->hasFile('video_file')) {
            $allowedExtensions = ['mp4', 'mov', 'avi', 'webm', 'mkv', 'flv', 'wmv', '3gp', '3g2', 'm4v', 'mpg', 'mpeg', 'ogv', 'ts', 'mts', 'vob'];
            $ext = strtolower($request->file('video_file')->getClientOriginalExtension());
            if (! in_array($ext, $allowedExtensions)) {
                return back()->withInput()->withErrors([
                    'video_file' => 'The video file format (.'.$ext.') is not supported. Please upload a valid video file (MP4, MKV, MOV, WEBM, AVI, 3GP, etc.).',
                ]);
            }
        }

        if ($request->filled('id')) {
            $video = MemberVideo::where('video_type', $videoType)->findOrFail($request->input('id'));
        } else {
            $video = new MemberVideo;
            $video->video_type = $videoType;
        }

        $video->title = $request->input('title');
        $video->description = $request->input('description');
        $video->tag = $request->input('tag') ?: ($videoType === 'plan' ? 'Official Presentation' : 'Step-by-Step Guide');
        $video->video_source = $request->input('video_source');
        $video->duration = $request->input('duration') ?: 'Full Video';
        $video->sort_order = $request->input('sort_order', 0) ?? 0;
        $video->status = $request->input('status');

        if ($request->input('video_source') === 'url') {
            $video->video_url = $request->input('video_url');
        } else {
            if ($request->hasFile('video_file')) {
                $vFile = $request->file('video_file');
                $filename = 'video_'.time().'_'.uniqid().'.'.$vFile->getClientOriginalExtension();
                $destPath = public_path('uploads/videos');
                if (! File::isDirectory($destPath)) {
                    File::makeDirectory($destPath, 0755, true, true);
                }
                $vFile->move($destPath, $filename);
                $video->video_file = $filename;
            }
        }

        if ($request->hasFile('thumbnail')) {
            $tFile = $request->file('thumbnail');
            $thumbName = 'thumb_'.time().'_'.uniqid().'.'.$tFile->getClientOriginalExtension();
            $destThumbPath = public_path('uploads/thumbnails');
            if (! File::isDirectory($destThumbPath)) {
                File::makeDirectory($destThumbPath, 0755, true, true);
            }
            $tFile->move($destThumbPath, $thumbName);
            $video->thumbnail = $thumbName;
        }

        $video->save();

        $typeName = ($videoType === 'plan') ? 'Plan Video' : 'Tutorial Video';
        session()->flash('successMsg', $typeName.' saved successfully.');

        return redirect()->back();
    }

    public function toggleVideoStatus($id)
    {
        $video = MemberVideo::findOrFail($id);
        $video->status = ($video->status === 'active') ? 'inactive' : 'active';
        $video->save();

        session()->flash('successMsg', 'Video status updated to '.ucfirst($video->status).'.');

        return redirect()->back();
    }

    public function deleteVideo($id)
    {
        $video = MemberVideo::findOrFail($id);
        if ($video->video_file && file_exists(public_path('uploads/videos/'.$video->video_file))) {
            @unlink(public_path('uploads/videos/'.$video->video_file));
        }
        if ($video->thumbnail && file_exists(public_path('uploads/thumbnails/'.$video->thumbnail))) {
            @unlink(public_path('uploads/thumbnails/'.$video->thumbnail));
        }
        $video->delete();

        session()->flash('delMsg', 'Video deleted successfully.');

        return redirect()->back();
    }
}
