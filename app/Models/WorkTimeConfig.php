<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkTimeConfig extends Model
{
    private static ?WorkTimeConfig $instance = null;
    private array $config;

    private function __construct(array $config)
    {
        $this->config = $config;
    }

    public static function getInstance(array $config = []): WorkTimeConfig
    {
        if (self::$instance === null) {
            $defaultConfig = [
                'rate' => 20,
                'overtime_rate' => 200,
                'monthly_norm_hours' => 40
            ];
            self::$instance = new WorkTimeConfig(array_merge($defaultConfig, $config));
        }
        return self::$instance;
    }

    public function getConfig(): array
    {
        return $this->config;
    }
}
