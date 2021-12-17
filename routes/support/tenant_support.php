<?php

use App\Http\Controllers\Core\Auth\UserInvitationController;
use App\Http\Controllers\Core\Notification\NotificationEventController;
use App\Http\Controllers\Tenant\Attendance\AttendanceDetailsController;
use App\Http\Controllers\Tenant\Attendance\AttendanceLogController;
use App\Http\Controllers\Tenant\Attendance\AttendanceSummaryController;
use App\Http\Controllers\Tenant\Auth\TenantRoleAPIController;
use App\Http\Controllers\Tenant\Auth\TenantUserAPIController;
use App\Http\Controllers\Tenant\Employee\AttendanceController;
use App\Http\Controllers\Tenant\Employee\AttendancePunchInController;
use App\Http\Controllers\Tenant\Employee\DepartmentAPIController;
use App\Http\Controllers\Tenant\Employee\DepartmentController;
use App\Http\Controllers\Tenant\Employee\DesignationAPIController;
use App\Http\Controllers\Tenant\Employee\EmployeeAddressController;
use App\Http\Controllers\Tenant\Employee\EmployeeContactController;
use App\Http\Controllers\Tenant\Employee\EmployeeController;
use App\Http\Controllers\Tenant\Employee\EmployeeEmploymentStatusController;
use App\Http\Controllers\Tenant\Employee\EmployeeLeaveAllowanceController;
use App\Http\Controllers\Tenant\Employee\EmployeePayrunController;
use App\Http\Controllers\Tenant\Employee\EmployeeProfileController;
use App\Http\Controllers\Tenant\Employee\EmployeeSalaryController;
use App\Http\Controllers\Tenant\Employee\EmployeeSocialLinkController;
use App\Http\Controllers\Tenant\Employee\EmploymentStatusAPIController;
use App\Http\Controllers\Tenant\Employee\ManualAttendanceController;
use App\Http\Controllers\Tenant\Leave\LeaveAPIController;
use App\Http\Controllers\Tenant\Leave\LeaveSummeryController;
use App\Http\Controllers\Tenant\Leave\LeaveLogController;
use App\Http\Controllers\Tenant\Leave\LeaveTypeController;
use App\Http\Controllers\Tenant\NavigationController;
use App\Http\Controllers\Tenant\Payroll\BeneficiaryBadgeApiController;
use App\Http\Controllers\Tenant\Payroll\ConflictPayrunController;
use App\Http\Controllers\Tenant\Payroll\DefaultPayrunController;
use App\Http\Controllers\Tenant\Payroll\ManualPayrunController;
use App\Http\Controllers\Tenant\Payroll\PayrollSettingController;
use App\Http\Controllers\Tenant\Payroll\PayrollSummeryController;
use App\Http\Controllers\Tenant\Payroll\PayrunController;
use App\Http\Controllers\Tenant\Settings\TenantDeliveryController;
use App\Http\Controllers\Tenant\WorkingShift\WorkingShiftAPIController;
use App\Http\Controllers\Tenant\WorkingShift\WorkingShiftController;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Bus;

