<?php namespace Kevupton\Referrals;

class Subscriber extends ReferralModel {
    // table name
    protected $table = 'subscribers';

    // validation rules
    public static $rules = array(
        'name' => 'required|max:125',
        'ref_code' => 'required|max:255',
        'is_win_prize' => 'required|boolean',
        'points' => 'required|points',
        'referrals' => 'required|referrals',
        'email' => 'required|max:255'
    );

    protected $fillable = array(
        'name', 'ref_code', 'is_win_prize', 'points',
        'referrals', 'email'
    );

    public static $relationsData = array(
        'queue' => array(self::HAS_ONE, ReferQueue::class, 'user_id'),
    );
}
