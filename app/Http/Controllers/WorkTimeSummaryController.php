<?php

namespace App\Http\Controllers;

use App\Models\WorkTimeConfig;
use App\Repositories\WorkTimeRepository;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class WorkTimeSummaryController extends Controller
{
    private $workTimeRepository;

    public function __construct(WorkTimeRepository $workTimeRepository)
    {
        $this->workTimeRepository = $workTimeRepository;
    }
    
    public function summaryByDay(Request $request)
    {
        try {
            $validated = $this->validateRequest($request, 'Y-m-d');
            $workTimes = $this->workTimeRepository->getWorkTimes($validated['employee_id'], $validated['date']);

            if ($workTimes->isEmpty()) {
                return $this->errorResponse('Brak wpisów czasu pracy dla podanego dnia.', 404);
            }

            $totalHours = $this->calculateTotalHours($workTimes);
            $salary = $this->calculateSalary($totalHours);

            return response()->json([
                'response' => [
                    'suma po przeliczeniu' => "{$salary} PLN",
                    'ilość godzin z danego dnia' => "{$totalHours}",
                    'stawka' => "{$this->getRate()} PLN"
                ]
            ]);
        } catch (ValidationException $e) {
            return $this->validationErrorResponse($e);
        }
    }

    public function summaryByMonth(Request $request)
    {
        try {
            $validated = $this->validateRequest($request, 'Y-m');
            $workTimes = $this->workTimeRepository->getMonthlyWorkTimes($validated['employee_id'], $validated['date']);

            if ($workTimes->isEmpty()) {
                return $this->errorResponse('Brak wpisów czasu pracy dla podanego miesiąca.', 404);
            }

            list($normalHours, $overtimeHours) = $this->calculateMonthlyHours($workTimes);
            
            $salary = $this->calculateMonthlySalary($normalHours, $overtimeHours);

            return response()->json([
                'response' => [
                     'ilość normalnych godzin z danego miesiąca' => $normalHours,
                     'stawka' => "{$this->getRate()} PLN",
                     'ilość nadgodzin z danego miesiąca' => $overtimeHours,
                     'stawka nadgodzinowa' => "{$this->getOvertimeRate()} PLN",
                     'suma po przeliczeniu' => "{$salary} PLN"
                 ]
            ]);
        } catch (ValidationException $e) {
            return $this->validationErrorResponse($e);
        }
    }

    private function validateRequest(Request $request, string $dateFormat)
    {
        return $request->validate([
            'employee_id' => 'required|uuid|exists:employees,id',
            'date' => "required|date_format:{$dateFormat}",
        ]);
    }

    private function calculateTotalHours($workTimes): float
    {
        return array_reduce($workTimes->toArray(), function ($carry, $workTime) {
            return $carry + round(Carbon::parse($workTime['start_time'])->diffInMinutes(Carbon::parse($workTime['end_time'])) / 30) * 0.5;
        }, 0);
    }

    private function calculateSalary(float $totalHours): float
    {
        return $this->getRate() * $totalHours;
    }

    private function calculateMonthlyHours($workTimes): array
    {
        $totalHours = $this->calculateTotalHours($workTimes);
        return [$this->getNormalHours($totalHours), max(0, $totalHours - WorkTimeConfig::getInstance()->getConfig()['monthly_norm_hours'])];
    }

    private function calculateMonthlySalary(float $normalHours, float $overtimeHours): float
    {
        return ($normalHours * $this->getRate()) + ($overtimeHours * ($this->getOvertimeRate()));
    }

    private function getNormalHours(float $totalHours): float
    {
        return min(WorkTimeConfig::getInstance()->getConfig()['monthly_norm_hours'], $totalHours);
    }

    private function getRate(): float
    {
        return WorkTimeConfig::getInstance()->getConfig()['rate'];
    }

    private function getOvertimeRate(): float
    {
        return ($this->getRate() * (WorkTimeConfig::getInstance()->getConfig()['overtime_rate']/100));
    }

   private function errorResponse(string $message, int $statusCode)
   {
       return response()->json(['error' => "{$message}"], "{$statusCode}");
   }

   private function validationErrorResponse(ValidationException $e)
   {
       return response()->json(['errors' => $e->errors()], 422);
   }
}