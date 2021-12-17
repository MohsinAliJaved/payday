<?php

namespace App\Models\Tenant\Applicant\Traits\Relationship;

use App\Models\Tenant\Applicant\Event;

trait EventTypeRelationship
{
    public function events()
    {
        return $this->hasMany(Event::class, 'event_type_id');
    }
}