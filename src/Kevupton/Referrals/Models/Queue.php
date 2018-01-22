<?php

namespace Kevupton\Referrals\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Class ReferQueue
 *
 * @package Kevupton\Referrals\Models
 * @property int position
 * @property int user_id
 */
class Queue extends Model
{
    protected $primaryKey   = 'position';
    public    $incrementing = false;
    public    $timestamps   = false;

    public $rules = [
        'position' => 'required|integer',
        'user_id'  => 'nullable|integer',
    ];

    protected $fillable = [
        'user_id', 'position',
    ];


    public static function fromUser (Eloquent $user)
    {
        return Queue::query()
            ->where('user_id', $user->getKey())
            ->firstOrFail();
    }

    /**
     * Gets the user associated to this model
     *
     * @return null|Eloquent
     */
    public function getUser ()
    {
        try {
            return ref_parse_user($this->user_id);
        } catch (ModelNotFoundException $e) {
            return null;
        }
    }
}
