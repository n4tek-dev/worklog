<?php

namespace App\Http\Controllers;

use App\Repositories\WorkTimeRepository;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class WorkTimeController extends Controller
{
    private $workTimeRepository;

    public function __construct(WorkTimeRepository $workTimeRepository)
    {
        $this->workTimeRepository = $workTimeRepository;
    }

    public function store(Request $request)
    {
        try {
            $validated = $this->validateRequest($request);

            $start = Carbon::parse($validated['start_time']);
            $end = Carbon::parse($validated['end_time']);

            if ($this->isWorkTimeExceedingLimit($start, $end)) {
                return $this->errorResponse('Przedział czasu pracy nie może przekraczać 12 godzin.');
            }

            if ($this->workTimeRepository->isDuplicateWorkTime($validated['employee_id'], $start)) {
                return $this->errorResponse('Pracownik może posiadać tylko jeden przedział z tym samym dniem rozpoczęcia.');
            }

            $this->workTimeRepository->createWorkTime($validated['employee_id'], $start, $end);

            return response()->json(['response' => ['Czas pracy został dodany!']]);
        } catch (ValidationException $e) {
            return $this->validationErrorResponse($e);
        }
    }

    private function validateRequest(Request $request)
    {
        return $request->validate([
            'employee_id' => 'required|uuid|exists:employees,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ]);
    }

    private function isWorkTimeExceedingLimit(Carbon $start, Carbon $end)
    {
        return $start->diffInHours($end) > 12;
    }

    private function errorResponse(string $message)
    {
        return response()->json(['error' => $message], 400);
    }

    private function validationErrorResponse(ValidationException $e)
    {
        return response()->json(['errors' => $e->errors()], 422);
    }
}
