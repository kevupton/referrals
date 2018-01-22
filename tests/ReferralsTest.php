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
        $start_at =  ref_conf('queue.start_at', 0);
        $this->assertEquals($start_at, \Kevupton\Referrals\Models\Queue::all()->count());
    }

    public function testJumpQueue() {
        $jump = 10; //jumps 10 places in the queue

        $ref = \Kevupton\Referrals\Models\Queue::findOrFail(20);

        $move = new \Kevupton\Referrals\Jobs\MoveInQueue($ref, $ref->position - $jump);

        $move->handle();

        $ref = \Kevupton\Referrals\Models\Queue::findOrFail(20);

        $this->assertEquals(10, $ref->position);
    }
}