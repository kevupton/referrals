<?php

namespace Kevupton\Referrals\Jobs;

use App\Jobs\Job;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Kevupton\Referrals\ReferQueue;
use Kevupton\Referrals\Repositories\ReferQueueRepository;

class AddMore extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels, DispatchesJobs;

    /**
     * @var ReferQueueRepository
     */
    protected $repo;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->repo = new ReferQueueRepository();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $interval = ref_conf('addmore.interval', 3600);
        $amount = ref_conf('addmore.amount', 1);

        for ($i = 0; $i < $amount; $i++) {
            $this->repo->addToQueue();
        }

        $job = (new AddMore())->delay($interval);

        $this->dispatch($job);
    }
}
