<?php

namespace Kevupton\Referrals\Models;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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
    protected $primaryKey   = 'user_id';
    public    $incrementing = false;

    public $rules = [
        'user_id'    => 'required|integer',
        'by_user_id' => 'required|integer',
    ];

    protected $fillable = [
        'user_id', 'by_user_id',
    ];


    public function getReferrer ()
    {
        try {
            return ref_parse_user($this->by_user_id);
        } catch (ModelNotFoundException $e) {
            return null;
        }
    }

    public function getReferred ()
    {
        try {
            return ref_parse_user($this->user_id);
        } catch (ModelNotFoundException $e) {
            return null;
        }
    }
}
