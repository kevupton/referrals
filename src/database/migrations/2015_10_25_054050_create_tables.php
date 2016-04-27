<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Kevupton\Referrals\Config;

class CreateTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $prefix = ref_prefix();
        $start_at =  ref_conf('start_at', 0);
        Schema::create($prefix . 'config', function(Blueprint $table) {
            $table->string('key', 32);
            $table->text('value');
            $table->primary('key');
        });

        Config::create([
            'key' => 'start_at',
            'value' => $start_at
        ]);

        Config::create([
            'key' => 'db_prefix',
            'value' => $prefix
        ]);

//        if ( ! Schema::hasTable($prefix . 'subscribers')) {
//            Schema::create($prefix . 'subscribers', function (Blueprint $table) {
//                $table->increments('id');
//                $table->string('mevu_tag', 32)->unique();
//                $table->string('name', 125);
//                $table->string('email');
//                $table->string('ref_code');
//                $table->tinyInteger('is_win_prize');
//                $table->integer('points');
//                $table->integer('referrals');
//                $table->timestamps();
//            });
//        }

        if ( ! Schema::hasTable($prefix . 'refer_queue')) {

            Schema::create( $prefix . 'refer_queue', function ( Blueprint $table ) {
                $table->increments( 'id' );
                $table->integer( 'user_id' )->nullable()->unique();
                $table->integer('position')->unique();
            } );

            if ($start_at) {
                $string = "INSERT INTO $prefix" . "refer_queue (position) VALUES";
                for ($i = 1; $i <= $start_at; $i++) {
                    $string .= "($i),";
                }
                $string = trim($string, ",");
                DB::insert($string);
            }
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $config = Config::where('key','db_prefix')->first();
        $prefix = $config? $config->value: '';

        Schema::dropIfExists($prefix . 'refer_queue');
        Schema::dropIfExists($prefix . 'refer_flow');
        Schema::dropIfExists($prefix . 'subscribers');
        Schema::dropIfExists($prefix . 'config');
    }
}