Route::group(['prefix' => ''], function (Router $router) {
    $router->get('user/notifications', [NavigationController::class, 'notifications'])
        ->name('tenant.notifications');

    $router->get('app/settings', [NavigationController::class, 'settings'])
        ->name('tenant.settings');

    $router->get('administration/users', [NavigationController::class, 'users'])
        ->name('tenant.users');

    $router->get('selectable/users', [TenantUserAPIController::class, 'index'])
        ->middleware(['can:view_users','can_access:view_users', 'check_behavior'])
        ->name('users.select');

    $router->get('selectable/attendance-settings/users', [TenantUserAPIController::class, 'index'])
        ->middleware(['can:view_users','can_access:view_users', 'additional_behavior:attendance'])
        ->name('users.select');

    $router->get('selectable/role/users', [TenantUserAPIController::class, 'index'])
        ->middleware(['can:view_users','can_access:view_users', 'additional_behavior:role'])
        ->name('users.select');

    $router->get('selectable/work-shift/users', [TenantUserAPIController::class, 'index'])
        ->middleware(['can:view_users','can_access:view_users', 'additional_behavior:work_shift'])
        ->name('users.select');

    $router->get('selectable/department/users', [TenantUserAPIController::class, 'index'])
        ->middleware(['can:view_users','can_access:view_users', 'additional_behavior:department'])
        ->name('users.select');

    $router->get('selectable/payrun/users', [TenantUserAPIController::class, 'index'])
        ->middleware(['can:view_users','can_access:view_users', 'additional_behavior:payrun'])
        ->name('users.select');

    $router->get('selectable/leave-settings/users', [TenantUserAPIController::class, 'index'])
        ->middleware(['can:view_users','can_access:view_users', 'additional_behavior:leave'])
        ->name('users.select');

    $router->get('selectable/roles', [TenantRoleAPIController::class, 'index'])
        ->middleware('can:view_roles')
        ->name('users.roles');

    $router->get('selectable/filter/roles', [TenantRoleAPIController::class, 'filterRoles'])
        ->middleware('can:view_roles')
        ->name('users.roles');

    $router->get('selectable/departments', [DepartmentAPIController::class, 'index'])
        ->name('selectable.departments')
        ->middleware(['can:view_departments', 'check_behavior']);

    $router->get('selectable/work-shift/departments', [DepartmentAPIController::class, 'index'])
        ->name('selectable.departments')
        ->middleware(['can:view_departments', 'additional_behavior:work_shift']);

    $router->get('selectable/payrun/departments', [DepartmentAPIController::class, 'index'])
        ->name('selectable.departments')
        ->middleware(['can:view_departments', 'additional_behavior:payrun']);

    $router->get('selectable/holiday/departments', [DepartmentAPIController::class, 'index'])
        ->name('selectable.departments')
        ->middleware(['can:view_departments', 'additional_behavior:holiday']);

    $router->get('selectable/department/departments', [DepartmentAPIController::class, 'index'])
        ->name('selectable.departments')
        ->middleware(['can:view_departments', 'additional_behavior:department']);

    $router->get('selectable/leave-types', [LeaveAPIController::class, 'index'])
        ->name('selectable.leave-types');

    $router->patch('employees/punch-in', [AttendanceController::class, 'punchIn'])
        ->name('in.punch');

    $router->patch('employees/punch-out', [AttendanceController::class, 'punchOut'])
        ->name('out.punch');

    $router->get('employees/check-punch-in', [AttendancePunchInController::class, 'checkPunchIn'])
        ->name('check.punch_in');

    $router->get('employees/punch-in-time', [AttendancePunchInController::class, 'getPunchInTime'])
        ->name('time.punch_in');

    $router->get('notification/events', [NotificationEventController::class, 'index'])
        ->middleware('can:view_notification_settings')
        ->name('notification.event');

    $router->get('check-mail-settings', [TenantDeliveryController::class, 'isExists'])
        ->name('check-mail-settings');

    $router->post('users/invite-user', [UserInvitationController::class, 'invite'])
        ->middleware('can:invite_user')
        ->name('users.invite');

    $router->get('administration/departments', [NavigationController::class, 'departments'])
        ->name('employee.departments');

    $router->get('administration/org-structure', [NavigationController::class, 'orgStructure'])
        ->name('organization.structure');

    $router->get('administration/work-shifts', [NavigationController::class, 'shifts'])
        ->name('employee.work_shifts');

    $router->get('settings/leave-settings', [NavigationController::class, 'leaveSettings'])
        ->name('settings.leave');

    $router->get('settings/attendance', [NavigationController::class, 'attendanceSettings'])
        ->name('settings.attendance');

    $router->get('settings/payroll-settings', [NavigationController::class, 'payrollSettings'])
        ->name('settings.payroll');

    $router->get('settings/import', [NavigationController::class, 'import'])
        ->name('settings.import');

    $router->get('employee/payroll', [NavigationController::class, 'payroll'])
        ->name('employee.payroll');

    $router->get('attendances/lists', [NavigationController::class, 'attendances'])
        ->name('attendances.lists');

    $router->get('attendances/requests', [NavigationController::class, 'attendancesRequest'])
        ->name('attendances.requests');

    $router->get('attendances/details', [NavigationController::class, 'attendancesDetails'])
        ->name('attendances.details');

    $router->get('attendances/summaries', [NavigationController::class, 'attendancesSummaries'])
        ->name('attendances.summaries')
        ->middleware('check_behavior');

    $router->get('leave/lists', [NavigationController::class, 'leaves'])
        ->name('leave.lists');

    $router->get('leave/status', [NavigationController::class, 'leaveStatus'])
        ->name('leave.status');

    $router->get('leave/requests', [NavigationController::class, 'leaveRequests'])
        ->name('leave.requests')
        ->middleware('check_behavior');

    $router->get('leave/calendar', [NavigationController::class, 'leaveCalendar'])
        ->name('leave.calendar')
        ->middleware('check_behavior');

    $router->get('leave/summaries', [NavigationController::class, 'leaveSummaries'])
        ->name('leave.summaries')
        ->middleware('check_behavior');

    $router->get('leave/periods', [NavigationController::class, 'leavePeriods'])
        ->name('leave.periods');

    $router->get('leave/types', [NavigationController::class, 'leaveTypes'])
        ->name('leave.types');

    $router->get('employee/lists', [NavigationController::class, 'employees'])
        ->name('employee.lists');

    $router->get('employee/designations', [NavigationController::class, 'designations'])
        ->name('employee.designations');

    $router->get('employee/employment-statuses', [NavigationController::class, 'employmentStatus'])
        ->name('employee.employment-statuses');

    $router->get('notification/events/{notification_event}', [NotificationEventController::class, 'show'])
        ->name('notification.event');

    $router->get('administration/holidays', [NavigationController::class, 'holidays'])
        ->name('employee.holidays');

    $router->get('selectable/working-shifts', [WorkingShiftAPIController::class, 'index'])
        ->name('selectable.working_shift')
        ->middleware('can:view_working_shifts');

    $router->get('selectable/designations', [DesignationAPIController::class, 'index'])
        ->name('selectable.designations')
        ->middleware('can:view_designations');

    $router->get('selectable/employment-statuses', [EmploymentStatusAPIController::class, 'index'])
        ->name('selectable.employment-statuses')
        ->middleware('can:view_employment_statuses');

    $router->get('selectable/leave-periods', [LeaveSummeryController::class, 'leavePeriods'])
        ->name('selectable.leave-periods');

    $router->get('employees/profile/employee-id', [EmployeeProfileController::class, 'employeeId'])
        ->name('employees.employee_id');

    $router->get('selectable/leave/{user}/users', [AttendanceSummaryController::class, 'users'])
        ->name('selectable.users')
        ->middleware(['check_behavior', 'can_access:view_all_leaves']);

    $router->get('selectable/attendance/{user}/users', [AttendanceSummaryController::class, 'users'])
        ->name('selectable.users')
        ->middleware(['can_access:view_all_attendance', 'check_behavior']);

    $router->get('employees/{employee}/profile', [NavigationController::class, 'employee'])
        ->name('employee.details')
        ->middleware('check_behavior');

    $router->get('app/salary-range', [EmployeeSalaryController::class, 'range'])
        ->name('salary.range');

    $router->group(['prefix' => 'app'], function (Router $router) {
        $router->get('employees', [EmployeeController::class, 'index'])
            ->name('employees.index')
            ->middleware(['employee_access', 'can_access:view_employees', 'check_behavior']);

        $router->get('employees/{employee}', [EmployeeController::class, 'show'])
            ->name('employees.show')
            ->middleware(['employee_access', 'can_access:view_employees', 'check_behavior']);

        $router->get('attendances/{employee}/summaries-data-table', [AttendanceSummaryController::class, 'summaries'])
            ->name('attendances.summaries-data-table')
            ->middleware(['can:view_attendance_summary', 'can_access:view_all_attendance']);

        $router->get('attendances/{details}/log', [AttendanceLogController::class, 'index'])
            ->name('attendance-log.index');

        $router->get('attendances/periods', [AttendanceDetailsController::class, 'attendancePeriods'])
            ->name('attendances.periods');

        $router->get('attendances/{employee}/summaries', [AttendanceSummaryController::class, 'index'])
            ->name('attendance-summary.index')
            ->middleware(['can:view_attendance_summary', 'can_access:view_all_attendance']);

        $router->get('selectable/{user}/next-user/{type}', [TenantUserAPIController::class, 'nextUser'])
            ->name('attendances.summaries.next-user')
            ->middleware('can:view_attendance_summary');

        $router->post('employees/add-attendance', [ManualAttendanceController::class, 'store'])
            ->name('attendances.store')
            ->middleware(['add_attendance_middleware', 'check_behavior']);

        $router->get('leaves/{employee}/summaries', [LeaveSummeryController::class, 'index'])
            ->name('leaves.summaries-data-table')
            ->middleware('can:view_leave_summaries');

        $router->group(['prefix' => 'employees/{employee}/'], function (Router $router) {
            $router->get('social-links', [EmployeeSocialLinkController::class, 'index'])
                ->name('employee-social-links.index')
                ->middleware('employee_access');

            $router->patch('social-links', [EmployeeSocialLinkController::class, 'update'])
                ->name('employee-social-links.update')
                ->middleware('employee_access');

            $router->get('addresses', [EmployeeAddressController::class, 'show'])
                ->name('employee-address.index')
                ->middleware('employee_access');

            $router->get('emergency-contacts', [EmployeeContactController::class, 'index'])
                ->name('employee-address.index')
                ->middleware('employee_access');

            $router->patch('update-termination-note', [EmployeeEmploymentStatusController::class, 'updateTerminationNote'])
                ->name('employees.update-termination-note')
                ->middleware('can:terminate_employees');

            $router->patch('profile-update', [EmployeeProfileController::class, 'update'])
                ->name('employees-profile.update');
        });

        $router->apiResource('working-shifts', WorkingShiftController::class)->only('show');

        $router->get('leaves/{leave}/log', [LeaveLogController::class, 'index'])
            ->name('leave-log.index');

        $router->get('leaves/{employee}/allowances', [EmployeeLeaveAllowanceController::class, 'index'])
            ->name('employee.leave-allowances');

        $router->get('payrun/{payrun}/users/conflicted', [ConflictPayrunController::class, 'users'])
            ->name('conflicted-users.payrun');

        $router->get('payrun/{payrun}/user/{user}/conflicted', [ConflictPayrunController::class, 'userPayslips'])
            ->name('conflicted-user-payslip.payrun');

        $router->delete('departments/upcoming/working-shift/{id}', [DepartmentController::class, 'deleteUpcomingWorkShift'])
            ->name('department-user.index');
    });

    $router->get('payroll/beneficiary-badges', [NavigationController::class, 'beneficiaryBadges'])
        ->name('payroll.beneficiary-badges');

    $router->get('selectable/beneficiaries', [BeneficiaryBadgeApiController::class, 'index'])
        ->name('selectable.beneficiaries')
        ->middleware('can:view_beneficiaries');

    $router->get('payroll/payrun', [NavigationController::class, 'payrun'])
        ->name('payroll.payrun');

    $router->get('payroll/payslip', [NavigationController::class, 'payslip'])
        ->name('payroll.payslip');

    $router->get('payroll/summery', [NavigationController::class, 'payrollSummery'])
        ->name('payroll.summery');

    $router->get('/batch/{batchId}', function (string $batchId) {
        return Bus::findBatch($batchId);
    })->middleware('can:view_payruns');

    $router->get('app/payruns/{payrun}/batch/update', [PayrunController::class, 'updateBatch'])
        ->name('payruns-batch-update.show')
        ->middleware('can:view_payruns');

    $router->get('app/employees/{employee}/payrun-setting', [EmployeePayrunController::class, 'index'])
        ->name('employee-payrun.index')
        ->middleware(['can_access:view_payslips']);

    $router->get('app/payroll/{employee}/summaries', [PayrollSummeryController::class, 'summery'])
        ->name('payroll-summery.index')
        ->middleware(['can_access:view_payslips']);

    $router->get('app/payroll/{employee}/summery-table', [PayrollSummeryController::class, 'index'])
        ->name('payroll-summery-table.index')
        ->middleware(['can_access:view_payroll_summery']);

    $router->get('app/payrun/default', [DefaultPayrunController::class, 'index'])
        ->name('default-payrun.index')
        ->middleware(['can:run_default_payrun']);

    $router->get('app/payrun/default/employees', [DefaultPayrunController::class, 'employees'])
        ->name('payrun-employees.default')
        ->middleware(['can:run_default_payrun']);

    $router->get('app/settings/payrun/audience', [PayrollSettingController::class, 'getAudience'])
        ->name('payrun-audience.index')
        ->middleware(['can:update_payrun_audience']);

});