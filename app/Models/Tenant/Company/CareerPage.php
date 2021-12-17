<?php

namespace App\Models\Tenant\Company;

use App\Models\App\AppModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CareerPage extends AppModel
{
    use HasFactory;

    protected $fillable = ['title', 'brief', 'content', 'page_blocks', 'page_style'];

    protected $casts = [
        'company_detail_id' => 'int'
    ];
}
