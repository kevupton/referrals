<?php namespace Kevupton\Referrals;

use Kevupton\Ethereal\Models\Ethereal;

class ReferralModel extends Ethereal {

    /**
     * Defines the prefix for the table.
     * @param array $attr
     */
    public function __construct($attr = array()) {
        $this->table = ref_prefix() . $this->table;
        parent::__construct($attr);
    }
}
