<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Services\SalaryCalculatorService;
use Carbon\Carbon;

class CalculateDailySalary extends Command
{
    protected $signature = 'salary:calculate-daily';
    protected $description = 'Calculate salary for all users for the previous day';

    public function handle(SalaryCalculatorService $calculator)
    {
        $date = Carbon::yesterday();
        $users = User::where('calculate_salary', 1)->get();

        foreach ($users as $user) {
            $salary = $calculator->calculate($user, $date);
            if ($salary) {
                $this->info("Salary calculated for {$user->name} ({$date->toDateString()})");
            }
        }

        return Command::SUCCESS;
    }
}
