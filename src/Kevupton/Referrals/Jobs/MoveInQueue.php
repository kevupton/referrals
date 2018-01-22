<?php

namespace Kevupton\Referrals\Jobs;

use Kevupton\Referrals\Models\Queue;

class MoveInQueue extends Job
{
    /**
     * @var Queue
     */
    public $item;
    public $newPosition;

    /**
     * Create a new job instance.
     *
     * @param Queue $item
     * @param $newPosition
     */
    public function __construct(Queue $item, $newPosition)
    {
        $this->item        = $item;
        $this->newPosition = $newPosition;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->attempts() > 3) {
            $this->release();
        }
        $this->queue()->move($this->item->getUser(), $this->newPosition);
    }
}
