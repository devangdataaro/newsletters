<?php

namespace App\Http\Controllers;

use App\Jobs\SendNewsletterJob;
use App\Models\Newsletter;
use App\Models\User;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function index()
    {
        $newsletters = Newsletter::latest()->paginate(10);
        $totalUsers  = User::count();
        return view('newsletters.index', compact('newsletters', 'totalUsers'));
    }

    public function create()
    {
        return view('newsletters.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'newsletter_title'   => 'required|string|max:255',
            'newsletter_content' => 'required|string',
            'scheduled_at'       => 'nullable|date',
        ]);

        Newsletter::create([
            'newsletter_title'   => $validated['newsletter_title'],
            'newsletter_content' => $validated['newsletter_content'],
            'scheduled_at'       => $validated['scheduled_at'] ?? null,
            'process_status'     => 'pending',
        ]);

        return redirect()->route('newsletters.index')
            ->with('success', 'Newsletter created successfully!');
    }

    public function show(Newsletter $newsletter)
    {
        $newsletter->load('recipients.user');
        $recipients = $newsletter->recipients()->with('user')->paginate(15);
        return view('newsletters.show', compact('newsletter', 'recipients'));
    }

    public function edit(Newsletter $newsletter)
    {
        if (in_array($newsletter->process_status, ['started', 'completed'])) {
            return redirect()->route('newsletters.index')
                ->with('error', 'Cannot edit a newsletter that is already running or completed.');
        }
        return view('newsletters.edit', compact('newsletter'));
    }

    public function update(Request $request, Newsletter $newsletter)
    {
        if (in_array($newsletter->process_status, ['started', 'completed'])) {
            return redirect()->route('newsletters.index')
                ->with('error', 'Cannot update a newsletter that is already running or completed.');
        }

        $validated = $request->validate([
            'newsletter_title'   => 'required|string|max:255',
            'newsletter_content' => 'required|string',
            'scheduled_at'       => 'nullable|date',
        ]);

        // Handle status change to "start" — atomic to prevent double-dispatch
        if ($request->input('process_status') === 'start' && $newsletter->process_status === 'pending') {
            $updated = Newsletter::where('id', $newsletter->id)
                ->where('process_status', 'pending')
                ->update([
                    'newsletter_title'   => $validated['newsletter_title'],
                    'newsletter_content' => $validated['newsletter_content'],
                    'scheduled_at'       => $validated['scheduled_at'] ?? $newsletter->scheduled_at,
                    'process_status'     => 'start',
                ]);

            if ($updated) {
                $newsletter->refresh();
                $this->dispatchNewsletter($newsletter);
            }

            return redirect()->route('newsletters.index')
                ->with('success', 'Newsletter is now being dispatched to all users!');
        }

        $newsletter->update([
            'newsletter_title'   => $validated['newsletter_title'],
            'newsletter_content' => $validated['newsletter_content'],
            'scheduled_at'       => $validated['scheduled_at'] ?? null,
        ]);

        return redirect()->route('newsletters.index')
            ->with('success', 'Newsletter updated successfully!');
    }

    public function destroy(Newsletter $newsletter)
    {
        if ($newsletter->process_status === 'started') {
            return redirect()->route('newsletters.index')
                ->with('error', 'Cannot delete a newsletter that is currently sending.');
        }
        $newsletter->delete();
        return redirect()->route('newsletters.index')
            ->with('success', 'Newsletter deleted successfully!');
    }

    public function updateStatus(Request $request, Newsletter $newsletter)
    {
        $request->validate([
            'process_status' => 'required|in:start',
        ]);

        // Atomic update: only succeeds if the newsletter is still "pending".
        // Prevents double-dispatch if the button is clicked twice quickly.
        $updated = Newsletter::where('id', $newsletter->id)
            ->where('process_status', 'pending')
            ->update(['process_status' => 'start']);

        if (! $updated) {
            return back()->with('error', 'Only pending newsletters can be started.');
        }

        $newsletter->refresh();
        $this->dispatchNewsletter($newsletter);

        return back()->with('success', 'Newsletter sending has been initiated!');
    }

    private function dispatchNewsletter(Newsletter $newsletter): void
    {
        $delay = $newsletter->scheduled_at && $newsletter->scheduled_at->isFuture()
            ? $newsletter->scheduled_at
            : now();

        SendNewsletterJob::dispatch($newsletter->id, 0)->delay($delay);
    }

    /**
     * Return live progress data as JSON for AJAX polling.
     */
    public function progress(Newsletter $newsletter)
    {
        $newsletter->refresh();
        return response()->json([
            'process_status'   => $newsletter->process_status,
            'total_recipients' => $newsletter->total_recipients,
            'sent_count'       => $newsletter->sent_count,
            'progress_pct'     => $newsletter->progress_percentage,
            'pending_count'    => max(0, $newsletter->total_recipients - $newsletter->sent_count),
        ]);
    }
}
