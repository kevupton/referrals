<?php

namespace Kevupton\Referrals\Jobs;

class AddMore extends Job
{
    public   $amount;
    public   $interval;

    /**
     * Create a new job instance.
     *
     * @param $amount
     * @param $interval
     */
    public function __construct($amount, $interval)
    {
        $this->amount = $amount;
        $this->interval = $interval;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->queue()->insertEmptyUsers($this->amount);

        $job = (new AddMore($this->amount, $this->interval))->delay($this->interval);

        $this->dispatch($job);
    }
}
