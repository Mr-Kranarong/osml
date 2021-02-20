<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Settings;

class SettingsController extends Controller
{
    public function index()
    {
        return view('admin.settings.index',[
            'settings' => Settings::all()
        ]);
    }
    public function update(Settings $setting){
        $setting->update([
            'value' => request()->value
        ]);
        return redirect()->back();
    }
}
