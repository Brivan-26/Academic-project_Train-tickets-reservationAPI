<?php

namespace App\Jobs;

use App\Models\Travel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RenderTravelCompleted implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $travel;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Travel $travel)
    {
        $this->travel = $travel;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->travel->status = "completed";
        $this->travel->save();
    }
}
