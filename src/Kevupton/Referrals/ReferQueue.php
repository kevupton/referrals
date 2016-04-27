<?php namespace Kevupton\Referrals;

class ReferQueue extends ReferralModel {
    // table name
    protected $table = 'refer_queue';
    public $timestamps = false;

    // validation rules
    public static $rules = array(
        'user_id' => 'numeric|exists:subscribers,id',
        'position' => 'required|numeric'
    );

    protected $fillable = array(
        'user_id', 'position'
    );
}
