<?php

namespace App\Models\Tenant\Applicant\Traits\Rules;

trait EventRules
{
    public function createdRules()
    {
        return [
            "event_type_id" => "required|exists:event_types,id",
            "start_at" => "required|date",
            "end_at" => "required|date",
            "location" => 'required|string',
            "job_applicant_id" => "required|exists:job_applicants,id",
            "description" => "nullable|string",
            "attendees" => "required|array",
            "attendees.*.hiring_team_id" => "required|exists:hiring_teams,id",
        ];
    }

    public function updatedRules()
    {
        return [
            "event_type_id" => "nullable|exists:event_types,id",
            "start_at" => "nullable",
            "end_at" => "nullable",
            "location" => 'nullable|string',
            "description" => "nullable|string",
            "attendees" => "nullable|array",
            "attendees.*.hiring_team_id" => "required|exists:hiring_teams,id",
        ];
    }
}
