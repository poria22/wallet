<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssetRequest;
use App\Models\Asset;
use App\Models\User;
use App\Models\UserAsset;
use http\Env\Response;
use Illuminate\Http\Request;
use Mockery\Exception;

class AssetController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = \Illuminate\Support\Facades\Validator::make($request->all(), (new AssetRequest())->rules());

        if ($data->fails()) {
            return response()->json([
                'message' => $data->errors(),
                'status' => 'failed',
            ], 400);
        }
        try {
            $asset = Asset::create($request->all());

            return response()->json([
                'message' => 'Asset was created',
                'status' => 'success',
                'data' => $asset
            ], 201);

        } catch (\Exception $e) {
            return \response()->json([
                'message' => $e->getMessage(),
                'status' => 'failed',
            ], 500);
        }
    }

    public function getUserAssets(User $user, Request $request)
    {
        try {
            $data = '';
            $message = '';

            if ($request->has('asset_id')) {
                $data = $user->assets()->where('assets.id',$request->asset_id)->get();
            } else {
                $data = $user->assets;
            }

            if ($data->count() !== 0) {
                $message = 'is success';
            } else {
                $message = 'The user has no assets';
            }

            return response()->json([
                'message' => $message,
                'status' => 'success',
                'data' => $data
            ], 200);
        } catch (Exception $exception) {
            return \response()->json([
                'message' => $exception->getMessage(),
                'status' => 'failed',
                'data' => ''
            ], 500);
        }
    }
}
