<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 22/01/2018
 * Time: 3:58 PM
 */

namespace Kevupton\Referrals\Observers;

use Illuminate\Database\Eloquent\Model;
use Kevupton\Referrals\Exceptions\InvalidReferCodeException;

class ReferralObserver
{
    /**
     * @param Model $user
     * @throws InvalidReferCodeException
     */
    public function created (Model $user)
    {
        referrals()->registerReferral($user);
    }
}