<?php

namespace Kevupton\Referrals\Models;

/**
 * Class ReferQueue
 *
 * @package Kevupton\Referrals\Models
 * @property int            user_id
 * @property int            by_user_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Referral extends Model
{
    protected $primaryKey = 'user_id';
    public $incrementing = false;

    public $rules = [
        'user_id' => 'required|integer',
        'by_user_id' => 'required|integer'
    ];

    protected $fillable = [
        'user_id', 'by_user_id'
    ];
}
