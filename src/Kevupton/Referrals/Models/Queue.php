<?php

namespace Kevupton\Referrals\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Kevupton\Referrals\Exceptions\ReferQueueException;

/**
 * Class ReferQueue
 *
 * @package Kevupton\Referrals\Models
 * @property int position
 * @property int user_id
 */
class Queue extends Model
{
    protected $table = 'queue';
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


    /**
     * Gets the Queue model that belongs to a specific user
     *
     * @param Eloquent $user
     * @return Queue
     * @throws ReferQueueException
     */
    public static function fromUser (Eloquent $user)
    {
        try {
            return Queue::query()
                ->where('user_id', $user->getKey())
                ->firstOrFail();
        }
        catch (ModelNotFoundException $e) {
            throw new ReferQueueException('User does not exist in queue');
        }
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
