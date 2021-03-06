<?php

namespace App\Http\Controllers\Tenant\Payroll;

use App\Exceptions\GeneralException;
use App\Helpers\Traits\TenantAble;
use App\Http\Controllers\Controller;
use App\Models\Tenant\Payroll\PayrunSetting;
use App\Models\Tenant\Payroll\PayrunType;
use App\Repositories\Core\BaseRepository;
use App\Repositories\Core\Setting\SettingRepository;
use App\Services\Core\Setting\SettingService;
use App\Services\Tenant\Payroll\PayslipService;
use Illuminate\Http\Request;

class PayrollSettingController extends Controller
{
    use TenantAble;

    protected BaseRepository $repository;

    public function __construct(SettingService $service, SettingRepository $repository)
    {
        $this->service = $service;
        $this->repository = $repository;
    }

    public function index()
    {
        $default_payrun = PayrunType::query()
            ->where('is_default', 1)
            ->with([
                'setting',
                'beneficiaries',
                'beneficiaries.beneficiary',
            ])
            ->first();
        return $default_payrun ? $default_payrun->toArray() : [];
    }

    public function getAudience()
    {
        [$setting_able_id, $setting_able_type] = $this->tenantAble();

        $payrun = $this->repository->getFormattedSettings(
            'payrun', $setting_able_type, $setting_able_id
        );
        $beneficiary = $this->repository->getFormattedSettings(
            'beneficiary', $setting_able_type, $setting_able_id
        );

        return [
            'payrun' => $payrun,
            'beneficiary' => $beneficiary
        ];
    }

    public function updateDefault(Request $request)
    {
        $this->validate($request, [
            'payrun_period' => ['required'],
            'consider_type' => ['required'],
        ]);

        $payrun_type = PayrunType::query()->updateOrCreate(
            ['is_default' => 1],
            ['name' => 'Default Payrun', 'is_default' => 1],
        );

        PayrunSetting::query()->updateOrCreate(
            [
                'payrun_settingable_type' => PayrunType::class,
                'payrun_settingable_id' => $payrun_type->id
            ],
            [
                'payrun_period' => $request->payrun_period,
                'consider_type' => $request->consider_type,
                'consider_overtime' => $request->consider_overtime
            ]
        );

        return updated_responses('payroll_settings');
    }

    public function updateAudience(Request $request)
    {
        [$setting_able_id, $setting_able_type] = $this->tenantAble();

        $payrun_attributes = [
            'users' => json_encode($request->get('beneficiary_users', [])),
            'departments' => json_encode($request->get('beneficiary_departments', [])),
            'employment_statuses' => json_encode($request->get('employment_statuses', [])),
            'eligible_audience' => count($request->get('beneficiary_departments', []))
                || count($request->get('beneficiary_users', []))
                || count($request->get('employment_statuses', [])) ? 'restricted' : 'all',
        ];
        $this->service
            ->saveSettings(
                $payrun_attributes,
                'beneficiary',
                $setting_able_type,
                $setting_able_id
            );

        $payrun_attributes = [
            'users' => json_encode($request->get('payrun_users', [])),
            'departments' => json_encode($request->get('payrun_departments', [])),
            'eligible_audience' => count($request->get('payrun_departments', []))
                || count($request->get('payrun_users', [])) ? 'restricted' : 'all',
        ];
        $this->service
            ->saveSettings(
                $payrun_attributes,
                'payrun',
                $setting_able_type,
                $setting_able_id
            );

        return updated_responses('payrun_audience_settings');

    }

    public function updateBeneficiaries(Request $request)
    {
        $default_payrun_type = PayrunType::query()->where('is_default', 1)->first();
        if (!$default_payrun_type) {
            throw new GeneralException(__t('default_payrun_is_not_added_yet'));
        }

        resolve(PayslipService::class)
            ->setModel($default_payrun_type)
            ->setAttributes(\request()->only([
                'allowances',
                'allowanceValues',
                'allowancePercentages',
                'deductions',
                'deductionValues',
                'deductionPercentages',
            ]))
            ->beneficiariesValidation()
            ->updateBeneficiaries();

        return updated_responses('beneficiary_badge_settings');
    }
}
