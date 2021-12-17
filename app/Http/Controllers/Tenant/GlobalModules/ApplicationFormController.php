<?php

namespace App\Http\Controllers\Tenant\GlobalModules;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Core\Setting\Setting;
use App\Services\Tenant\Setting\SettingService;
use App\Http\Requests\GlobalModules\ApplicationFormRequest;

class ApplicationFormController extends Controller
{
    public function __construct(SettingService $service)
    {
        $this->service = $service;
        $this->setting = "";
    }

    private function __setJobSettingsData()
    {
        $this->setting = Setting::where('name', 'application_form')->first();
    }

    public function show()
    {
        $this->__setJobSettingsData();
        return array($this->setting->name => json_decode($this->setting->value));
    }

    public function update(ApplicationFormRequest $request)
    {
        $this->__setJobSettingsData();
        $this->setting->value = json_encode($request->application_form);
        $this->setting->save();
        return updated_responses('global_application_form');
    }
}
