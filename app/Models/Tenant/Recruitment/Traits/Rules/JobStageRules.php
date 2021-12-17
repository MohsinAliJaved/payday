<?php


namespace App\Models\Tenant\Recruitment\Traits\Rules;


trait JobStageRules
{
    public function createdRules()
    {
        return [
            'job_post_id' => 'required|exists:job_posts,id',
            'name' => 'required|string',
            'ordered_stages' => 'required|string',  //Comma seperated stage name- new,stage1,stage2,...,new_stage,hired,disqualified
        ];
    }

    public function updatedRules()
    {
        return [
            'name' => 'required|string'
        ];
    }
}