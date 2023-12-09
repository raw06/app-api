<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_infos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->unique()->index()->constrained();
            $table->string('shopify_id');
            $table->string('address1', 512)->nullable();
            $table->string('address2', 512)->nullable();
            $table->string('city', 128)->nullable();
            $table->string('country', 128)->nullable();
            $table->string('country_code', 128)->nullable()->index();
            $table->string('country_name', 128)->nullable();
            $table->timestamp('created_at');
            $table->timestamp('updated_at')->nullable();
            $table->string('customer_email', 128)->nullable();
            $table->string('currency', 128)->nullable()->index();
            $table->string('domain', 128)->nullable();
            $table->string('email', 128)->nullable();
            $table->string('google_apps_domain', 128)->nullable();
            $table->boolean('google_apps_login_enabled')->nullable();
            $table->float('latitude')->nullable();
            $table->float('longitude')->nullable();
            $table->string('money_format', 128)->nullable();
            $table->string('money_in_emails_format', 128)->nullable();
            $table->string('money_with_currency_format', 128)->nullable();
            $table->string('money_with_currency_in_emails_format', 128)->nullable();
            $table->string('myshopify_domain', 128)->nullable();
            $table->boolean('eligible_for_payments')->nullable();
            $table->string('name', 128)->nullable();
            $table->string('plan_name', 128)->nullable()->index();
            $table->string('plan_display_name', 128)->nullable();
            $table->boolean('password_enabled')->nullable();
            $table->string('phone', 128)->nullable();
            $table->string('primary_locale', 128)->nullable();
            $table->string('primary_location_id', 128)->nullable();
            $table->string('province', 128)->nullable();
            $table->string('province_code', 128)->nullable();
            $table->string('shop_owner', 128)->nullable();
            $table->string('source', 128)->nullable();
            $table->boolean('force_ssl')->nullable();
            $table->boolean('tax_shipping')->nullable();
            $table->boolean('taxes_included')->nullable();
            $table->boolean('county_taxes')->nullable();
            $table->boolean('requires_extra_payments_agreement')->nullable();
            $table->string('timezone', 128)->nullable();
            $table->string('iana_timezone', 128)->nullable();
            $table->string('zip', 128)->nullable();
            $table->boolean('has_storefront')->nullable();
            $table->boolean('setup_required')->nullable();
            $table->boolean('has_discounts')->nullable();
            $table->boolean('has_gift_cards')->nullable();
            $table->boolean('eligible_for_card_reader_giveaway')->nullable();
            $table->boolean('finances')->nullable();
            $table->string('weight_unit')->nullable();
            $table->text('description');
            $table->string('auto_configure_tax_inclusivity', 128)->nullable();
            $table->string('cookie_consent_level', 128)->nullable();
            $table->string('visitor_tracking_consent_preference', 128)->nullable();
            $table->boolean('pre_launch_enabled')->nullable();
            $table->json('enabled_presentment_currencies')->nullable();
            $table->boolean('multi_location_enabled')->nullable();
            $table->boolean('checkout_api_supported')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shop_infos');
    }
}
