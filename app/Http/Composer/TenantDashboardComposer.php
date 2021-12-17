<?php


namespace App\Http\Composer;


use App\Helpers\Settings\SettingParser;
use App\Http\Composer\Helper\AdministrationPermissions;
use App\Http\Composer\Helper\AttendancePermissions;
use App\Http\Composer\Helper\EmployeePermissions;
use App\Http\Composer\Helper\LeavePermissions;
use App\Http\Composer\Helper\LogoIcon;
use App\Http\Composer\Helper\PayrollPermissions;
use App\Http\Composer\Helper\RecruitmentPermissions;
use App\Http\Composer\Helper\SettingPermissions;
use App\Models\Tenant\WorkingShift\WorkingShift;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class TenantDashboardComposer
{
    public function compose(View $view)
    {
        ['logo' => $logo, 'icon' => $icon] = LogoIcon::new(true)
            ->logoIcon();

        if (!Cache::get('has_default_work_shift')) {
            Cache::forget('has_default_work_shift');

            Cache::rememberForever('has_default_work_shift', function () {
                return WorkingShift::query()
                    ->where('is_default', 1)
                    ->exists();
            });
        }


        $view->with([
            'permissions' => [
                [
                    'name' => __t('dashboard'),
                    'url' => route('tenant.dashboard', optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name]),
                    'icon' => 'pie-chart',
                    'permission' => auth()->user()->can('manage_dashboard')
                ],
                [
                    'name' => __t('job_desk'),
                    'icon' => 'user',
                    'url' => EmployeePermissions::new(true)->profile(),
                    'permission' => true,

                ],
                EmployeePermissions::new(true)->canVisit() ?
                    [
                        'name' => __t('employee'),
                        'icon' => 'users',
                        'id' => 'employee',
                        'permission' => EmployeePermissions::new(true)->canVisit(),
                        'subMenu' => EmployeePermissions::new(true)->permissions(),
                    ] : [],
                [
                    'name' => __t('leave'),
                    'icon' => 'clipboard',
                    'id' => 'leave',
                    'permission' => LeavePermissions::new(true)->canVisit(),
                    'subMenu' => LeavePermissions::new(true)->permissions(),
                ],
                [
                    'name' => __t('attendance'),
                    'icon' => 'calendar',
                    'id' => 'attendance_menu',
                    'permission' => AttendancePermissions::new(true)->canVisit(),
                    'subMenu' => AttendancePermissions::new(true)->permissions(),
                ],
                [
                    'name' => __t('payroll'),
                    'icon' => 'dollar-sign',
                    'id' => 'payroll_menu',
                    'permission' => PayrollPermissions::new(true)->canVisit(),
                    'subMenu' => PayrollPermissions::new(true)->permissions(),
                ],
                AdministrationPermissions::new(true)->canVisit() ?
                    [
                        'name' => __t('administration'),
                        'icon' => 'briefcase',
                        'id' => 'administration',
                        'permission' => AdministrationPermissions::new(true)->canVisit(),
                        'subMenu' => AdministrationPermissions::new(true)->permissions()
                    ] :
                    [
                        'name' => __t('holiday'),
                        'icon' => 'calendar',
                        'url' => AdministrationPermissions::new(true)->holidayUrl(),
                        'permission' => authorize_any(['view_holidays'])
                    ],
                [
                    'name' => __t('recruitment'),
                    'icon' => 'pie-chart',
                    'id' => 'recruitment',
                    'subMenu' => RecruitmentPermissions::new(true)->permissions(),
                    'permission' => authorize_any(['view_holidays'])
                ],
                [
                    'name' => __t('settings'),
                    'id' => 'tenant-settings',
                    'icon' => 'settings',
                    'subMenu' => SettingPermissions::new(true)->permissions(),
                    'permission' => SettingPermissions::new(true)->canVisit()
                ],


            ],
            'settings' => SettingParser::new(true)->getSettings(),
            'top_bar_menu' => [
                [
                    'optionName' => __t('my_profile'),
                    'optionIcon' => 'user',
                    'url' => route('tenant.user.profile', optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name])
                ],
                [
                    'optionName' => __t('notifications'),
                    'optionIcon' => 'bell',
                    'url' => route("support.tenant.notifications", optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name])
                ],
                auth()->user()->can('view_settings') ?
                    [
                        'optionName' => __t('settings'),
                        'optionIcon' => 'settings',
                        'url' => authorize_any([
                            'view_settings',
                            'view_corn_job_settings',
                            'view_delivery_settings',
                            'view_notification_settings'
                        ]) ? route("support.tenant.settings", optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name]) : '#'
                    ] : [],
                [
                    'optionName' => __t('log_out'),
                    'optionIcon' => 'log-out',
                    'url' => request()->root() . '/admin/log-out'
                ],
            ],
            'logo' => $logo,
            'logo_icon' => $icon,
            'hasDefaultWorkShift' => Cache::get('has_default_work_shift')
        ]);
    }
}
