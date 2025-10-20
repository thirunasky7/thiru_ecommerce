<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StoreSetting;

class StoreSettingController extends Controller
{
    public function index()
    {
        return StoreSetting::all();
    }

    public function update(Request $request, $key)
    {
        $setting = StoreSetting::where('key', $key)->first();

        if ($setting) {
            $setting->update(['value' => $request->value]);
            return response()->json(['message' => 'Setting updated']);
        }
            
        return response()->json(['message' => 'Setting not found'], 404);
    }
}
