<?php

namespace App\Console\Commands;

use App\Jobs\SendNewsletterJob;
use App\Models\Newsletter;
use Illuminate\Console\Command;

class ProcessScheduledNewsletters extends Command
{
    protected $signature   = 'newsletters:process-scheduled';
    protected $description = 'Check for newsletters with "start" status and dispatch their jobs';

    public function handle(): void
    {
        // Dispatch any newsletter that has been set to "start" but not yet dispatched
        // (process_status = 'start' means the admin triggered it but jobs haven't fired yet)
        $newsletters = Newsletter::where('process_status', 'start')->get();

        foreach ($newsletters as $newsletter) {
            $shouldDispatchNow = !$newsletter->scheduled_at || $newsletter->scheduled_at->lte(now());

            if ($shouldDispatchNow) {
                $this->info("Dispatching newsletter ID {$newsletter->id}: {$newsletter->newsletter_title}");
                SendNewsletterJob::dispatch($newsletter->id, 0);
            }
        }

        $this->info('Scheduled newsletter check complete.');
    }
}
