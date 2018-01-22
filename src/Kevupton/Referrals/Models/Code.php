<?php

namespace Kevupton\Referrals\Models;

use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Class ReferQueue
 *
 * @package Kevupton\Referrals\Models
 * @property int            user_id
 * @property string         code
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Code extends Model
{
    protected $primaryKey   = 'user_id';
    public    $incrementing = false;

    public $rules = [
        'user_id' => 'required|integer',
        'code'    => 'required|string|max:32',
    ];

    protected $fillable = [
        'user_id', 'code',
    ];

    public function getUser ()
    {
        try {
            return ref_parse_user($this->user_id);
        } catch (ModelNotFoundException $e) {
            return null;
        }
    }
}
