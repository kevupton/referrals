<?php

namespace Kevupton\Referrals\Jobs;

use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Kevupton\Referrals\ReferQueue;
use Kevupton\Referrals\Repositories\ReferQueueRepository;

class MoveInQueue extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * @var ReferQueue
     */
    protected $item;

    protected $new_position;

    /**
     * @var ReferQueueRepository
     */
    protected $repo;

    /**
     * Create a new job instance.
     *
     * @param ReferQueue $item
     * @param $new_position
     */
    public function __construct(ReferQueue $item, $new_position)
    {
        $this->item = $item;
        $this->new_position = $new_position;
        $this->repo = new ReferQueueRepository();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->attempts() > 2) {
            $this->release();
        } else { //move to a new position
            $this->repo->move($this->item, $this->new_position);
        }
    }
}
