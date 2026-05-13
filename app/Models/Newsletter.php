<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Newsletter extends Model
{
    protected $fillable = [
        'newsletter_title',
        'newsletter_content',
        'process_status',
        'send_at',
        'scheduled_at',
        'total_recipients',
        'sent_count',
        'current_index',
    ];

    protected $casts = [
        'send_at'      => 'datetime',
        'scheduled_at' => 'datetime',
    ];

    public function recipients()
    {
        return $this->hasMany(NewsletterRecipient::class);
    }

    public function sentRecipients()
    {
        return $this->hasMany(NewsletterRecipient::class)->where('status', 'sent');
    }

    public function pendingRecipients()
    {
        return $this->hasMany(NewsletterRecipient::class)->where('status', 'pending');
    }

    public function getProgressPercentageAttribute(): int
    {
        if ($this->total_recipients === 0) {
            return 0;
        }
        return (int) round(($this->sent_count / $this->total_recipients) * 100);
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->process_status) {
            'pending'   => 'badge-warning',
            'start'     => 'badge-info',
            'started'   => 'badge-primary',
            'completed' => 'badge-success',
            default     => 'badge-secondary',
        };
    }
}
