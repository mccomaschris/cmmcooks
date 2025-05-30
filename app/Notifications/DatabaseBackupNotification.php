<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class DatabaseBackupNotification extends Notification
{
    use Queueable;

    protected $filename;
    protected $storageUrl;

    public function __construct($filename, $storageUrl)
    {
        $this->filename = $filename;
        $this->storageUrl = $storageUrl;
    }

    public function via($notifiable)
    {
        return ['mail']; // Send via email
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Database Backup Successful')
            ->greeting('Hello,')
            ->line('Your database backup was successfully created and uploaded.')
            ->line('Backup File: ' . $this->filename)
            ->action('View Backup', $this->storageUrl)
            ->line('Thank you for using our backup system!');
    }
}
