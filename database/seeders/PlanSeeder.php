<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('plans')->insert([
            [
                'id' => 1,
                'type' => env('IS_RECURRING', 'RECURRING'),
                'name' => env('PLAN_NAME_1', "Starter Plan"),
                'price' => env('PLAN_PRICE_1', 0.00),
                'interval' => 'EVERY_30_DAYS',
                'capped_amount' => 1000,
                'terms' => env('PLAN_TERM_1', "Includes all features. Additional charge of $0.25 per member per month will be added to your monthly charge"),
                'trial_days' => env('TRIAL_DAY', 0),
                'test' => env('TEST_MODE', 1),
                'on_install' => env('ON_INSTALL', 1),
                'transaction_fee' => 0.0025,
            ],
            [
                'id' => 2,
                'type' => env('IS_RECURRING', 'RECURRING'),
                'name' => env('PLAN_NAME_1', "Growth Plan"),
                'price' => env('PLAN_PRICE_1', 19.99),
                'interval' => 'EVERY_30_DAYS',
                'capped_amount' => 500.00,
                'terms' => env('PLAN_TERM_1', "Includes all features. Additional charge of $0.15 per member per month will be added to your monthly charge."),
                'trial_days' => env('TRIAL_DAY', 0),
                'test' => env('TEST_MODE', 1),
                'on_install' => env('ON_INSTALL', 1),
                'transaction_fee' => 0.0015
            ],
            [
                'id' => 3,
                'type' => env('IS_RECURRING', 'RECURRING'),
                'name' => env('PLAN_NAME_1', "Enterprise Plan"),
                'price' => env('PLAN_PRICE_1', 299.99),
                'interval' => 'EVERY_30_DAYS',
                'capped_amount' => 1000.00,
                'terms' => env('PLAN_TERM_1', "Includes all features. Additional charge of $0.05 per member per month will be added to your monthly charge."),
                'trial_days' => env('TRIAL_DAY', 0),
                'test' => env('TEST_MODE', 1),
                'on_install' => env('ON_INSTALL', 1),
                'transaction_fee' => 0.0005
            ],
        ]);
    }
}
