<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\{Role, Permission};

class RolesPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ── Define all permissions ────────────────────────────
        $permissions = [
            // Organizations
            'organization.create','organization.update','organization.delete','organization.view',
            // Users
            'user.create','user.update','user.delete','user.view','user.suspend',
            // Credit Requests
            'credit_request.create','credit_request.view','credit_request.approve','credit_request.reject',
            // Loans
            'loan.view','loan.disburse','loan.write_off',
            // Repayments
            'repayment.create','repayment.confirm','repayment.reverse',
            // Penalties
            'penalty.view','penalty.waive',
            // Analytics
            'analytics.view_org','analytics.view_global',
            // Settings
            'settings.manage','credit_rules.manage',
            // Audit Logs
            'audit_log.view',
        ];

        foreach ($permissions as $p) {
            Permission::firstOrCreate(['name' => $p, 'guard_name' => 'web']);
        }

        // ── Create roles and assign permissions ───────────────
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin']);
        $superAdmin->givePermissionTo(Permission::all());

        $orgAdmin = Role::firstOrCreate(['name' => 'org_admin']);
        $orgAdmin->givePermissionTo([
            'user.create','user.update','user.view','user.suspend',
            'credit_request.view','credit_request.approve','credit_request.reject',
            'loan.view','loan.disburse',
            'repayment.create','repayment.confirm',
            'penalty.view','penalty.waive',
            'analytics.view_org','audit_log.view',
        ]);

        $user = Role::firstOrCreate(['name' => 'user']);
        $user->givePermissionTo([
            'credit_request.create','credit_request.view',
            'loan.view','repayment.create',
        ]);
    }
}
