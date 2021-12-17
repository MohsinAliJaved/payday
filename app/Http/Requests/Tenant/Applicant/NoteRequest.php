<?php

namespace App\Http\Requests\App\Applicant;

use App\Http\Requests\App\AppRequest;
use App\Models\App\Applicant\Note;

class NoteRequest extends AppRequest
{
    public function rules()
    {
        return $this->initRules( new Note());
    }
}
