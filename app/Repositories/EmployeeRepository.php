<?php

namespace App\Repositories;

use App\Models\Employee;
use Illuminate\Support\Str;

class EmployeeRepository
{
    public function createEmployee(array $validated): Employee
    {
        return Employee::create([
            'id' => (string) Str::uuid(),
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
        ]);
    }
}