<?php

namespace App\Models\Tenant\Applicant;

use App\Models\Core\BaseModel;
use App\Models\Tenant\Applicant\Traits\Relationship\EventTypeRelationship;
use App\Models\Tenant\Applicant\Traits\Rules\EventTypeRules;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EventType extends BaseModel
{
    use HasFactory, EventTypeRules, EventTypeRelationship;

    protected $fillable = ['name'];
}
