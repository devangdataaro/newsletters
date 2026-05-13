<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsletterRecipient extends Model
{
    protected $fillable = [
        'newsletter_id',
        'user_id',
        'status',
        'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    public function newsletter()
    {
        return $this->belongsTo(Newsletter::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
