<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AttendNotiMail extends Mailable
{
    use Queueable, SerializesModels;

    public $studentName;
    public $studentId;
    public $attendanceStatus;
    public $datetime;
    public $className;
    public $date;
    public $time;

    public function __construct($studentName, $studentId, $attendanceStatus, $datetime, $className, $date, $time)
    {
        $this->studentName = $studentName;
        $this->studentId = $studentId;
        $this->attendanceStatus = $attendanceStatus;
        $this->datetime = $datetime;
        $this->className = $className;
        $this->date = $date;
        $this->time = $time;
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Điểm danh thành công');
    }

    public function content(): Content
    {
        $message = "Sinh viên {$this->studentName} {$this->studentId} đã điểm danh {$this->attendanceStatus} vào lúc {$this->datetime} của lớp {$this->className} ngày {$this->date} {$this->time}. \n\n\n\nVui lòng không trả lời thư này.";

        return new Content(view: 'email', with: [
            'content' => $message,
        ]);
    }

    public function attachments(): array
    {
        return [];
    }
}
