<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 22/01/2018
 * Time: 11:30 AM
 */

namespace Kevupton\Referrals;

use Illuminate\Database\Eloquent\Model;
use Kevupton\Ethereal\Traits\HasMemory;
use Kevupton\Referrals\Events\UserWasReferred;
use Kevupton\Referrals\Exceptions\InvalidReferCodeException;
use Kevupton\Referrals\Jobs\AddMore;
use Kevupton\Referrals\Models\Referral;
use Kevupton\Referrals\Repositories\QueueRepository;

class Referrals
{
    use HasMemory;

    /**
     * Dispatches the addMore event to add empty users at a set interval
     *
     * @param null $amount
     * @param null $interval
     */
    public function startAddingEmptyUsers ($amount = null, $interval = null)
    {
        $amount   = $amount ?: ref_conf('queue.add_more.amount', 1);
        $interval = $interval ?: ref_conf('queue.add_more.interval', 3600);

        dispatch(new AddMore($interval, $amount));
    }

    /**
     * Gets the queue repository
     *
     * @return QueueRepository
     */
    public function queue ()
    {
        return $this->memory('queue', function () {
            return new QueueRepository();
        });
    }

    /**
     * Gets the referral token
     *
     * @return ReferToken
     */
    public function token ()
    {
        return $this->memory('token', function () {
            return new ReferToken();
        });
    }

    /**
     * @return bool
     */
    public function hasToken ()
    {
        return $this->token()->hasToken();
    }

    /**
     * @param $token
     */
    public function setToken ($token)
    {
        $this->token()->setToken($token);
    }

    /**
     * @param Model $user
     * @throws InvalidReferCodeException
     */
    public function registerReferral (Model $user)
    {
        $token = referrals()->token();
        $referrer = $token->validate();

        Referral::create([
            'user_id'    => $user->getKey(),
            'by_user_id' => $referrer->getKey(),
        ]);

        $this->queue()
            ->moveUp($referrer, ref_jumps())
            ->addToQueue($user);

        event(new UserWasReferred($user, $token->getUser()));
    }
}