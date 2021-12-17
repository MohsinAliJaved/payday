<?php

namespace App\Http\Controllers\Tenant\GlobalModules;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Core\Setting\Setting;
use App\Models\Tenant\JobPost\JobPost;
use App\Services\Core\Setting\SettingService;
use App\Repositories\Core\Status\StatusRepository;

class CareerPageController extends Controller
{
    private $setting = null;

    public function __construct(SettingService $service)
    {
        $this->service = $service;
    }

    public function show()
    {
        $setting = Setting::where('name', 'career_page')->first();
        return array($setting->name => json_decode($setting->value));
    }

    public function showCareerPage(JobPost $jobPost)
    {
        $setting = Setting::where('name', 'career_page')->first();
        // dd($setting);
        $careerPage = json_decode($setting->value);
        $jobPosts = $jobPost
            ->select('id', 'name', 'job_type_id', 'slug', 'company_location_id', 'last_submission_date')
            ->with([
                'location:id,address',
                'jobType:id,name',
            ])
            ->where('status_id', resolve(StatusRepository::class)->getStatusId('job_post', 'status_open'))
            ->get();
        return view('career-page.index', ['careerPage' => $careerPage, 'jobPosts' => $jobPosts]);
    }

    private function __setCareerSettingsData()
    {
        $this->setting = Setting::where('name', 'career_page')->first();
    }

    public function update(Request $request)
    {
        $request->validate([
            'career_page' => 'required|array'
        ]);

        $this->__setCareerSettingsData();

        $this->setting->value = json_encode($request->career_page);
        $this->setting->save();
        return updated_responses('career_page');
    }
}
