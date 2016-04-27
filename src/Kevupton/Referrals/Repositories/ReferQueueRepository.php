<?php namespace Kevupton\Referrals\Repositories;

use Kevupton\Ethereal\Repositories\Repository;
use Kevupton\Referrals\Exceptions\ReferralException;
use Kevupton\Referrals\ReferQueue;

class ReferQueueRepository extends Repository {
    const TICKETING_HOLDING_TIME = 15;

    protected $exceptions = [
        'main' => ReferralException::class,
    ];

    /**
     * Retrieves the class instance of the specified repository.
     *
     * @return string the string instance of the defining class
     */
    function getClass()
    {
        return ReferQueue::class;
    }

    /**
     * Adds an item to queue
     *
     * @param null $user_id
     * @return static
     */
    function addToQueue($user_id = null) {
        $first = ReferQueue::orderBy('position', 'desc')->first();
        if ($first) $position = $first->position;
        else $position = 0;

        $data = [
            'user_id' => $user_id,
            'position' => $position + 1
        ];

        $ref = ReferQueue::create($data);

        if ($ref->errors()->count() > 0) {
            $this->throwException(implode(',', $ref->errors()->all()));
        }

        return $ref;
    }

    /**
     * Moves the item to the new position, adjusting all queued element's positions
     * in between
     *
     * @param ReferQueue $item
     * @param $new_pos
     */
    public function move(ReferQueue $item, $new_pos) {
        if ($item->position == $new_pos) return;

        $old_pos = $item->position;

        $item->position = -1;
        $item->save();

        if ($new_pos < $old_pos) {
            ReferQueue::where('position', '>=', $new_pos)
                ->where('position', '<',  $old_pos)
                ->orderBy('position', 'desc')
                ->update([
                    'position' => \DB::raw('`position` + 1')
                ]);
        } else {
            ReferQueue::where('position', '<=', $new_pos)
                ->where('position', '>', $old_pos)
                ->orderBy('position', 'asc')
                ->update([
                    'position' => \DB::raw('`position` - 1')
                ]);
        }

        $item->position = $new_pos;
        $item->save();
    }

    /**
     * Finds a user by id
     *
     * @param $user_id
     * @return mixed
     */
    public function findByUserID($user_id) {
        $refer = ReferQueue::where('user_id', $user_id)->first();
        if (!$refer) $this->throwException("Cannot find user with id: $user_id, in queue.");
        return $refer;
    }
}