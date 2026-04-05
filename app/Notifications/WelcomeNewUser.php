<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;

class WelcomeNewUser extends Notification implements ShouldQueue
{
    use Queueable;

    public User $user;
    public string $message;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $user, string $customMessage = null)
    {
        $this->user = $user;
        $this->message = $customMessage ?? 'Welcome to our platform!';
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Welcome Aboard')
            ->greeting('Hi ' . $this->user->name . ',')
            ->line($this->message)
            ->line('Explore our features and get started right away.')
            ->action('Visit Dashboard', url('/dashboard'))
            ->line('Happy to have you on board!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'user_id' => $this->user->id,
            'message' => $this->message,
            'type' => 'welcome',
        ];
    }
}
