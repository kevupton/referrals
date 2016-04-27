<?php

class ReferralsTest extends TestCase
{
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testTheStartAt()
    {
        $start_at =  ref_conf('start_at', 0);
        $this->assertEquals($start_at, \Kevupton\Referrals\ReferQueue::all()->count());
    }
}