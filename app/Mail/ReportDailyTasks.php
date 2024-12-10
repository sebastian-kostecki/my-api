<?php

namespace App\Mail;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReportDailyTasks extends Mailable
{
    use Queueable, SerializesModels;

    protected array $dailyTasks;

    protected array $nextTasks;

    /**
     * Create a new message instance.
     */
    public function __construct(array $dailyTasks, array $nextTasks)
    {
        $this->dailyTasks = $dailyTasks;
        $this->nextTasks = $nextTasks;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Raport: '.Carbon::now()->format('Y-m-d'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.report-daily-tasks',
            with: [
                'dailyTasks' => $this->dailyTasks,
                'nextTasks' => $this->nextTasks,
                'date' => Carbon::now()->format('d-m-Y'),
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
