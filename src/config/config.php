<?php

return array(

    //prefix to each of the tables in the database
    'database_prefix' => 'ref_',

    // the user associated to the referral
    'user' => \App\User::class,

    // the referral queue
    'queue' => [
        'enabled' => true,

        //how many uses to pretend we have at the start.
        'start_at' => 1548,

        //the number of positions jumped when a referral is made.
        'jump_count' => 10,

        //How often to add an extra fake referral
        'add_more' => [
            'interval' => 3600, //the time between each insert in seconds
            'amount' => 1 //the amount to insert
        ],
    ],

    'referrals' => [
    ]
);
