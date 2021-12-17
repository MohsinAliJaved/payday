<?php

namespace App\Models\Tenant\Recruitment;

use App\Models\Core\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Tenant\Recruitment\Traits\Rules\HiringTeamRules;
use App\Models\Tenant\Recruitment\Traits\Relationship\HiringTeamRelationship;

class HiringTeam extends BaseModel
{
    use HasFactory, HiringTeamRules, HiringTeamRelationship;

    protected $fillable = ['job_post_id', 'recruiter_id'];

    protected $casts = [
        'job_post_id' => 'integer',
        'recruiter_id' => 'integer'
    ];
}