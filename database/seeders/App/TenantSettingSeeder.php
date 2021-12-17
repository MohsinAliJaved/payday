<?php

namespace Database\Seeders\App;

use App\Models\Core\Setting\Setting;
use App\Models\Tenant\Employee\EmploymentStatus;
use Illuminate\Database\Seeder;

class TenantSettingSeeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        Setting::query()->where('context', 'tenant')->delete();
        $settingable_type = null;

        if (class_exists(\App\Models\Tenant\Tenant\Tenant::class)) {
            $settingable_type = get_class(new \App\Models\Tenant\Tenant\Tenant());
        }
        $employmentStatuses = EmploymentStatus::all();
        $permanent = $employmentStatuses->where('alias','permanent')->first()->id;
        $probation = $employmentStatuses->where('alias','probation')->first()->id;

        $array = collect([
            [
                'name' => 'tenant_name', 'value' => config('app.name'), 'context' => 'tenant', 'autoload' => 0, 'public' => 1,
            ],
            [
                'name' => 'tenant_icon', 'value' => '/images/icon.png', 'context' => 'tenant', 'autoload' => 0, 'public' => 1,
            ],
            [
                'name' => 'punch_in_time_tolerance', 'value' => 15, 'context' => 'attendance', 'autoload' => 0, 'public' => 1,
            ],
            [
                'name' => 'work_availability_definition', 'value' => 80, 'context' => 'attendance', 'autoload' => 0, 'public' => 1,
            ],
            [
                'name' => 'start_month', 'value' => 'Jan', 'context' => 'leave', 'autoload' => 0, 'public' => 1,
            ],
            [
                'name' => 'approval_level', 'value' => 'single', 'context' => 'leave', 'autoload' => 0, 'public' => 1,
            ],
            [
                'name' => 'allow_bypass', 'value' => 0, 'context' => 'leave', 'autoload' => 0, 'public' => 1,
            ],
            [
                'name' => 'statuses_for_paid_leave', 'value' => json_encode([$permanent]), 'context' => 'leave', 'autoload' => 0, 'public' => 1,
            ],
            [
                'name' => 'statuses_for_unpaid_leave', 'value' => json_encode([$probation,$permanent]), 'context' => 'leave', 'autoload' => 0, 'public' => 1,
            ],
            [
                'name' => 'users', 'value' => '[]', 'context' => 'leave', 'autoload' => 0, 'public' => 1,
            ],
            [
                'name' => 'roles', 'value' => '[]', 'context' => 'leave', 'autoload' => 0, 'public' => 1,
            ]
        ])->merge(collect(config('settings.app')))
            ->map(function ($s) use($settingable_type) {
                $s['context'] = $s['context'] == 'attendance' || $s['context'] == 'leave' ? $s['context'] : 'tenant';
                $s['settingable_id'] = 1;
                $s['settingable_type'] = $settingable_type;
                return $s;
            })
            ->filter(function ($s) {
                return !in_array($s['name'], ['company_name', 'company_icon', 'company_banner', 'company_logo']);
            })->toArray();

        Setting::query()->insert($array);
    }
}
