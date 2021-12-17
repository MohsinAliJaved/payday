<?php

namespace App\Models\Tenant\Applicant;

use App\Models\Core\BaseModel;
use App\Models\Tenant\Applicant\Traits\Relationship\ApplicantRelationship;
use App\Models\Tenant\Applicant\Traits\Rules\ApplicantRules;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Applicant extends BaseModel
{
    use HasFactory, ApplicantRelationship, ApplicantRules;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'gender',
        'date_of_birth'
    ];

    
    protected $casts = [
        'date_of_birth' => 'datetime:Y-m-d'
    ];

    protected $appends = ['full_name'];

    public function getFullNameAttribute()
    {
        return $this->last_name
            ? $this->first_name . ' ' . $this->last_name
            : $this->first_name;
    }

    public function getNameAttribute()
    {
        return $this->full_name;
    }
}
