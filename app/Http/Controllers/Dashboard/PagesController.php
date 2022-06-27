<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class PagesController extends Controller
{

    public function termsAndConditions(){
        $setting = Setting::query()->first();
        return view('dashboard.pages.terms_and_conditions',compact('setting'));
    }

    public function updateTermsAndConditions(Request $request){
        $setting = Setting::query()->first();
        $data = $request->except('_token','terms_and_conditions_ar','terms_and_conditions_en');
        $data['Terms_and_Conditions'] = ['ar' => $request->terms_and_conditions_ar, 'en' => $request->terms_and_conditions_en];
        $setting->update($data);
        return redirect()->route('admin.pages.terms');
    }

    public function privacyPolicy(){
        $setting = Setting::query()->first();
        return view('dashboard.pages.privacy',compact('setting'));
    }

    public function updatePrivacyPolicy(Request $request){
        $setting = Setting::query()->first();
        $data = $request->except('_token','privacy_policy_ar','privacy_policy_en');
        $data['privacy_policy'] = ['ar' => $request->privacy_policy_ar, 'en' => $request->privacy_policy_en];
        $setting->update($data);
        return redirect()->route('admin.pages.privacy');
    }
}
