<?php

namespace App\Http\Requests\Tenant\Attendance;

use App\Http\Requests\BaseRequest;

class AttendanceSettingsRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'auto_approval' => ''
        ];
    }
}
