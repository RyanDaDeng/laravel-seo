<?php

namespace App\Events;

use App\Modules\JobHistory\Models\JobHistory;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class ProcessSummaryJobCompleted implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $jobHistory;
    public $message;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(JobHistory $jobHistory)
    {
        //
        $this->jobHistory = $jobHistory;
        $this->message = "Job #$jobHistory->id is completed (Result: $jobHistory->status_name, Date From: $jobHistory->date_from, Date To: $jobHistory->date_to)";
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('job-completed-channel');
    }

    public function broadcastAs()
    {
        return 'job-completed-event';
    }
}
