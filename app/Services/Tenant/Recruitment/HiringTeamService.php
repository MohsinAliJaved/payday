<?php


namespace App\Services\Tenant\Recruitment;


use App\Models\Tenant\Recruitment\HiringTeam;
use App\Services\App\AppService;
use App\Services\Tenant\TenantService;

class HiringTeamService extends TenantService
{
    public function __construct(HiringTeam $team)
    {
        $this->model = $team;
    }

}