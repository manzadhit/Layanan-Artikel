<?php

namespace App\Notifications;

use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReportNotification extends Notification
{
    use Queueable;

    protected $report;

    /**
     * Create a new notification instance.
     */
    public function __construct(Report $report)
    {
        $this->report = $report;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('New report submitted.')
            ->line('Reportable ID: ' . $this->report->reportable_id)
            ->line('Reportable Type: ' . $this->report->reportable_type)
            ->line('Reason: ' . $this->report->reason)
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable)
    {
        return [
            'report_id' => $this->report->id,
            'user_id' => $this->report->user_id, // Add user_id here
            'reportable_id' => $this->report->reportable_id,
            'reportable_type' => $this->report->reportable_type,
            'reason' => $this->report->reason,
        ];
    }
}
