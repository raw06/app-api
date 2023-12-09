<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shop_id')->index();
            $table->string('title')->nullable()->index();
            $table->string('handle')->nullable();
            $table->longText('image')->nullable();
            $table->string('shopify_id')->index();
            $table->string('vendor')->nullable();
            $table->string('product_type')->nullable();
            $table->string('meta_page')->nullable();
            $table->string('meta_rating')->nullable();
            $table->string('meta_id')->nullable();
            $table->boolean('is_loading')->nullable();
            $table->string('collections_name')->nullable(true)->index();
            $table->string('tags')->nullable(true)->index();
            $table->string('status')->nullable();
            $table->string('published_scope')->nullable();
            $table->string('template_suffix')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
