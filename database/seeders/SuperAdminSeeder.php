<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
$admin = User::firstOrCreate(
            ['email' => 'admin@hawkscredits.com'],
            [
                'uuid'          => Str::uuid(),
                'first_name'    => 'Super',
                'last_name'     => 'Admin',
                'password'      => Hash::make('Admin@12345'),
                'organization_id' => null,  // Super admin has no org
                'is_active'     => true,
            ]
        );
        $admin->assignRole('super_admin');
    }
}
