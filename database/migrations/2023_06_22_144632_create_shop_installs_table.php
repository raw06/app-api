<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopInstallsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_installs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->index()->constrained();
            $table->longText('shopify_permission');
            $table->integer('used_days')->default(0);
            $table->string("ipinfo")->nullable();
            $table->timestamp('install_at');
            $table->timestamp('uninstall_at')->nullable();
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
        Schema::dropIfExists('shop_installs');
    }
}
