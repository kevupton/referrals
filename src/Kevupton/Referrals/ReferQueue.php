<?php namespace Kevupton\Referrals;

class ReferQueue extends ReferralModel {
    // table name
    protected $table = 'refer_queue';

    // validation rules
    public static $rules = array(
        'user_id' => 'numeric|exists:subscribers,id',
        'position' => 'required|numeric'
    );

    protected $fillable = array(
        'user_id', 'position'
    );

    public static $relationsData = array(
        'user' => array(self::BELONGS_TO, Subscriber::class, 'user_id'),
    );
}
