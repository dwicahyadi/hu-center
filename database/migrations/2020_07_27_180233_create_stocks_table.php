<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->string('po_no')->nullable();
            $table->string('ip',50)->nullable()->unique();
            $table->string('hu1_no',50)->nullable();
            $table->string('hu2_no',50)->nullable();
            $table->string('description',250)->nullable();
            $table->string('item_code',50)->nullable();
            $table->string('msisdn_no',25)->nullable()->unique();
            $table->date('expire_date')->nullable();
            $table->enum('status',['on warehouse','on canvasser','sold', 'expired', 'transfer'])->default('on warehouse');
            $table->integer('prima_erp_item_id')->nullable();
            $table->string('prima_erp_item_name',100)->nullable();
            $table->string('cluster',100)->nullable();
            $table->string('micro_cluster',100)->nullable();
            $table->string('city',100)->nullable();
            $table->string('canvasser',100)->nullable();
            $table->string('ro',200)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stocks');
    }
}
