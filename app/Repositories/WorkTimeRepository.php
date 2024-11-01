<?php

namespace App\Repositories;

use App\Models\WorkTime;
use Carbon\Carbon;
use Illuminate\Support\Collection;

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

    public function getWorkTimes(string $employeeId, string $date): Collection
    {
        return WorkTime::where('employee_id', $employeeId)
            ->where('start_day', Carbon::parse($date)->toDateString())
            ->get();
    }

    public function getMonthlyWorkTimes(string $employeeId, string $date): Collection
    {
        return WorkTime::where('employee_id', $employeeId)
            ->whereYear('start_day', Carbon::parse($date)->year)
            ->whereMonth('start_day', Carbon::parse($date)->month)
            ->get();
    }
}