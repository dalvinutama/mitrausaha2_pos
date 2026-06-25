<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Database\Eloquent\Collection;

class DailyPODigestMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $pos;
    public $emailSetting;

    /**
     * Create a new message instance.
     */
    public function __construct(Collection $pos)
    {
        $this->pos = $pos;
        $this->emailSetting = \App\Models\EmailSetting::getSettings();
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Laporan Rekap: ' . $this->pos->count() . ' Purchase Order Menunggu Persetujuan',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.daily_po_digest',
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
