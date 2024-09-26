<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class KirimCallback implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $rahasia;
    protected $bill_id;

     public function __construct($rahasia,$bill_id)
    {
        $this->rahasia=$rahasia;
        $this->bill_id=$bill_id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::channel('custom-flip')->info("Calback udah di test kunci : ".$this->rahasia." ".$this->bill_id);
    }
}
