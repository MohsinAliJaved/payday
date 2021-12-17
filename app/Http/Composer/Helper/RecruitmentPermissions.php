<?php


namespace App\Http\Composer\Helper;


use App\Helpers\Core\Traits\InstanceCreator;

class RecruitmentPermissions
{
    use InstanceCreator;

    public function permissions()
    {
        return [
            [
                'name' => __t('job_post'),
                'url' => route('tenant.jobs'),
                'permission' => true
            ],
            [
                'name' => __t('candidates'),
                'url' => route('tenant.candidates'),
                'permission' => true
            ],
            [
                'name' => __t('career_page'),
                'url' => route('tenant.career-page.show'),
                'permission' => true
            ]
        ];
    }

    // public function canVisit()
    // {
    //     return authorize_any(['view_employees', 'view_designations', 'view_employment_statuses']);
    // }

    // public function profile($user = null)
    // {
    //     return route(
    //         'support.employee.details',
    //         !optional(tenant())->is_single ?
    //         [
    //             'employee' => $user ?: auth()->id(),
    //             'tenant_parameter' => optional(tenant())->short_name ?: 'default-tenant'
    //         ]: [
    //             'employee' => $user ?: auth()->id()
    //         ]
    //     );
    // }
}
