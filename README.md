## Referrals ##
Laravel Referral System integration. 

### How it works ###
This referral system is based of queue jumping. The idea being that when someone signs up they are placed into a queue.
Then whenever someone registers, they jump **X** places in the queue. 
The idea behind the queue itself is that it is to slowly grow over a period of time, to simulate users signing up, and hence a demand. Or a queue to jump.

---

### Config File ###
This file handles everything to do with the queue itself. A commend on each field is there explaining what each does.
```php
<?php

return array(

    //prefix to each of the tables in the database
    'database_prefix' => 'ref_',

    //how many uses to pretend we have at the start.
    'start_at' => 1548,

    //the number of positions jumped when a referral is made.
    'jump_count' => 10,

    //How often to add an extra fake referral
    'addmore' => [
        'interval' => 3600, //the time between each insert
        'amount' => 1 //the amount to insert
    ]
);
```
