<?php namespace Kevupton\Referrals\Repositories;

use Kevupton\BeastCore\Repositories\BeastRepository;
use Kevupton\Referrals\Exceptions\ReferralException;
use Kevupton\Referrals\ReferQueue;

class ReferQueueRepository extends BeastRepository {
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
}