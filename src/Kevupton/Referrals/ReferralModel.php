<?php namespace Kevupton\Referrals;

use Kevupton\BeastCore\BeastModel;

class ReferralModel extends BeastModel {

    /**
     * Defines the prefix for the table.
     * @param array $attr
     */
    public function __construct($attr = array()) {
        $this->table = ref_prefix() . $this->table;
        parent::__construct($attr);
    }
}
