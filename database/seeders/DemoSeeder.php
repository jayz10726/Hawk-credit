<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{User, Organization, CreditScore};
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        // ── 1. Create a demo organization ──────────────────────────
        $org = Organization::firstOrCreate(
            ['email' => 'info@acmesacco.com'],
            [
                'uuid'                  => (string) Str::uuid(),
                'name'                  => 'Acme SACCO',
                'slug'                  => 'acme-sacco',
                'phone'                 => '+254700000001',
                'address'               => 'Nairobi, Kenya',
                'status'                => 'active',
                'subscription_tier'     => 'professional',
                'credit_pool'           => 5000000,
                'available_credit_pool' => 5000000,
            ]
        );
        $this->command->info("Org created: {$org->name} (id={$org->id})");

        // ── 2. Create Org Admin ─────────────────────────────────────
        $admin = User::firstOrCreate(
            ['email' => 'orgadmin@acmesacco.com'],
            [
                'uuid'            => (string) Str::uuid(),
                'first_name'      => 'Jane',
                'last_name'       => 'Wanjiku',
                'password'        => Hash::make('Password@123'),
                'organization_id' => $org->id,
                'phone'           => '+254700000002',
                'is_active'       => true,
            ]
        );
        $admin->assignRole('org_admin');
        $this->command->info("Org Admin: orgadmin@acmesacco.com / Password@123");

        // ── 3. Create Normal Users ──────────────────────────────────
        $users_data = [
            ['John',  'Kamau',  'john@acmesacco.com',  35000, 700],
            ['Mary',  'Njeri',  'mary@acmesacco.com',  50000, 620],
            ['Peter', 'Otieno', 'peter@acmesacco.com', 28000, 580],
        ];

        foreach ($users_data as [$fn, $ln, $email, $income, $score_val]) {
            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'uuid'              => (string) Str::uuid(),
                    'first_name'        => $fn,
                    'last_name'         => $ln,
                    'password'          => Hash::make('Password@123'),
                    'organization_id'   => $org->id,
                    'monthly_income'    => $income,
                    'employment_status' => 'employed',
                    'is_active'         => true,
                ]
            );
            $user->assignRole('user');

            CreditScore::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'organization_id'  => $org->id,
                    'score'            => $score_val,
                    'band'             => $score_val >= 660 ? 'good' : ($score_val >= 600 ? 'fair' : 'poor'),
                    'risk_category'    => $score_val >= 660 ? 'low' : 'medium',
                    'credit_limit'     => $score_val >= 660 ? 200000 : 100000,
                    'available_credit' => $score_val >= 660 ? 200000 : 100000,
                    'total_borrowed'   => 0,
                    'total_repaid'     => 0,
                    'on_time_payments' => rand(5, 20),
                    'late_payments'    => rand(0, 3),
                    'missed_payments'  => 0,
                ]
            );
            $this->command->info("User: {$email} / Password@123 (score: {$score_val})");
        }
    }
}