<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttendanceSetting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = AttendanceSetting::all()->keyBy('key');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'office_latitude' => 'required|numeric|between:-90,90',
            'office_longitude' => 'required|numeric|between:-180,180',
            'allowed_radius_meters' => 'required|integer|min:1',
            'work_start_time' => 'required|date_format:H:i',
            'work_end_time' => 'required|date_format:H:i|after:work_start_time',
        ]);

        foreach ($validated as $key => $value) {
            $type = in_array($key, ['allowed_radius_meters']) ? 'integer' : 'string';
            AttendanceSetting::setValue($key, $value, $type);
        }

        return redirect()->route('admin.settings.index')
            ->with('success', 'Settings updated successfully');
    }
}
