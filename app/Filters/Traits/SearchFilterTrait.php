<?php


namespace App\Filters\Traits;


trait SearchFilterTrait
{
    public function search($search = null)
    {
        $this->name($search);
    }
}
