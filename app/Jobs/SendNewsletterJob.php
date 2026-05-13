<?php

namespace App\Jobs;

use App\Mail\NewsletterMail;
use App\Models\Newsletter;
use App\Models\NewsletterRecipient;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendNewsletterJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;
    public int $timeout = 120;

    public function __construct(
        public int $newsletterId,
        public int $userIndex
    ) {}

    public function handle(): void
    {
        $newsletter = Newsletter::find($this->newsletterId);

        if (!$newsletter || $newsletter->process_status === 'completed') {
            return;
        }

        // Get all users ordered consistently
        $users = User::orderBy('id')->get();
        $total = $users->count();

        if ($this->userIndex >= $total) {
            // All users processed — mark completed
            $newsletter->update([
                'process_status' => 'completed',
                'send_at'        => now(),
            ]);
            return;
        }

        $user = $users[$this->userIndex];

        // Update total_recipients on first job
        if ($this->userIndex === 0) {
            $newsletter->update([
                'process_status'   => 'started',
                'total_recipients' => $total,
                'current_index'    => 0,
            ]);
            // Seed pending recipient rows
            $recipients = $users->map(fn($u) => [
                'newsletter_id' => $newsletter->id,
                'user_id'       => $u->id,
                'status'        => 'pending',
                'created_at'    => now(),
                'updated_at'    => now(),
            ])->toArray();
            NewsletterRecipient::upsert($recipients, ['newsletter_id', 'user_id'], ['status', 'updated_at']);
        }

        // Send the email
        try {
            Mail::to($user->email)->send(new NewsletterMail($newsletter, $user));

            NewsletterRecipient::where('newsletter_id', $newsletter->id)
                ->where('user_id', $user->id)
                ->update(['status' => 'sent', 'sent_at' => now()]);

            $newsletter->increment('sent_count');
            $newsletter->update(['current_index' => $this->userIndex + 1]);
        } catch (\Throwable $e) {
            NewsletterRecipient::where('newsletter_id', $newsletter->id)
                ->where('user_id', $user->id)
                ->update(['status' => 'failed', 'sent_at' => now()]);
        }

        $nextIndex = $this->userIndex + 1;

        if ($nextIndex >= $total) {
            // Final user processed
            $newsletter->update([
                'process_status' => 'completed',
                'send_at'        => now(),
            ]);
        } else {
            // Dispatch next email with 4–5 minute delay
            $delayMinutes = rand(4, 5);
            SendNewsletterJob::dispatch($this->newsletterId, $nextIndex)
                ->delay(now()->addMinutes($delayMinutes));
        }
    }
}
