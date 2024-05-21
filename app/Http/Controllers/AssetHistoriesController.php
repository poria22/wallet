<?php

namespace App\Http\Controllers;

use App\Models\AssetHistories;
use App\Models\User;
use Illuminate\Http\Request;

class AssetHistoriesController extends Controller
{
    public function userHistories(User $user)
    {
        try {
            $message = '';
            $data = '';

            if ($user->assetHistories->count() !== 0) {
                $message = 'is success';
                $data = $user->assetHistories;
            } else {
                $message = 'The user has no assetHistories';
            }
            return response()->json([
                'message' => $message,
                'status' => 'success',
                'data' => $data
            ], 200);
        } catch (\Exception $exception) {
            return \response()->json([
                'message' => $exception->getMessage(),
                'status' => 'failed',
                'data' => ''
            ], 500);
        }

    }
}
