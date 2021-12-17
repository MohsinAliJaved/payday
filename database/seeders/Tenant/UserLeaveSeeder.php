<?php

namespace Database\Seeders\Tenant;

use App\Models\Core\Auth\User;
use App\Models\Tenant\Leave\LeaveType;
use App\Models\Tenant\Leave\UserLeave;
use Database\Seeders\Traits\DisableForeignKeys;
use Illuminate\Database\Seeder;

class UserLeaveSeeder extends Seeder
{
    use DisableForeignKeys;

    public function run()
    {
        $this->disableForeignKeys();
        UserLeave::query()->truncate();

        $leaves = LeaveType::query()->pluck('id')->reduce(function ($leaves, $leaveType) {
            return array_merge($leaves, User::query()->get()
                ->map(fn(User $user) => [
                    'user_id' => $user->id,
                    'leave_type_id' => $leaveType,
                    'amount' => rand(5, 10),
                    'start_date' => now()->subMonths(5)->format('Y-m-d'),
                    'end_date' => now()->addYear()->subMonths(5)->format('Y-m-d'),
                ])->toArray()
            );
        }, []);

        UserLeave::insert($leaves);

        $this->enableForeignKeys();
    }
}
