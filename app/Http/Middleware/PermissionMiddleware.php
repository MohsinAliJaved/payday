<?php

namespace App\Http\Middleware;

use App\Exceptions\GeneralException;
use App\Helpers\Route\RouteToPermissionString;
use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class PermissionMiddleware
{
    use RouteToPermissionString;

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param \Closure $next
     * @return mixed
     * @throws GeneralException
     * @throws AuthorizationException
     *
     */

    public function replace_first_position_string($string="", $finds = [], $replace="")
    {
        foreach($finds as $find){
            if (strpos($string, $find) === 0){
                return substr_replace($string,$replace,0, strlen($find));
                break;
            }
        }
        return $string;
    }

    private function return_custom_permission_string($permission_string)
    {
        $redirect_permission = [
            'can_add_stage_to_job_post' => 'can_create_job_stage',
            'can_add_update_to_job_post' => 'can_update_job_stage',
            'can_activate_job_post' => 'can_change_job_post_status',
            'can_move_applicant_job_stage' => 'can_change_stage_job_applicant',
            'can_check_email_applicant' => 'can_create_applicant',
            'can_update_disqualification_note_job_applicant' => 'can_disqualify_job_applicant',
            'can_view_job_overview_view' => 'can_view_job_overview',
            'can_allow_job_post_setting_view' => 'can_update_job_post_setting_job_post',
            'can_create_attendee' => 'can_manage_attendee',
            'can_delete_attendee' => 'can_manage_attendee',
            'can_get_teams_by_job_post' => 'can_view_hiring_team',
            'can_view_job_summery' => 'can_view_job_post',
            'update_user_name' => 'update_users',
            'view_user_list' => 'view_users',
            'update_user_list' => 'update_users',
            'create_user_list' => 'create_users',
            'delete_user_list' => 'delete_users',
            'can_view_sharable_thumbnail' => 'can_manage_sharable_thumbnail',
            'can_update_sharable_thumbnail' => 'can_manage_sharable_thumbnail',
            'can_show_application_form_global' => 'can_manage_global_application_form',
            'can_update_application_form_global' => 'can_manage_global_application_form',
            'can_update_apply_form_setting_job_post' => 'can_manage_job_post_application_form',
            'can_publish_job_post' => 'can_change_job_post_status'
        ];
        return $redirect_permission[$permission_string] ?? $permission_string;
    }
    
    public function handle($request, Closure $next)
    {
        $route_name = preg_replace('/core.|app.|tenant./', '', $request->route()->getName(), 1);

        if (!$route_name) {
            throw new GeneralException('Route must have a name');
        }

        /**
         * Avoid if the user is appAdmin
         */
        if (auth()->user()->isAppAdmin()) {
            return $next($request);
        }

        $permission_string = $this->setRouteName($route_name)
            ->validateRouteName()
            ->getPermissionString();

        /*
         * Authorizing user with permission and merge allowed resource into allowed_resource index
         * if the allowed_resource is empty array then all resource is allowed
         * otherwise only take what in that array
         * allowed resource will contain ID's of model which is permitted for currently logged in user
         */
        $meta = get_authorized($permission_string);

        $request->merge([
            'allowed_resource' => $meta
        ]);

        return $next($request);
    }

}
