<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkTime extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'start_time',
        'end_time',
        'start_day',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
