<?php

define('REFERRAL_CONFIG', 'referrals');

if (!function_exists('ref_prefix')) {
    /**
     * Gets the prefix of the database table.
     *
     * @return string
     */
    function ref_prefix ()
    {
        return ref_conf('database_prefix');
    }
}

if (!function_exists('ref_jumps')) {
    /**
     * Gets the jump count for each referral
     *
     * @return int
     */
    function ref_jumps ()
    {
        return ref_conf('queue.jump_count');
    }
}

if (!function_exists('ref_conf')) {
    /**
     * Gets a config value from the config file.
     *
     * @param string $prop    the key property
     * @param string $default the default response
     *
     * @return mixed
     */
    function ref_conf ($prop, $default = '')
    {
        return config(REFERRAL_CONFIG . '.' . $prop, $default);
    }
}

if (!function_exists('ref_parse_user')) {
    /**
     * @param $user_id
     */
    function ref_parse_user ($user_id)
    {
        $user = ref_user();
        $user::findOrFail($user_id);
    }
}

if (!function_exists('ref_user')) {
    /**
     * @return mixed
     */
    function ref_user ()
    {
        return ref_conf('user');
    }
}

if (!function_exists('referrals')) {
    /**
     * Gets the referrals instance
     *
     * @return \Kevupton\Referrals\Referrals
     */
    function referrals ()
    {
        return app(\Kevupton\Referrals\Providers\ReferralsServiceProvider::SINGLETON);
    }
}