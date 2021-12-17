<?php


namespace App\Models\Tenant\Recruitment\Traits\Rules;


trait TodoRules
{
    public function createdRules()
    {
        return [
            'status_id' => 'required|exists:statuses,id',
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string',
        ];
    }

    public function updatedRules()
    {
        return [
            'status_id' => 'nullable|exists:statuses,id',
            'name' => 'nullable|string',
        ];
    }
}