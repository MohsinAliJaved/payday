<?php

namespace App\Models\Tenant\Applicant;

use App\Models\Core\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Tenant\Applicant\Traits\Rules\EventRules;
use App\Models\Tenant\Applicant\Traits\Relationship\EventRelationship;

class Event extends BaseModel
{
    use HasFactory, EventRules, EventRelationship;

    protected $fillable = ['event_type_id', 'job_applicant_id', 'location', 'start_at', 'end_at', 'description'];

    protected $casts = [
        'event_type_id' => "integer",
        'job_applicant_id' => "integer",
    ];
}
