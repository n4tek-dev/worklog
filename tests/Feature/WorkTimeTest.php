<?php

use App\Models\Employee;
use App\Models\WorkTime;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WorkTimeTest extends TestCase
{
    use RefreshDatabase;

    protected $employee;

    protected function setUp(): void
    {
        parent::setUp();
        $this->employee = Employee::factory()->create();
    }

    public function test_register_work_time()
    {
        $workTimeData = [
            'employee_id' => $this->employee->id,
            'start_time' => '1970-01-01 08:00:00',
            'end_time' => '1970-01-01 14:00:00',
            'start_day' => '1970-01-01',
        ];

        $response = $this->postJson('/api/work-times', $workTimeData);

        $response->assertStatus(200)
            ->assertJson(['response' => ['Czas pracy został dodany!']]);
    }

    public function test_summary_by_day()
    {
        WorkTime::factory()->create([
            'employee_id' => $this->employee->id,
            'start_time' => '1970-01-01 08:00:00',
            'end_time' => '1970-01-01 14:00:00',
            'start_day' => '1970-01-01',
        ]);

        $summaryDay = [
            'employee_id' => $this->employee->id,
            'date' => '1970-01-01',
        ];

        $response = $this->getJson('/api/work-times/summary/day?' . http_build_query($summaryDay));

        $response->assertStatus(200)
            ->assertJson([
                'response' => [
                    'suma po przeliczeniu' => "120 PLN",
                    'ilość godzin z danego dnia' => 6,
                    'stawka' => "20 PLN"
                ]
            ]);
    }

    public function test_summary_by_month()
    {
        WorkTime::factory()->create([
            'employee_id' => $this->employee->id,
            'start_time' => '1970-01-01 08:00:00',
            'end_time' => '1970-01-01 20:00:00',
            'start_day' => '1970-01-01',
        ]);

        WorkTime::factory()->create([
            'employee_id' => $this->employee->id,
            'start_time' => '1970-01-05 08:00:00',
            'end_time' => '1970-01-05 20:00:00',
            'start_day' => '1970-01-05',
        ]);

        WorkTime::factory()->create([
            'employee_id' => $this->employee->id,
            'start_time' => '1970-01-10 08:00:00',
            'end_time' => '1970-01-10 20:00:00',
            'start_day' => '1970-01-10',
        ]);

        WorkTime::factory()->create([
            'employee_id' => $this->employee->id,
            'start_time' => '1970-01-15 08:00:00',
            'end_time' => '1970-01-15 20:00:00',
            'start_day' => '1970-01-15',
        ]);

        $summaryMonth = [
            'employee_id' => $this->employee->id,
            'date' => '1970-01',
        ];

        $response = $this->getJson('/api/work-times/summary/month?' . http_build_query($summaryMonth));

        $response->assertStatus(200)
            ->assertJson([
                'response' => [
                    'ilość normalnych godzin z danego miesiąca' => 40,
                    'stawka' => "20 PLN",
                    'ilość nadgodzin z danego miesiąca' => 8,
                    'stawka nadgodzinowa' => "40 PLN",
                    'suma po przeliczeniu' => "1120 PLN"
                ]
            ]);
    }

    public function test_rounding_8_10_to_8_hours()
    {
        WorkTime::factory()->create([
            'employee_id' => $this->employee->id,
            'start_time' => '1970-01-01 08:00:00',
            'end_time' => '1970-01-01 16:10:00',
            'start_day' => '1970-01-01',
        ]);

        $summaryDay = [
            'employee_id' => $this->employee->id,
            'date' => '1970-01-01',
        ];

        $response = $this->getJson('/api/work-times/summary/day?' . http_build_query($summaryDay));

        $response->assertStatus(200)
            ->assertJson([
                'response' => [
                    'suma po przeliczeniu' => "160 PLN",
                    'ilość godzin z danego dnia' => 8,
                    'stawka' => "20 PLN"
                ]
            ]);
    }

    public function test_rounding_8_17_to_8_5_hours()
    {
        WorkTime::factory()->create([
            'employee_id' => $this->employee->id,
            'start_time' => '1970-01-05 08:00:00',
            'end_time' => '1970-01-05 16:17:00',
            'start_day' => '1970-01-05',
        ]);

        $summaryDay = [
            'employee_id' => $this->employee->id,
            'date' => '1970-01-05',
        ];

        $response = $this->getJson('/api/work-times/summary/day?' . http_build_query($summaryDay));

        $response->assertStatus(200)
            ->assertJson([
                'response' => [
                    'suma po przeliczeniu' => "170 PLN",
                    'ilość godzin z danego dnia' => 8.5,
                    'stawka' => "20 PLN"
                ]
            ]);
    }

    public function test_rounding_8_35_to_8_5_hours()
    {
        WorkTime::factory()->create([
            'employee_id' => $this->employee->id,
            'start_time' => '1970-01-10 08:00:00',
            'end_time' => '1970-01-10 16:35:00',
            'start_day' => '1970-01-10',
        ]);

        $summaryDay = [
            'employee_id' => $this->employee->id,
            'date' => '1970-01-10',
        ];

        $response = $this->getJson('/api/work-times/summary/day?' . http_build_query($summaryDay));

        $response->assertStatus(200)
            ->assertJson([
                'response' => [
                    'suma po przeliczeniu' => "170 PLN",
                    'ilość godzin z danego dnia' => 8.5,
                    'stawka' => "20 PLN"
                ]
            ]);
    }

    public function test_rounding_8_48_to_9_hours()
    {
        WorkTime::factory()->create([
            'employee_id' => $this->employee->id,
            'start_time' => '1970-01-15 08:00:00',
            'end_time' => '1970-01-15 16:48:00',
            'start_day' => '1970-01-15',
        ]);

        $summaryDay = [
            'employee_id' => $this->employee->id,
            'date' => '1970-01-15',
        ];

        $response = $this->getJson('/api/work-times/summary/day?' . http_build_query($summaryDay));

        $response->assertStatus(200)
            ->assertJson([
                'response' => [
                    'suma po przeliczeniu' => "180 PLN",
                    'ilość godzin z danego dnia' => 9,
                    'stawka' => "20 PLN"
                ]
            ]);
    }
}
