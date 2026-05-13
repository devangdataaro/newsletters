<?php

namespace App\Console\Commands;

use App\Jobs\SendNewsletterJob;
use App\Models\Newsletter;
use Illuminate\Console\Command;

class ProcessScheduledNewsletters extends Command
{
    protected $signature   = 'newsletters:process-scheduled';
    protected $description = 'Auto-start pending newsletters whose scheduled_at time has arrived';

    public function handle(): void
    {
        // Only touch PENDING newsletters whose scheduled_at has now passed.
        // Newsletters already in "start"/"started" state were manually triggered by the
        // admin and already have jobs queued — re-dispatching them would duplicate emails.
        $newsletters = Newsletter::where('process_status', 'pending')
            ->whereNotNull('scheduled_at')
            ->where('scheduled_at', '<=', now())
            ->get();

        foreach ($newsletters as $newsletter) {
            // Atomic update: only one scheduler process can claim a newsletter.
            $updated = Newsletter::where('id', $newsletter->id)
                ->where('process_status', 'pending')
                ->update(['process_status' => 'start']);

            if ($updated) {
                $this->info("Auto-starting newsletter ID {$newsletter->id}: {$newsletter->newsletter_title}");
                SendNewsletterJob::dispatch($newsletter->id, 0);
            }
        }

        $this->info('Scheduled newsletter check complete.');
    }
}
