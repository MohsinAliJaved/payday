<?php

namespace App\Models\Tenant\Recruitment\Traits\Rules;

trait StageRules{

    public function createdRules()
    {
        return [
            'name' => 'required|unique:stages',
        ];
    }

    public function updatedRules()
    {
        return $this->createdRules();
    }
}