<?php

namespace App\Repositories;

use App\Models\WorkTime;
use Carbon\Carbon;

class WorkTimeRepository
{
    public function createWorkTime(string $employeeId, Carbon $start, Carbon $end)
    {
        return WorkTime::create([
            'employee_id' => $employeeId,
            'start_time' => $start,
            'end_time' => $end,
            'start_day' => $start->toDateString(),
        ]);
    }

    public function isDuplicateWorkTime(string $employeeId, Carbon $start)
    {
        return WorkTime::where('employee_id', $employeeId)
                       ->where('start_day', $start->toDateString())
                       ->exists();
    }
}