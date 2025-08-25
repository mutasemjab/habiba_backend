<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'roles_list', 'roles_create', 'roles_edit', 'roles_delete',
            'users_list', 'users_create', 'users_edit', 'users_delete',
            'clients_list', 'clients_create', 'clients_edit', 'clients_delete',
            'categories_list', 'categories_create', 'categories_edit', 'categories_delete', // Fixed typo here
            'sub_categories_list', 'sub_categories_create', 'sub_categories_edit', 'sub_categories_delete', // Fixed typo here
            'brands_list', 'brands_create', 'brands_edit', 'brands_delete',
            'offers_list', 'offers_create', 'offers_edit', 'offers_delete',
            'products_list', 'products_create', 'products_edit', 'products_delete',
            'drivers_list', 'drivers_create', 'drivers_edit', 'drivers_delete',
            'orders_list', 'orders_create', 'orders_edit', 'orders_delete', 'orders_assign', 'orders_change_status',
            'slider_images_list', 'slider_images_create', 'slider_images_edit', 'slider_images_delete',
            'coupons_list', 'coupons_create', 'coupons_edit', 'coupons_delete',
            'store_settings_list', 'contact_requests_list','app_ratings_list', 'account_deletion_list', 'empty_driver_petty_cash',
            'branches_list', 'branches_create', 'branches_edit', 'branches_delete',
            'global_notifications_create','client_notifications_create',
        ];
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }
}
