<?php

namespace Tests\Feature;

use App\Models\User;
use App\Notifications\UserRegistered;
use App\Notifications\WelcomeNewUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_registered_notification_is_sent()
    {
        Notification::fake();

        $user = User::factory()->create();

        $user->notify(new UserRegistered($user));

        Notification::assertSentTo($user, UserRegistered::class, function ($notification) use ($user) {
            return $notification->user->id === $user->id;
        });
    }

    public function test_welcome_new_user_notification_is_sent()
    {
        Notification::fake();

        $user = User::factory()->create();
        $message = 'Welcome to our awesome platform!';

        $user->notify(new WelcomeNewUser($user, $message));

        Notification::assertSentTo($user, WelcomeNewUser::class, function ($notification) use ($message) {
            return $notification->message === $message;
        });
    }

    public function test_notification_can_be_stored_in_database()
    {
        $user = User::factory()->create();

        $user->notify(new UserRegistered($user));

        $this->assertCount(1, $user->notifications);
        $this->assertEquals('registration', $user->notifications[0]['data']['type']);
    }

    public function test_notification_can_be_marked_as_read()
    {
        $user = User::factory()->create();

        $user->notify(new WelcomeNewUser($user));

        $notification = $user->notifications->first();

        $this->assertNull($notification->read_at);

        $notification->markAsRead();

        $this->assertNotNull($notification->read_at);
    }
}
