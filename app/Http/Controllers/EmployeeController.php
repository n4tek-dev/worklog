<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\EmployeeRepository;
use Illuminate\Http\JsonResponse;
use App\Models\Employee;

class EmployeeController extends Controller
{
    private $employeeRepository;

    public function __construct(EmployeeRepository $employeeRepository)
    {
        $this->employeeRepository = $employeeRepository;
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $this->validateEmployee($request);

        $employee = $this->employeeRepository->createEmployee($validated);

        return $this->sendResponse($employee);
    }

    private function validateEmployee(Request $request): array
    {
        return $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
        ]);
    }

    private function sendResponse(Employee $employee): JsonResponse
    {
        return response()->json(['response' => ['id' => $employee->id]]);
    }
}