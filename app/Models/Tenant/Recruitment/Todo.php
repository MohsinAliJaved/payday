<?php

namespace App\Models\Tenant\Recruitment;

use App\Models\Core\BaseModel;
use App\Models\Tenant\AppModel;
use App\Models\Tenant\Recruitment\Traits\Relationship\TodoRelationship;
use App\Models\Tenant\Recruitment\Traits\Rules\TodoRules;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Todo extends BaseModel
{
    use HasFactory, TodoRules, TodoRelationship;

    protected $fillable = [
        'status_id',
        'user_id',
        'name',
    ];

    protected $casts = [
        'status_id' => 'int',
        'user_id' => 'int',
    ];
}