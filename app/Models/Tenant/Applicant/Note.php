<?php

namespace App\Models\App\Applicant;

use App\Models\Core\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Tenant\Applicant\Traits\Rules\NoteRules;
use App\Models\Tenant\Applicant\Traits\Relationship\NoteRelationship;

class Note extends BaseModel
{
    use HasFactory, NoteRules, NoteRelationship;

    protected $fillable = ['job_applicant_id', 'noted_by', 'description'];

    protected $casts = [
        'job_applicant_id' => "integer",
        'noted_by' => "integer"
    ];
}
