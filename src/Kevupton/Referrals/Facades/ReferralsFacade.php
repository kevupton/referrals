<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 22/01/2018
 * Time: 11:28 AM
 */

namespace Kevupton\Referrals\Facades\Referrals;

use Illuminate\Support\Facades\Facade;
use Kevupton\Referrals\Providers\ReferralsServiceProvider;

class ReferralsFacade extends Facade
{
    protected static function getFacadeAccessor() { return ReferralsServiceProvider::SINGLETON; }
}