<?php

namespace App\Jobs;

use App\Models\Dish\Dish;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AddSnackTimesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public Dish $dish, public array $timeIds)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->dish->times()->syncWithoutDetaching($this->timeIds);
    }
}
