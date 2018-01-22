<?php

namespace Kevupton\Referrals\Models;

use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Class Config
 * @package Kevupton\Referrals\Models
 * @property array $value
 * @property string $key
 */
class Config extends Model
{
    protected $primaryKey = 'key';
    protected $keyType = 'string';
    public $timestamps = false;

    protected $casts = [
        'value' => 'array'
    ];

    public $rules = [
        'key' => 'required|max:32',
    ];

    protected $fillable = [
        'key', 'value'
    ];

    /**
     * @param $key
     * @param null $defaultValue
     * @return mixed
     */
    public static function get($key, $defaultValue = null)
    {
        try {
            return Config::firstOrFail($key)->value;
        } catch (ModelNotFoundException $e) {
            return $defaultValue;
        }
    }

    /**
     * @param $key
     * @param null $value
     * @return Config
     */
    public static function set($key, $value = null)
    {
        return Config::updateOrCreate([
            'key' => $key
        ], [
            'value' => $value
        ]);
    }
}
