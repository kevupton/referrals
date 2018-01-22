<?php

namespace Kevupton\Referrals\Jobs;

use App\Traits\HasMemory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Kevupton\Referrals\Repositories\QueueRepository;

abstract class Job implements ShouldQueue
{
    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels,
        HasMemory;

    /**
     * @return QueueRepository
     */
    public function queue ()
    {
        return $this->memory('queue', function () {
            return new QueueRepository();
        });
    }
}
