<?php

namespace Tests\Feature;

use App\Http\Controllers\AdminMediaController;
use App\Models\MemberDetail;
use App\Models\MemberVideo;
use App\Models\PromotionBanner;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminMediaManagementTest extends TestCase
{
    protected function createTestMember(): MemberDetail
    {
        $memberId = 'TEST'.rand(1000, 9999);

        return MemberDetail::create([
            'memberid' => $memberId,
            'name' => 'Media Tester',
            'mobile' => '98'.rand(10000000, 99999999),
            'email' => 'media'.rand(100, 999).'@example.com',
            'password' => bcrypt('password'),
        ]);
    }

    public function test_guest_cannot_access_admin_media_routes(): void
    {
        $this->get('/hdgteyusjasget/promotion-banners')->assertRedirect('/hdgteyusjasget');
        $this->get('/hdgteyusjasget/business-plan-pdfs')->assertRedirect('/hdgteyusjasget');
        $this->get('/hdgteyusjasget/plan-videos')->assertRedirect('/hdgteyusjasget');
        $this->get('/hdgteyusjasget/tutorial-videos')->assertRedirect('/hdgteyusjasget');
    }

    public function test_admin_can_access_media_management_pages(): void
    {
        $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->get('/hdgteyusjasget/promotion-banners');
        $response->assertStatus(200);
        $response->assertSee('Promotion Banners');

        $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->get('/hdgteyusjasget/business-plan-pdfs');
        $response->assertStatus(200);
        $response->assertSee('Business Plan PDFs');

        $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->get('/hdgteyusjasget/plan-videos');
        $response->assertStatus(200);
        $response->assertSee('Plan Videos');

        $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->get('/hdgteyusjasget/tutorial-videos');
        $response->assertStatus(200);
        $response->assertSee('Tutorial Videos');
    }

    public function test_plan_video_and_tutorial_video_isolation(): void
    {
        // Create unique plan video
        $planVideo = MemberVideo::create([
            'video_type' => 'plan',
            'title' => 'UNIQUE_PLAN_PRESENTATION_99',
            'description' => 'Plan only description',
            'video_source' => 'url',
            'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'sort_order' => 100,
            'status' => 'active',
        ]);

        // Create unique tutorial video
        $tutVideo = MemberVideo::create([
            'video_type' => 'tutorial',
            'title' => 'UNIQUE_TUTORIAL_GUIDE_99',
            'description' => 'Tutorial only description',
            'video_source' => 'url',
            'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'sort_order' => 100,
            'status' => 'active',
        ]);

        $member = $this->createTestMember();

        // Check member plan video page
        $planResponse = $this->withSession(['MEMBER_ID' => $member->memberid])
            ->get('/member/plan-video');
        $planResponse->assertStatus(200);
        $planResponse->assertSee('UNIQUE_PLAN_PRESENTATION_99');
        $planResponse->assertDontSee('UNIQUE_TUTORIAL_GUIDE_99');

        // Check member tutorial video page
        $tutResponse = $this->withSession(['MEMBER_ID' => $member->memberid])
            ->get('/member/tutorial-video');
        $tutResponse->assertStatus(200);
        $tutResponse->assertSee('UNIQUE_TUTORIAL_GUIDE_99');
        $tutResponse->assertDontSee('UNIQUE_PLAN_PRESENTATION_99');

        // Cleanup
        $planVideo->delete();
        $tutVideo->delete();
        $member->delete();
    }

    public function test_member_can_access_all_four_dynamic_sections(): void
    {
        $member = $this->createTestMember();

        $this->withSession(['MEMBER_ID' => $member->memberid])
            ->get('/member/promotion-banners')
            ->assertStatus(200)
            ->assertSee('Promotion Banners');

        $this->withSession(['MEMBER_ID' => $member->memberid])
            ->get('/member/business-plan-pdf')
            ->assertStatus(200)
            ->assertSee('Business Plan PDF');

        $this->withSession(['MEMBER_ID' => $member->memberid])
            ->get('/member/plan-video')
            ->assertStatus(200)
            ->assertSee('Plan Videos');

        $this->withSession(['MEMBER_ID' => $member->memberid])
            ->get('/member/tutorial-video')
            ->assertStatus(200)
            ->assertSee('Tutorial Videos');

        $member->delete();
    }

    public function test_admin_can_toggle_media_status(): void
    {
        $banner = PromotionBanner::first();
        $this->assertNotNull($banner);

        $initialStatus = $banner->status;
        $this->from('/hdgteyusjasget/promotion-banners')
            ->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->get('/hdgteyusjasget/promotion-banners/status/'.$banner->id)
            ->assertRedirect('/hdgteyusjasget/promotion-banners');

        $banner->refresh();
        $this->assertNotEquals($initialStatus, $banner->status);

        // Toggle back to keep original state
        $this->from('/hdgteyusjasget/promotion-banners')
            ->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->get('/hdgteyusjasget/promotion-banners/status/'.$banner->id)
            ->assertRedirect('/hdgteyusjasget/promotion-banners');

        $banner->refresh();
        $this->assertEquals($initialStatus, $banner->status);
    }

    public function test_member_video_pages_show_clean_empty_state_when_no_videos_uploaded(): void
    {
        // Ensure no videos exist
        MemberVideo::truncate();
        $this->assertEquals(0, MemberVideo::count());

        $member = $this->createTestMember();

        $planRes = $this->withSession(['MEMBER_ID' => $member->memberid])
            ->get('/member/plan-video');
        $planRes->assertStatus(200);
        $planRes->assertSee('No Plan Videos Available Yet');
        $planRes->assertDontSee('Math Wallet Official Business Plan Presentation');

        $tutRes = $this->withSession(['MEMBER_ID' => $member->memberid])
            ->get('/member/tutorial-video');
        $tutRes->assertStatus(200);
        $tutRes->assertSee('No Tutorial Videos Available Yet');
        $tutRes->assertDontSee('Math Wallet Official System Tutorial & Guide');

        // Confirm ensuring bootstrapping does not re-create dummy videos
        AdminMediaController::ensureBootstrapped();
        $this->assertEquals(0, MemberVideo::count());

        $member->delete();
    }

    public function test_admin_video_upload_supports_200mb_and_multiple_formats(): void
    {
        Storage::fake('public');

        // Test valid upload (MKV format, 50MB)
        $fakeVideo = UploadedFile::fake()->create('sample_presentation.mkv', 50000, 'video/x-matroska');

        $response = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->post('/hdgteyusjasget/plan-videos/save', [
                'title' => 'Test 200MB Plan Video',
                'video_source' => 'upload',
                'video_file' => $fakeVideo,
                'status' => 'active',
            ]);

        $response->assertSessionHas('successMsg');
        $video = MemberVideo::where('title', 'Test 200MB Plan Video')->first();
        $this->assertNotNull($video);
        $this->assertStringEndsWith('.mkv', $video->video_file);

        // Test rejected upload over 200MB (> 204800 KB)
        $oversizedVideo = UploadedFile::fake()->create('huge_video.mp4', 210000, 'video/mp4');
        $failResponse = $this->withSession(['ADMIN_LOGIN' => true, 'ADMIN_ID' => 1])
            ->post('/hdgteyusjasget/plan-videos/save', [
                'title' => 'Oversized Video',
                'video_source' => 'upload',
                'video_file' => $oversizedVideo,
                'status' => 'active',
            ]);

        $failResponse->assertSessionHasErrors(['video_file']);

        // Clean up
        $video->delete();
    }

    public function test_member_video_pages_render_correct_source_badge_pills(): void
    {
        $member = $this->createTestMember();

        $tutorialVideo = MemberVideo::create([
            'video_type' => 'tutorial',
            'title' => 'Tutorial Video Pill Test',
            'video_source' => 'upload',
            'video_file' => 'demo_guide.mp4',
            'duration' => '10:00',
            'status' => 'active',
        ]);

        $planVideo = MemberVideo::create([
            'video_type' => 'plan',
            'title' => 'Plan Video YouTube Pill Test',
            'video_source' => 'url',
            'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'duration' => '05:00',
            'status' => 'active',
        ]);

        $tutRes = $this->withSession(['MEMBER_ID' => $member->memberid])
            ->get('/member/tutorial-video');
        $tutRes->assertStatus(200);
        $tutRes->assertSee('video-source-pill');
        $tutRes->assertSee('pill-mp4');
        $tutRes->assertSee('MP4 Video');

        $planRes = $this->withSession(['MEMBER_ID' => $member->memberid])
            ->get('/member/plan-video');
        $planRes->assertStatus(200);
        $planRes->assertSee('video-source-pill');
        $planRes->assertSee('pill-youtube');
        $planRes->assertSee('YouTube');

        // Clean up
        $tutorialVideo->delete();
        $planVideo->delete();
        $member->delete();
    }
}
