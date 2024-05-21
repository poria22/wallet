<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssetRequest;
use App\Http\Requests\UserAssetRequest;
use App\Models\Asset;
use App\Models\AssetHistories;
use App\Models\User;
use App\Models\UserAsset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserAssetController extends Controller
{
    public function IncreaseDecrease(Request $request)
    {
        $data = \Illuminate\Support\Facades\Validator::make($request->all(), (new UserAssetRequest())->rules());

        if ($data->fails()) {
            return response()->json([
                'message' => $data->errors(),
                'status' => 'failed',
            ], 400);
        }

        try {
            $amount = $request->amount;
            $user_id = $request->user_id;
            $asset_id = $request->asset_id;
            $change_type = $request->change_type;
            $userAsset = UserAsset::firstOrCreate([
                'user_id' => $user_id,
                'asset_id' => $asset_id
            ]);
//بررسی نوع تراکنش
            if ($change_type == 'increase') {
                $userAsset->amount += $amount;
            } elseif ($change_type == 'decrease') {
                if ($userAsset->amount > $amount) {
                    $userAsset->amount -= $amount;
                } else {
                    return response()->json([
                        'message' => 'The value entered is greater than the value of the asset',
                        'status' => 'failed',
                    ], 500);
                }
            }
            $userAsset->save();
//تاریخچه تغییرات
            $asset = AssetHistories::create([
                'user_id' => $user_id,
                'asset_id' => $asset_id,
                'user_asset_id' => $userAsset->id,
                'amount' => $amount,
                'change_type' => $change_type
            ]);
            return response()->json([
                'message' => 'Asset was created',
                'status' => 'success',
                'data' => $userAsset
            ], 201);

        } catch (\Exception $exception) {
            return \response()->json([
                'message' => $exception->getMessage(),
                'status' => 'failed',
                'data' => ''
            ], 500);
        }
    }

    public function convert(Request $request)
    {
        $data = \Illuminate\Support\Facades\Validator::make($request->all(), (new UserAssetRequest())->rules());

        if ($data->fails()) {
            return response()->json([
                'message' => $data->errors(),
                'status' => 'failed',
            ], 400);
        }

        try {
            $user = User::findOrFail($request->user_id);
            $fromAsset = $user->assets()->where('assets.id', $request->from_asset_id)->first();
            $toAssetId = Asset::findOrFail($request->to_asset_id);
            $amount = $request->amount;
            $change_type = $request->change_type;

            if (!$fromAsset) {
                return response()->json(['message' => 'assets not found for the user'], 500);
            } elseif ($fromAsset->pivot->amount < $amount) {
                return response()->json(['message' => 'insufficient amount of the source asset'], 400);
            }

            $fromAssetPrice = $fromAsset->price;
            $toAssetPrice = $toAssetId->price;
            //محاسبه نسبت ارزش
            $convertedAmount = ($amount * $fromAssetPrice) / $toAssetPrice;
            $toAsset = $user->assets()->where('asset_id', $toAssetId->id)->first();

            DB::transaction(function () use ($user, $fromAsset, $toAsset, $amount, $convertedAmount, $toAssetId, $change_type) {
                $user->assets()->updateExistingPivot($fromAsset->id, ['amount' => $fromAsset->pivot->amount - $amount]);
                if (!$toAsset) {
                    $toAsset = UserAsset::create(['user_id' => $user->id, 'asset_id' => $toAssetId->id, 'amount' => $convertedAmount]);
                } else {
                    $user->assets()->updateExistingPivot($toAsset->id, ['amount' => $toAsset->pivot->amount + $convertedAmount]);
                }

                $asset = AssetHistories::create([
                    'user_id' => $user->id,
                    'user_asset_id' => $fromAsset->pivot->id,
                    'amount' => $amount,
                    'change_type' => $change_type,
                    'from_asset_id' => $fromAsset->id,
                    'to_asset_id' => $toAsset->id
                ]);
            });

            return response()->json(['message' => 'asset conversion successful', 'status' => 'success']);
        } catch (\Exception $exception) {
            return \response()->json([
                'message' => $exception->getMessage(),
                'status' => 'failed',
                'data' => ''
            ], 500);
        }

    }

}
