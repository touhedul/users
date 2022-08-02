<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserLedgersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_ledgers', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->enum('type',['income','fee','withdrawal']);
            $table->string('description',255)->nullable();
            $table->decimal('debit',10,2)->nullable()->default(0);
            $table->decimal('credit',10,2)->nullable()->default(0);
            $table->decimal('sbalance',10,2)->nullable()->default(0);
            $table->decimal('ebalance',10,2)->nullable()->default(0);
            $table->enum('status',['pending','processed'])->nullable()->default('pending');
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
        Schema::dropIfExists('user_ledgers');
    }
}
