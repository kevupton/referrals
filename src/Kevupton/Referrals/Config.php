<?php namespace Kevupton\Referrals;


class Config extends ReferralModel {
    // table name
    protected $table = 'config';
    protected $primaryKey = 'key';
    public $timestamps = false;

    // validation rules
    public static $rules = array(
        'key' => 'required|max:32',
        'value' => 'required'
    );

    protected $fillable = array(
        'key', 'value'
    );
}
