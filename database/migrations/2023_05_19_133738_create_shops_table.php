<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            $table->string('shop', 255)->unique()->index();
            $table->string('access_token', 255)->nullable();
            $table->timestamp('installed_at')->useCurrent();
            $table->timestamp('activated_at')->nullable();
            $table->timestamp('uninstalled_at')->nullable();
            $table->timestamp('cleaned_at')->nullable();
            $table->integer('used_days')->unsigned()->default(0);
            $table->boolean('shop_inactive')->nullable();
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
        Schema::dropIfExists('shops');
    }
}
