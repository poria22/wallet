<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'price'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_assets');
    }

    public function userAssets()
    {
        return $this->hasMany(UserAsset::class);
    }

    public function assetHistories()
    {
        return $this->hasMany(AssetHistories::class);
    }
}
