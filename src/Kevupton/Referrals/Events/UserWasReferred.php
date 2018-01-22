<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 22/01/2018
 * Time: 7:07 PM
 */

namespace Kevupton\Referrals\Events;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Queue\SerializesModels;

class UserWasReferred
{
    use SerializesModels;

    /**
     * @var Model
     */
    public $user;
    /**
     * @var Model
     */
    public $referred_by;

    public function __construct (Model $user, Model $referred_by)
    {
        $this->user = $user;
        $this->referred_by = $referred_by;
    }
}