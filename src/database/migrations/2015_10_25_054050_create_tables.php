<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Kevupton\Referrals\Models\Config;
use Kevupton\Referrals\Models\Queue;
use Schema;

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
        $start_at = ref_conf('queue.start_at', 0);

        Schema::create($prefix . 'config', function (Blueprint $table) {
            $table->string('key', 32);
            $table->text('value');
            $table->primary('key');
        });

        Config::set('start_at', $start_at);
        Config::set('db_prefix', $prefix);

        Schema::create($prefix . 'queue', function (Blueprint $table) {
            $table->unsignedInteger('position')->primary();
            $table->unsignedInteger('user_id')->nullable()->unique();
        });

        Schema::create($prefix . 'codes', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->primary();
            $table->string('code', 32)->unique();
            $table->timestamps();
        });

        Schema::create($prefix . 'referrals', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->primary();
            $table->unsignedInteger('by_user_id')->index();
            $table->timestamps();
        });

        if ($start_at >= 1) {
            Queue::insert(collect(range(1, $start_at))->map(function ($i) {
                return ['position' => $i];
            }));
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $config = Config::where('key', 'db_prefix')->first();
        $prefix = $config ? $config->value : '';

        Schema::dropIfExists($prefix . 'refer_queue');
        Schema::dropIfExists($prefix . 'refer_flow');
        Schema::dropIfExists($prefix . 'subscribers');
        Schema::dropIfExists($prefix . 'config');
    }
}
