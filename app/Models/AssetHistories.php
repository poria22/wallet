<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetHistories extends Model
{
    use HasFactory;

    protected $fillable = ['user_asset_id', 'asset_id', 'amount', 'change_type', 'from_asset_id', 'to_asset_id'];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }
}
