<?php

namespace Tests\Feature;

use App\Http\Controllers\NotificationController;
use App\Models\MemberDetail;
use App\Models\Notification;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    public function test_admin_can_create_broadcast_and_targeted_notifications(): void
    {
        $this->withoutMiddleware(PreventRequestForgery::class);

        $response = $this->withSession(['ADMIN_LOGIN' => true])
            ->post(route('saveNotification'), [
                'type' => 'All Users',
                'title' => 'Broadcast Update',
                'message' => 'System maintenance tonight.',
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('notifications', [
            'type' => 'All Users',
            'title' => 'Broadcast Update',
        ]);

        $responseTargeted = $this->withSession(['ADMIN_LOGIN' => true])
            ->post(route('saveNotification'), [
                'type' => 'Specific Member',
                'memberid' => 'MEM1001',
                'title' => 'Personal Offer',
                'message' => 'Special bonus for you.',
            ]);

        $responseTargeted->assertRedirect();
        $this->assertDatabaseHas('notifications', [
            'type' => 'Specific Member',
            'memberid' => 'MEM1001',
            'title' => 'Personal Offer',
        ]);
    }

    public function test_all_users_notifications_are_visible_to_members(): void
    {
        $this->withoutMiddleware(PreventRequestForgery::class);

        $memberIdStr = 'MEM'.rand(10000, 99999);

        $member = MemberDetail::create([
            'memberid' => $memberIdStr,
            'name' => 'Jane Doe',
            'email' => 'jane'.rand(100, 999).'@example.com',
            'password' => bcrypt('password'),
            'file_read' => [],
        ]);

        Notification::create([
            'type' => 'All Users',
            'title' => 'Global Announcement',
            'message' => 'Every member should see this.',
        ]);

        $response = $this->withSession(['MEMBER_ID' => $memberIdStr])
            ->get('/member/notifications');

        $response->assertStatus(200);
        $response->assertSeeText('Global Announcement');

        $member->delete();
    }

    public function test_admin_rejects_invalid_specific_member_id(): void
    {
        $this->withoutMiddleware(PreventRequestForgery::class);

        $response = $this->withSession(['ADMIN_LOGIN' => true])
            ->from('/hdgteyusjasget/notification')
            ->post(route('saveNotification'), [
                'type' => 'Specific Member',
                'memberid' => 'INVALID_MEMBER_999999',
                'title' => 'Wrong User Notice',
                'message' => 'This should not be saved.',
            ]);

        $response->assertSessionHasErrors('memberid');
        $this->assertDatabaseMissing('notifications', [
            'type' => 'Specific Member',
            'memberid' => 'INVALID_MEMBER_999999',
            'title' => 'Wrong User Notice',
        ]);
    }

    public function test_member_notifications_view_composer_and_reading_flow(): void
    {
        $this->withoutMiddleware(PreventRequestForgery::class);

        $memberIdStr = 'MEM'.rand(10000, 99999);

        $member = MemberDetail::create([
            'memberid' => $memberIdStr,
            'name' => 'John Doe',
            'email' => 'john'.rand(100, 999).'@example.com',
            'password' => bcrypt('password'),
            'file_read' => [],
        ]);

        $notif1 = Notification::create([
            'type' => 'All Users',
            'title' => 'General News',
            'message' => 'Hello Everyone',
        ]);

        $notif2 = Notification::create([
            'type' => 'Specific Member',
            'memberid' => $memberIdStr,
            'title' => 'Private Alert',
            'message' => 'Hello John',
        ]);

        // AJAX single read
        $responseRead = $this->withSession(['MEMBER_ID' => $memberIdStr])
            ->postJson('/member/notifications/read', ['id' => $notif1->id]);

        $responseRead->assertStatus(200)->assertJson(['success' => true]);
        $member->refresh();
        $this->assertTrue(in_array((int) $notif1->id, array_map('intval', $member->file_read ?? []), true));

        // Show single notification detail (should mark read automatically)
        $responseDetail = $this->withSession(['MEMBER_ID' => $memberIdStr])
            ->get('/member/notification/'.$notif2->id);

        $responseDetail->assertStatus(200);
        $member->refresh();
        $this->assertTrue(in_array((int) $notif2->id, array_map('intval', $member->file_read ?? []), true));

        // AJAX read all
        $notif3 = Notification::create([
            'type' => 'All Users',
            'title' => 'Another BroadCast',
            'message' => 'Test message',
        ]);

        $responseReadAll = $this->withSession(['MEMBER_ID' => $memberIdStr])
            ->postJson('/member/notifications/read-all');

        $responseReadAll->assertStatus(200)->assertJson(['success' => true]);
        $member->refresh();
        $this->assertTrue(in_array((int) $notif3->id, array_map('intval', $member->file_read ?? []), true));

        // Cleanup test data
        $member->delete();
        $notif1->delete();
        $notif2->delete();
        $notif3->delete();
    }

    public function test_all_user_notification_variants_are_visible(): void
    {
        $memberIdStr = 'MEM'.rand(10000, 99999);
        $otherMemberId = 'MEM99999';

        $member = MemberDetail::create([
            'memberid' => $memberIdStr,
            'name' => 'Test User',
            'email' => 'test'.rand(100, 999).'@example.com',
            'password' => bcrypt('password'),
            'file_read' => [],
        ]);

        $created = [];
        $created[] = Notification::create(['type' => 'All Users', 'memberid' => null, 'title' => 'T1', 'message' => 'M1']);
        $created[] = Notification::create(['type' => 'All User', 'memberid' => null, 'title' => 'T2', 'message' => 'M2']);
        $created[] = Notification::create(['type' => 'all user', 'memberid' => '0', 'title' => 'T3', 'message' => 'M3']);
        $created[] = Notification::create(['type' => 'Broadcast', 'memberid' => '', 'title' => 'T4', 'message' => 'M4']);
        $created[] = Notification::create(['type' => 'Specific Member', 'memberid' => $memberIdStr, 'title' => 'T5', 'message' => 'M5']);
        $created[] = Notification::create(['type' => 'Specific Member', 'memberid' => $otherMemberId, 'title' => 'T6', 'message' => 'M6']);

        $visibleIds = Notification::forMember($memberIdStr)->pluck('id')->toArray();

        $this->assertContains($created[0]->id, $visibleIds);
        $this->assertContains($created[1]->id, $visibleIds);
        $this->assertContains($created[2]->id, $visibleIds);
        $this->assertContains($created[3]->id, $visibleIds);
        $this->assertContains($created[4]->id, $visibleIds); // target is this member
        $this->assertNotContains($created[5]->id, $visibleIds); // target is other member

        foreach ($created as $c) {
            $c->delete();
        }
        $member->delete();
    }

    public function test_ensure_notification_columns_exist_and_mark_all_read_contract(): void
    {
        $this->withoutMiddleware(PreventRequestForgery::class);

        NotificationController::ensureNotificationColumnsExist();

        $this->assertTrue(Schema::hasTable('notifications'));
        $this->assertTrue(Schema::hasTable('member_details'));
        $this->assertTrue(Schema::hasColumn('member_details', 'file_read'));

        $memberIdStr = 'MEM'.rand(10000, 99999);
        $member = MemberDetail::create([
            'memberid' => $memberIdStr,
            'name' => 'Test Schema',
            'email' => 'schema'.rand(100, 999).'@example.com',
            'password' => bcrypt('password'),
            'file_read' => [],
        ]);

        $notif = Notification::create([
            'type' => 'All Users',
            'title' => 'Schema Check',
            'message' => 'Schema and read all validation',
        ]);

        $response = $this->withSession(['MEMBER_ID' => $memberIdStr])
            ->postJson(route('member.notifications.readAll'));

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'unreadCount' => 0,
            ]);

        $member->refresh();
        $this->assertTrue(in_array((int) $notif->id, array_map('intval', $member->file_read ?? []), true));

        $notif->delete();
        $member->delete();
    }
}
