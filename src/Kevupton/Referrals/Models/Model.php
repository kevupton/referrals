<?php

namespace Kevupton\Referrals\Models;

use Kevupton\Ethereal\Models\Ethereal;

/**
 * Class Model
 * @package Kevupton\Referrals\Models
 */
class Model extends Ethereal
{
    public function getTable()
    {
        $table = parent::getTable();
        return ref_prefix() . $table;
    }
}
