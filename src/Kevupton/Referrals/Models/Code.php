<?php

namespace Kevupton\Referrals\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;
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

    /**
     * Returns the model from the configuration specified.
     *
     * @return Eloquent
     */
    public function getUser ()
    {
        try {
            return ref_parse_user($this->user_id);
        } catch (ModelNotFoundException $e) {
            return null;
        }
    }

    /**
     * Returns the users' code otherwise, generates a code model for the specified user if it
     * does not already exist.
     *
     * @param Eloquent $model
     * @return Code
     */
    public static function generate (Eloquent $model)
    {
        try {
            return Code::where('user_id', $model->getKey())
                ->firstOrFail();
        }
        catch (ModelNotFoundException $e) {
            do {
                $code = str_random(32);
                $exists = Code::query()->where('code', $code)->exists();
            } while ($exists);

            return Code::create([
                'user_id' => $model->getKey(),
                'code' => $code
            ]);
        }
    }
}
