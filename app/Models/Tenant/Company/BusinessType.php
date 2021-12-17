<?php

namespace App\Models\Tenant\Company;

use App\Models\App\AppModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BusinessType extends AppModel
{
    use HasFactory;

    protected $fillable = ['type', 'brief'];

    protected $casts = [];
}
