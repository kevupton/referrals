<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 22/01/2018
 * Time: 11:30 AM
 */

namespace Kevupton\Referrals;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Kevupton\Ethereal\Traits\HasMemory;
use Kevupton\Referrals\Events\UserWasReferred;
use Kevupton\Referrals\Exceptions\InvalidReferCodeException;
use Kevupton\Referrals\Jobs\AddMore;
use Kevupton\Referrals\Models\Code;
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
     * @return Referrals
     * @throws InvalidReferCodeException
     */
    public function setToken ($token)
    {
        $this->token()->setToken($token);
        return $this;
    }

    /**
     * @param Model $user
     * @return Referrals
     * @throws InvalidReferCodeException
     */
    public function registerReferral (Model $user)
    {
        $token    = referrals()->token();
        $referrer = $token->validate();

        Referral::create([
            'user_id'    => $user->getKey(),
            'by_user_id' => $referrer->getKey(),
        ]);

        if ($this->queueEnabled()) {
            $this->queue()->moveUp($referrer, ref_jumps());
        }

        event(new UserWasReferred($user, $token->getUser()));

        return $this;
    }

    /**
     * Gets the user that referred the input user.
     * If no user referred the input user then return null,
     * otherwise return the referrer.
     *
     * @param Model $user
     * @return Model|null
     */
    public function getReferrer (Model $user)
    {
        /** @var Referral $referral */
        $referral = Referral::where('user_id', $user->getKey())->first();

        if ($referral) {
            return $referral->getReferrer();
        }

        return null;
    }

    /**
     * Gets the referral for the user. If the user was referred, then it will return a referral
     * object. Otherwise it will return null.
     *
     * @param Model $user
     * @return mixed
     */
    public function getReferral (Model $user)
    {
        /** @var Referral $referral */
        return Referral::where('user_id', $user->getKey())->first();
    }

    /**
     * Gets all the referrals that a user has made.
     *
     * @param Model $user
     * @return Collection|Referral[]
     */
    public function getReferrals (Model $user)
    {
        /** @var Referral $referral */
        return Referral::where('by_user_id', $user->getKey())->get();
    }

    /**
     * Counts the total number of referrals that a user has made
     *
     * @param Model $user
     * @return Collection|Referral[]
     */
    public function totalReferralsMade (Model $user)
    {
        /** @var Referral $referral */
        return Referral::where('by_user_id', $user->getKey())->count();
    }

    /**
     * Adds a user to the queue
     *
     * @param Model $user
     * @return $this
     */
    public function addToQueue (Model $user)
    {
        if ($this->queueEnabled()) {
            $this->queue()->addToQueue($user);
        }

        return $this;
    }

    /**
     * Checks whether the queue is enabled
     *
     * @return boolean
     */
    public function queueEnabled ()
    {
        return (bool)ref_conf('queue.enabled', false);
    }

    /**
     * Generates a code for the user if it does not already exist
     *
     * @param Model $user
     * @return Referrals
     */
    public function generateCode (Model $user)
    {
        Code::generate($user);
        return $this;
    }

    /**
     * Returns the users referral code.
     * If it does not exist, it will make one
     *
     * @param Model $user
     * @return string
     */
    public function getReferralCode (Model $user)
    {
        return Code::generate($user)->code;
    }
}