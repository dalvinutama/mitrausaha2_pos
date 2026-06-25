<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Barryvdh\DomPDF\Facade\Pdf;

class HeaderNotificationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $stokMenipis;
    public $hutangTempo;
    public $emailSetting;

    /**
     * Create a new message instance.
     */
    public function __construct(Collection $stokMenipis, Collection $hutangTempo)
    {
        $this->stokMenipis = $stokMenipis;
        $this->hutangTempo = $hutangTempo;
        $this->emailSetting = \App\Models\EmailSetting::getSettings();
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $total = $this->stokMenipis->count() + $this->hutangTempo->count();
        return new Envelope(
            subject: 'Peringatan Sistem: Terdapat ' . $total . ' Notifikasi Darurat',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.header_notification',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $pdf = Pdf::loadView('pdf.header_notification', [
            'stokMenipis' => $this->stokMenipis,
            'hutangTempo' => $this->hutangTempo
        ]);

        return [
            Attachment::fromData(fn () => $pdf->output(), 'Laporan_Darurat_MitraUsaha.pdf')
                    ->withMime('application/pdf'),
        ];
    }
}
