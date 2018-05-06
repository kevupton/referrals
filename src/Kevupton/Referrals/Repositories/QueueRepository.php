<?php namespace Kevupton\Referrals\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Kevupton\Referrals\Models\Queue;

class QueueRepository
{
    /**
     * Adds an item to queue
     *
     * @param Model $user
     * @return Queue
     */
    function addToQueue (Model $user)
    {
        try {
            /** @var Queue $first */
            $first    = Queue::query()
                ->orderBy('position', 'desc')
                ->firstOrFail();
            $position = $first->position + 1;
        } catch (ModelNotFoundException $e) {
            $position = 1;
        }

        return Queue::create([
            'user_id'  => $user->getKey(),
            'position' => $position,
        ]);
    }

    /**
     * Batch inserts empty users into the queue
     *
     * @param int $total
     * @return QueueRepository
     */
    function insertEmptyUsers ($total = 1)
    {
        try {
            /** @var Queue $first */
            $first    = Queue::query()
                ->orderBy('position', 'desc')
                ->firstOrFail();
            $position = $first->position + 1;
        } catch (ModelNotFoundException $e) {
            $position = 1;
        }

        $range = collect(range($position, $total - 1 + $position))->map(function ($position) {
            return ['position' => $position];
        });

        Queue::insert($range->toArray());

        return $this;
    }

    /**
     * Moves the item to the new position, adjusting all queued element's positions
     * in between
     *
     * @param Model $user
     * @param       $newPos
     * @return QueueRepository
     */
    public function move (Model $user, $newPos)
    {
        $queue = Queue::fromUser($user);
        return $this->moveQueue($queue, $newPos);
    }

    /**
     * Moves a user to the front of the queue
     *
     * @param Model $user
     * @param       $places
     * @return QueueRepository
     */
    public function moveUp (Model $user, $places)
    {
        $queue  = Queue::fromUser($user);
        $newPos = max($queue->position - $places, 1);
        return $this->moveQueue($queue, $newPos);
    }

    /**
     * Moves a user further down the queue
     *
     * @param Model $user
     * @param       $places
     * @return QueueRepository
     */
    public function moveDown (Model $user, $places)
    {
        $queue = Queue::fromUser($user);
        // we can assume that there is at least one queue item here.
        $min = Queue::query()
            ->orderBy('position', 'desc')
            ->first()
            ->position;

        $newPos = min($queue->position + $places, $min);
        return $this->moveQueue($queue, $newPos);
    }

    /**
     * Moves a queue item to a new position
     *
     * @param Queue $queue
     * @param       $newPos
     * @return $this
     */
    private function moveQueue (Queue $queue, $newPos)
    {
        if ($queue->position == $newPos) {
            return $this;
        }

        $oldPos = $queue->position;

        $queue->position = 0;
        $queue->save();

        if ($newPos < $oldPos) {
            Queue::query()
                ->where('position', '>=', $newPos)
                ->where('position', '<', $oldPos)
                ->orderBy('position', 'desc')
                ->update([
                    'position' => \DB::raw('`position` + 1'),
                ]);
        } else {
            Queue::query()
                ->where('position', '<=', $newPos)
                ->where('position', '>', $oldPos)
                ->orderBy('position', 'asc')
                ->update([
                    'position' => \DB::raw('`position` - 1'),
                ]);
        }

        $queue->position = $newPos;
        $queue->save();

        return $this;
    }
}