<?php

use App\Module;
use App\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Dashboard
        $moduleAppDashboard = Module::updateOrCreate(['name' => 'Admin Dashboard']);
        Permission::updateOrCreate([
            'module_id' => $moduleAppDashboard->id,
            'name' => 'Access Dashboard',
            'slug' => 'app.dashboard',
        ]);

        // Role management
        $moduleAppRole = Module::updateOrCreate(['name' => 'Role Management']);
        Permission::updateOrCreate([
            'module_id' => $moduleAppRole->id,
            'name' => 'Access Roles',
            'slug' => 'app.roles.index',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleAppRole->id,
            'name' => 'Create Role',
            'slug' => 'app.roles.create',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleAppRole->id,
            'name' => 'Edit Role',
            'slug' => 'app.roles.edit',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleAppRole->id,
            'name' => 'Delete Role',
            'slug' => 'app.roles.destroy',
        ]);

        // User management
        $moduleAppUser = Module::updateOrCreate(['name' => 'User Management']);
        Permission::updateOrCreate([
            'module_id' => $moduleAppUser->id,
            'name' => 'Access Users',
            'slug' => 'app.users.index',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleAppUser->id,
            'name' => 'Create User',
            'slug' => 'app.users.create',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleAppUser->id,
            'name' => 'Edit User',
            'slug' => 'app.users.edit',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleAppUser->id,
            'name' => 'Delete User',
            'slug' => 'app.users.destroy',
        ]);

        // Mapping
        $mapping = Module::updateOrCreate(['name' => 'Mapping']);
        Permission::updateOrCreate([
            'module_id' => $mapping->id,
            'name' => 'Access Mapping',
            'slug' => 'app.mapping.index',
        ]);
        Permission::updateOrCreate([
            'module_id' => $mapping->id,
            'name' => 'Create Mapping',
            'slug' => 'app.mapping.create',
        ]);
        Permission::updateOrCreate([
            'module_id' => $mapping->id,
            'name' => 'Edit Mapping',
            'slug' => 'app.mapping.edit',
        ]);
        Permission::updateOrCreate([
            'module_id' => $mapping->id,
            'name' => 'Delete Mapping',
            'slug' => 'app.mapping.destroy',
        ]);

        // Project Details
        $project = Module::updateOrCreate(['name' => 'Project']);
        Permission::updateOrCreate([
            'module_id' => $project->id,
            'name' => 'Access Project',
            'slug' => 'app.project.index',
        ]);
        Permission::updateOrCreate([
            'module_id' => $project->id,
            'name' => 'Create Project',
            'slug' => 'app.project.create',
        ]);
        Permission::updateOrCreate([
            'module_id' => $project->id,
            'name' => 'Edit Project',
            'slug' => 'app.project.edit',
        ]);
        Permission::updateOrCreate([
            'module_id' => $project->id,
            'name' => 'Delete Project',
            'slug' => 'app.project.destroy',
        ]);

        // Bank Details
        $bank = Module::updateOrCreate(['name' => 'Bank Details']);
        Permission::updateOrCreate([
            'module_id' => $bank->id,
            'name' => 'Access Bank Details',
            'slug' => 'app.bank_details.index',
        ]);
        Permission::updateOrCreate([
            'module_id' => $bank->id,
            'name' => 'Create Bank Details',
            'slug' => 'app.bank_details.create',
        ]);
        Permission::updateOrCreate([
            'module_id' => $bank->id,
            'name' => 'Edit Bank Details',
            'slug' => 'app.bank_details.edit',
        ]);
        Permission::updateOrCreate([
            'module_id' => $bank->id,
            'name' => 'Delete Bank Details',
            'slug' => 'app.bank_details.destroy',
        ]);

        // Master Account
        $master_account = Module::updateOrCreate(['name' => 'Master Account']);
        Permission::updateOrCreate([
            'module_id' => $master_account->id,
            'name' => 'Access master account',
            'slug' => 'app.master_account.index',
        ]);
        Permission::updateOrCreate([
            'module_id' => $master_account->id,
            'name' => 'Create master account',
            'slug' => 'app.master_account.create',
        ]);
        Permission::updateOrCreate([
            'module_id' => $master_account->id,
            'name' => 'Edit master account',
            'slug' => 'app.master_account.edit',
        ]);
        Permission::updateOrCreate([
            'module_id' => $master_account->id,
            'name' => 'Delete master account',
            'slug' => 'app.master_account.destroy',
        ]);

        // Account Head
        $account_head = Module::updateOrCreate(['name' => 'Account Head']);
        Permission::updateOrCreate([
            'module_id' => $account_head->id,
            'name' => 'Access account head',
            'slug' => 'app.account_head.index',
        ]);
        Permission::updateOrCreate([
            'module_id' => $account_head->id,
            'name' => 'Create account head',
            'slug' => 'app.account_head.create',
        ]);
        Permission::updateOrCreate([
            'module_id' => $account_head->id,
            'name' => 'Edit account head',
            'slug' => 'app.account_head.edit',
        ]);
        Permission::updateOrCreate([
            'module_id' => $account_head->id,
            'name' => 'Delete account head',
            'slug' => 'app.account_head.destroy',
        ]);

        // Cost Center
        $cost_center = Module::updateOrCreate(['name' => 'Cost Center']);
        Permission::updateOrCreate([
            'module_id' => $cost_center->id,
            'name' => 'Access cost center',
            'slug' => 'app.cost_center.index',
        ]);
        Permission::updateOrCreate([
            'module_id' => $cost_center->id,
            'name' => 'Create cost center',
            'slug' => 'app.cost_center.create',
        ]);
        Permission::updateOrCreate([
            'module_id' => $cost_center->id,
            'name' => 'Edit cost center',
            'slug' => 'app.cost_center.edit',
        ]);
        Permission::updateOrCreate([
            'module_id' => $cost_center->id,
            'name' => 'Delete cost center',
            'slug' => 'app.cost_center.destroy',
        ]);

        // Profit Center
        $profit_center = Module::updateOrCreate(['name' => 'Profit Center']);
        Permission::updateOrCreate([
            'module_id' => $profit_center->id,
            'name' => 'Access profit center',
            'slug' => 'app.profit_center.index',
        ]);
        Permission::updateOrCreate([
            'module_id' => $profit_center->id,
            'name' => 'Create profit center',
            'slug' => 'app.profit_center.create',
        ]);
        Permission::updateOrCreate([
            'module_id' => $profit_center->id,
            'name' => 'Edit profit center',
            'slug' => 'app.profit_center.edit',
        ]);
        Permission::updateOrCreate([
            'module_id' => $profit_center->id,
            'name' => 'Delete profit center',
            'slug' => 'app.profit_center.destroy',
        ]);

        // Party Info
        $party_info = Module::updateOrCreate(['name' => 'Party Info']);
        Permission::updateOrCreate([
            'module_id' => $party_info->id,
            'name' => 'Access party info',
            'slug' => 'app.party_info.index',
        ]);
        Permission::updateOrCreate([
            'module_id' => $party_info->id,
            'name' => 'Create party info',
            'slug' => 'app.party_info.create',
        ]);
        Permission::updateOrCreate([
            'module_id' => $party_info->id,
            'name' => 'Edit party info',
            'slug' => 'app.party_info.edit',
        ]);
        Permission::updateOrCreate([
            'module_id' => $party_info->id,
            'name' => 'Delete party info',
            'slug' => 'app.party_info.destroy',
        ]);

        // Journal
        $journal = Module::updateOrCreate(['name' => 'Journal']);
        Permission::updateOrCreate([
            'module_id' => $journal->id,
            'name' => 'Journal Entry',
            'slug' => 'app.journal_entry',
        ]);
        Permission::updateOrCreate([
            'module_id' => $journal->id,
            'name' => 'Journal Authorize',
            'slug' => 'app.journal_authorize',
        ]);
        Permission::updateOrCreate([
            'module_id' => $journal->id,
            'name' => 'Journal approval',
            'slug' => 'app.journal_approval',
        ]);
        //  work start by mominul
        // Requisition
        $requisition = Module::updateOrCreate(['name' => 'Purchase Requisition']);
        Permission::updateOrCreate([
            'module_id' => $requisition->id,
            'name' => 'Requisition Entry',
            'slug' => 'app.requisition_entry',
        ]);
        Permission::updateOrCreate([
            'module_id' => $requisition->id,
            'name' => 'Requisition Authorize',
            'slug' => 'app.requisition_authorize',
        ]);
        Permission::updateOrCreate([
            'module_id' => $requisition->id,
            'name' => 'Requisition approval',
            'slug' => 'app.requisition_approval',
        ]);
        // PO Generation
        $requisition = Module::updateOrCreate(['name' => 'PO Generation']);
        Permission::updateOrCreate([
            'module_id' => $requisition->id,
            'name' => 'PO Generation Entry',
            'slug' => 'app.po_generation_entry',
        ]);
        Permission::updateOrCreate([
            'module_id' => $requisition->id,
            'name' => 'PO Generation Approval',
            'slug' => 'app.po_generation_approval',
        ]);
        
        // purchase return
        $p_return = Module::updateOrCreate(['name' => 'Purchase Return']);
        Permission::updateOrCreate([
            'module_id' => $p_return->id,
            'name' => 'Purchase Return Entry',
            'slug' => 'app.purchase_return_entry',
        ]);
        Permission::updateOrCreate([
            'module_id' => $p_return->id,
            'name' => 'Purchase Return Authorize',
            'slug' => 'app.purchase_return_authorize',
        ]);
        Permission::updateOrCreate([
            'module_id' => $p_return->id,
            'name' => 'Purchase Return Approval',
            'slug' => 'app.purchase_return_approval',
        ]);        
        // Payment Voucher
        $payment_voucher = Module::updateOrCreate(['name' => 'Payment Voucher']);
        Permission::updateOrCreate([
            'module_id' => $payment_voucher->id,
            'name' => 'Payment Voucher Entry',
            'slug' => 'app.payment_voucher_entry',
        ]);
        Permission::updateOrCreate([
            'module_id' => $payment_voucher->id,
            'name' => 'Payment Voucher Authorize',
            'slug' => 'app.payment_voucher_authorize',
        ]);
        Permission::updateOrCreate([
            'module_id' => $payment_voucher->id,
            'name' => 'Payment Voucher Approval',
            'slug' => 'app.payment_voucher_approval',
        ]);


        //  work end by mominul
    }
}
