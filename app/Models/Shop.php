<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property $shop
 * @property $access_token
 * @property $uninstalled_at
 * @property $activated_at
 * @property $installed_at
 */
class Shop extends Model
{
    use HasFactory;

    protected $fillable = [
        'shop',
        'access_token',
        'installed_at',
        'activated_at', // the time that shop start using app free or paid
        'uninstalled_at',
        'cleaned_at', // the time we clean personal data for shop
        'used_days',
        'shop_inactive',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'installed_at',
        'activated_at',
        'uninstalled_at',
        'clean_at',
    ];

    public function uninstalled(): bool
    {
        return $this->access_token == null;
    }

    public function uninstall(): Shop
    {
        $this->access_token = null;
        $this->uninstalled_at = Carbon::now();

        $shopInstall = $this->shopInstall();
        if ($shopInstall && !$shopInstall->uninstalled()) {
            $shopInstall->uninstall()->save();
        }

        return $this;
    }

    public function shopInstall()
    {
        return ShopInstall::where('shop_id', $this->id)
            ->orderBy('install_at', 'desc')
            ->first();
    }

    public function deactivate(): Shop
    {
        $this->activated_at = null;

        return $this;
    }

    public function install($accessToken): Shop
    {
        $this->access_token = $accessToken;
        $this->installed_at = new Carbon();

        return $this;
    }

    public function createShopInstall()
    {
        return ShopInstall::create([
            'shop_id'            => $this->id,
            'status'             => true,
            'shopify_permission' => config('shopify.permissions')[0],
            'install_at'         => Carbon::now(),
        ]);
    }

    public function qaSetting(): HasOne {
        return $this->hasOne(QuestionAnswerSetting::class);
    }

    public function displaySetting(): HasOne {
        return $this->hasOne(DisplaySetting::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function appUser()
    {
        return $this->hasOne(User::class, 'shop_id', 'id');
    }
}
