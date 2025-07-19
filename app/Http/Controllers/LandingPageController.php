<?php

namespace App\Http\Controllers;

use App\Models\Setting;

class LandingPageController extends Controller
{
    public function index()
    {
        // Ambil semua setting yang dibutuhkan
        $logo = Setting::where('key', 'site_logo')->first()?->value ?? [];
        $hero = Setting::where('key', 'hero_section')->first()?->value ?? [];
        $social = Setting::where('key', 'social_media')->first()?->value ?? [];
        $contact = Setting::where('key', 'contact_info')->first()?->value ?? [];

        return view('landing', compact('logo', 'hero', 'social', 'contact'));
    }
}
