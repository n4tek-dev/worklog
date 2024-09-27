<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'first_name',
        'last_name',
    ];

    public $incrementing = false;
    protected $keyType = 'string';

    public function workTimes()
    {
        return $this->hasMany(WorkTime::class);
    }
}
