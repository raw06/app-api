<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property $uninstall_at
 * @property $used_days
 * @property $install_at
 */
class ShopInstall extends Model
{
    use HasFactory;

    protected $fillable = [
        'shop_id',
        'shopify_permission',
        'install_at',
        'uninstall_at',
        'created_at',
        'updated_at',
        'used_days',
        'ipinfo'
    ];

    protected $casts = [
        'ipinfo' => 'json'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'install_at',
        'uninstall_at',
    ];

    public function uninstall(): ShopInstall
    {
        $this->uninstall_at = Carbon::now();
        $this->used_days = Carbon::now()->diffInDays($this->install_at);

        return $this;
    }

    public function uninstalled(): bool
    {
        return !is_null($this->uninstall_at);
    }
}
