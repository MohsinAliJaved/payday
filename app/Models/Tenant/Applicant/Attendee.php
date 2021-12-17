<?php

namespace App\Models\Tenant\Applicant;

use App\Models\Tenant\Applicant\Traits\Relationship\AttendeeRelationship;
use App\Models\Tenant\Applicant\Traits\Rules\AttendeeRules;
use App\Models\Core\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attendee extends BaseModel
{
    use HasFactory, AttendeeRules, AttendeeRelationship;

    protected $fillable = ['event_id', 'hiring_team_id'];

    protected $casts = [
        'event_id' => 'int',
        'hiring_team_id' => 'int'
    ];

    public $timestamps = false;
}
