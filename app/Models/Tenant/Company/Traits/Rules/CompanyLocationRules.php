<?php


namespace App\Models\Tenant\Company\Traits\Rules;


trait CompanyLocationRules
{
    public function createdRules()
    {
        return [
            'address' => 'required',
        ];
    }

    public function updatedRules()
    {
        return $this->createdRules();
    }
}