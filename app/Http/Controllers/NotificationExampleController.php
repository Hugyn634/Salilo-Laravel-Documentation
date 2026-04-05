<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\UserRegistered;
use App\Notifications\WelcomeNewUser;

class NotificationExampleController extends Controller
{
    /**
     * Example: Send notification when user registers
     * Use this in a registration controller or event listener
     */
    public function exampleUserRegistration()
    {
        $user = User::factory()->create();

        // Send via mail and database channels
        $user->notify(new UserRegistered($user));

        return response()->json(['message' => 'Registration notification sent']);
    }

    /**
     * Example: Send welcome notification with custom message
     */
    public function exampleWelcomeNotification()
    {
        $user = User::find(1);

        if ($user) {
            $user->notify(new WelcomeNewUser(
                $user,
                'Welcome! Enjoy exploring our amazing features.'
            ));

            return response()->json(['message' => 'Welcome notification sent']);
        }

        return response()->json(['error' => 'User not found'], 404);
    }

    /**
     * Example: Get user's notifications from database
     */
    public function getUserNotifications()
    {
        $user = auth()->user() ?? User::find(1);

        return response()->json([
            'unread' => $user->unreadNotifications,
            'all' => $user->notifications,
        ]);
    }

    /**
     * Example: Mark notification as read
     */
    public function markNotificationAsRead($notificationId)
    {
        $user = auth()->user() ?? User::find(1);
        $notification = $user->notifications()->find($notificationId);

        if ($notification) {
            $notification->markAsRead();
            return response()->json(['message' => 'Notification marked as read']);
        }

        return response()->json(['error' => 'Notification not found'], 404);
    }

    /**
     * Example: In your routes/api.php or routes/web.php
     *
     * Route::post('/notifications/register', [NotificationExampleController::class, 'exampleUserRegistration']);
     * Route::post('/notifications/welcome', [NotificationExampleController::class, 'exampleWelcomeNotification']);
     * Route::get('/notifications', [NotificationExampleController::class, 'getUserNotifications']);
     * Route::post('/notifications/{id}/read', [NotificationExampleController::class, 'markNotificationAsRead']);
     */
}
