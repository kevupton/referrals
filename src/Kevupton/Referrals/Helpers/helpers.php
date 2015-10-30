<?php

define('REFERRAL_CONFIG', 'referrals');

if (!function_exists('ref_prefix')) {
    /**
     * Gets the prefix of the database table.
     *
     * @return string
     */
    function ref_prefix() {
        return ref_conf('database_prefix');
    }
}

if (!function_exists('ref_jumps')) {
    /**
     * Gets the jump count for each referral
     *
     * @return int
     */
    function ref_jumps() {
        return ref_conf('jump_count');
    }
}

if (!function_exists('ref_conf')) {
    /**
     * Gets a config value from the config file.
     *
     * @param string $prop the key property
     * @param string $default the default response
     *
     * @return mixed
     */
    function ref_conf($prop, $default = '') {
        return Config::get(REFERRAL_CONFIG . '.' . $prop, $default);
    }
}
