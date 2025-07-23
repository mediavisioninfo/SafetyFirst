<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DefaultDataUsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Default All Permission
        $allPermission = [
            [
                'name' => 'manage user',
                'guard_name' => 'web',
            ],
            [
                'name' => 'create user',
                'guard_name' => 'web',
            ],
            [
                'name' => 'edit user',
                'guard_name' => 'web',
            ],
            [
                'name' => 'delete user',
                'guard_name' => 'web',
            ],
            [
                'name' => 'manage role',
                'guard_name' => 'web',
            ],
            [
                'name' => 'create role',
                'guard_name' => 'web',
            ],
            [
                'name' => 'edit role',
                'guard_name' => 'web',
            ],
            [
                'name' => 'delete role',
                'guard_name' => 'web',
            ],
            [
                'name' => 'manage contact',
                'guard_name' => 'web',
            ],
            [
                'name' => 'create contact',
                'guard_name' => 'web',
            ],
            [
                'name' => 'edit contact',
                'guard_name' => 'web',
            ],
            [
                'name' => 'delete contact',
                'guard_name' => 'web',
            ],
            [
                'name' => 'manage note',
                'guard_name' => 'web',
            ],
            [
                'name' => 'create note',
                'guard_name' => 'web',
            ],
            [
                'name' => 'edit note',
                'guard_name' => 'web',
            ],
            [
                'name' => 'delete note',
                'guard_name' => 'web',
            ],
            [
                'name' => 'manage logged history',
                'guard_name' => 'web',

            ],
            [
                'name' => 'delete logged history',
                'guard_name' => 'web',

            ],
            [
                'name' => 'manage pricing packages',
                'guard_name' => 'web',
            ],
            [
                'name' => 'create pricing packages',
                'guard_name' => 'web',
            ],
            [
                'name' => 'edit pricing packages',
                'guard_name' => 'web',
            ],
            [
                'name' => 'delete pricing packages',
                'guard_name' => 'web',
            ],

            [
                'name' => 'buy pricing packages',
                'guard_name' => 'web',
            ],
            [
                'name' => 'manage pricing transation',
                'guard_name' => 'web',
            ],
            [
                'name' => 'manage coupon',
                'guard_name' => 'web',
            ],
            [
                'name' => 'create coupon',
                'guard_name' => 'web',
            ],
            [
                'name' => 'edit coupon',
                'guard_name' => 'web',
            ],
            [
                'name' => 'delete coupon',
                'guard_name' => 'web',
            ],
            [
                'name' => 'manage coupon history',
                'guard_name' => 'web',
            ],
            [
                'name' => 'delete coupon history',
                'guard_name' => 'web',
            ],

            [
                'name' => 'manage account settings',
                'guard_name' => 'web',

            ],
            [
                'name' => 'manage password settings',
                'guard_name' => 'web',
            ],
            [
                'name' => 'manage general settings',
                'guard_name' => 'web',

            ],
            [
                'name' => 'manage company settings',
                'guard_name' => 'web',
            ],
            [
                'name' => 'manage email settings',
                'guard_name' => 'web',
            ],
            [
                'name' => 'manage payment settings',
                'guard_name' => 'web',
            ],
            [
                'name' => 'manage seo settings',
                'guard_name' => 'web',
            ],
            [
                'name' => 'manage google recaptcha settings',
                'guard_name' => 'web',
            ],

            [
                'name'=>'manage policy type',
                'guard_name'=>'web'
            ],
            [
                'name'=>'create policy type',
                'guard_name'=>'web'
            ],
            [
                'name'=>'edit policy type',
                'guard_name'=>'web'
            ],
            [
                'name'=>'delete policy type',
                'guard_name'=>'web'
            ],
            [
                'name'=>'manage policy sub type',
                'guard_name'=>'web'
            ],
            [
                'name'=>'create policy sub type',
                'guard_name'=>'web'
            ],
            [
                'name'=>'edit policy sub type',
                'guard_name'=>'web'
            ],
            [
                'name'=>'delete policy sub type',
                'guard_name'=>'web'
            ],
            [
                'name'=>'manage policy duration',
                'guard_name'=>'web'
            ],
            [
                'name'=>'create policy duration',
                'guard_name'=>'web'
            ],
            [
                'name'=>'edit policy duration',
                'guard_name'=>'web'
            ],
            [
                'name'=>'delete policy duration',
                'guard_name'=>'web'
            ],
            [
                'name'=>'manage policy for',
                'guard_name'=>'web'
            ],
            [
                'name'=>'create policy for',
                'guard_name'=>'web'
            ],
            [
                'name'=>'edit policy for',
                'guard_name'=>'web'
            ],
            [
                'name'=>'delete policy for',
                'guard_name'=>'web'
            ],
            [
                'name'=>'manage document type',
                'guard_name'=>'web'
            ],
            [
                'name'=>'create document type',
                'guard_name'=>'web'
            ],
            [
                'name'=>'edit document type',
                'guard_name'=>'web'
            ],
            [
                'name'=>'delete document type',
                'guard_name'=>'web'
            ],
            [
                'name'=>'manage policy',
                'guard_name'=>'web'
            ],
            [
                'name'=>'create policy',
                'guard_name'=>'web'
            ],
            [
                'name'=>'edit policy',
                'guard_name'=>'web'
            ],
            [
                'name'=>'delete policy',
                'guard_name'=>'web'
            ],
            [
                'name'=>'show policy',
                'guard_name'=>'web'
            ],
            [
                'name'=>'manage customer',
                'guard_name'=>'web'
            ],
            [
                'name'=>'create customer',
                'guard_name'=>'web'
            ],
            [
                'name'=>'show customer',
                'guard_name'=>'web'
            ],
            [
                'name'=>'manage agent',
                'guard_name'=>'web'
            ],
            [
                'name'=>'create agent',
                'guard_name'=>'web'
            ],
            [
                'name'=>'edit agent',
                'guard_name'=>'web'
            ],
            [
                'name'=>'delete agent',
                'guard_name'=>'web'
            ],
            [
                'name'=>'show agent',
                'guard_name'=>'web'
            ],
            [
                'name'=>'edit customer',
                'guard_name'=>'web'
            ],
            [
                'name'=>'delete customer',
                'guard_name'=>'web'
            ],
            [
                'name'=>'manage insurance',
                'guard_name'=>'web'
            ],
            [
                'name'=>'create insurance',
                'guard_name'=>'web'
            ],
            [
                'name'=>'edit insurance',
                'guard_name'=>'web'
            ],
            [
                'name'=>'delete insurance',
                'guard_name'=>'web'
            ],
            [
                'name'=>'show insurance',
                'guard_name'=>'web'
            ],
            [
                'name'=>'create insured detail',
                'guard_name'=>'web'
            ],
            [
                'name'=>'delete insured detail',
                'guard_name'=>'web'
            ],
            [
                'name'=>'create nominee',
                'guard_name'=>'web'
            ],
            [
                'name'=>'delete nominee',
                'guard_name'=>'web'
            ],
            [
                'name'=>'create document',
                'guard_name'=>'web'
            ],
            [
                'name'=>'delete document',
                'guard_name'=>'web'
            ],
            [
                'name'=>'manage payment',
                'guard_name'=>'web'
            ],
            [
                'name'=>'create payment',
                'guard_name'=>'web'
            ],
            [
                'name'=>'delete payment',
                'guard_name'=>'web'
            ],
            [
                'name'=>'manage claim',
                'guard_name'=>'web'
            ],
            [
                'name'=>'create claim',
                'guard_name'=>'web'
            ],
            [
                'name'=>'edit claim',
                'guard_name'=>'web'
            ],
            [
                'name'=>'delete claim',
                'guard_name'=>'web'
            ],
            [
                'name'=>'show claim',
                'guard_name'=>'web'
            ],

            [
                'name'=>'manage tax',
                'guard_name'=>'web'
            ],
            [
                'name'=>'create tax',
                'guard_name'=>'web'
            ],
            [
                'name'=>'edit tax',
                'guard_name'=>'web'
            ],
            [
                'name'=>'delete tax',
                'guard_name'=>'web'
            ],

        ];
        Permission::insert($allPermission);

        // Default Super Admin Role
        $superAdminRoleData = [
            'name' => 'super admin',
            'parent_id' => 0,
        ];
        $systemSuperAdminRole = Role::create($superAdminRoleData);
        $systemSuperAdminPermission = [
            ['name' => 'manage user'],
            ['name' => 'create user'],
            ['name' => 'edit user'],
            ['name' => 'delete user'],
            ['name' => 'manage contact'],
            ['name' => 'create contact'],
            ['name' => 'edit contact'],
            ['name' => 'delete contact'],
            ['name' => 'manage note'],
            ['name' => 'create note'],
            ['name' => 'edit note'],
            ['name' => 'delete note'],
            ['name' => 'manage pricing packages'],
            ['name' => 'create pricing packages'],
            ['name' => 'edit pricing packages'],
            ['name' => 'delete pricing packages'],
            ['name' => 'manage pricing transation'],
            ['name' => 'manage coupon'],
            ['name' => 'create coupon'],
            ['name' => 'edit coupon'],
            ['name' => 'delete coupon'],
            ['name' => 'manage coupon history'],
            ['name' => 'delete coupon history'],
            ['name' => 'manage account settings'],
            ['name' => 'manage password settings'],
            ['name' => 'manage general settings'],
            ['name' => 'manage email settings'],
            ['name' => 'manage payment settings'],
            ['name' => 'manage seo settings'],
            ['name' => 'manage google recaptcha settings'],


        ];
        $systemSuperAdminRole->givePermissionTo($systemSuperAdminPermission);
        // Default Super Admin
        $superAdminData = [
            'name' => 'Super Admin',
            'email' => 'superadmin@gmail.com',
            'password' => Hash::make('123456'),
            'type' => 'super admin',
            'lang' => 'english',
            'profile' => 'avatar.png',
        ];
        $systemSuperAdmin = User::create($superAdminData);
        $systemSuperAdmin->assignRole($systemSuperAdminRole);

        // Default Owner Role
        $ownerRoleData = [
            'name' => 'owner',
            'parent_id' => $systemSuperAdmin->id,
        ];
        $systemOwnerRole = Role::create($ownerRoleData);

        // Default Owner All Permissions
        $systemOwnerPermission = [
            ['name' => 'manage user'],
            ['name' => 'create user'],
            ['name' => 'edit user'],
            ['name' => 'delete user'],
            ['name' => 'manage role'],
            ['name' => 'create role'],
            ['name' => 'edit role'],
            ['name' => 'delete role'],
            ['name' => 'manage contact'],
            ['name' => 'create contact'],
            ['name' => 'edit contact'],
            ['name' => 'delete contact'],
            ['name' => 'manage note'],
            ['name' => 'create note'],
            ['name' => 'edit note'],
            ['name' => 'delete note'],
            ['name' => 'manage logged history'],
            ['name' => 'delete logged history'],
            ['name' => 'manage pricing packages'],
            ['name' => 'buy pricing packages'],
            ['name' => 'manage pricing transation'],
            ['name' => 'manage account settings'],
            ['name' => 'manage password settings'],
            ['name' => 'manage general settings'],
            ['name' => 'manage company settings'],
            ['name' => 'manage email settings'],

            ['name'=>'manage policy type'],
            ['name'=>'create policy type'],
            ['name'=>'edit policy type'],
            ['name'=>'delete policy type'],
            ['name'=>'manage policy sub type'],
            ['name'=>'create policy sub type'],
            ['name'=>'edit policy sub type'],
            ['name'=>'delete policy sub type'],
            ['name'=>'manage policy duration'],
            ['name'=>'create policy duration'],
            ['name'=>'edit policy duration'],
            ['name'=>'delete policy duration'],
            ['name'=>'manage policy for'],
            ['name'=>'create policy for'],
            ['name'=>'edit policy for'],
            ['name'=>'delete policy for'],
            ['name'=>'manage document type'],
            ['name'=>'create document type'],
            ['name'=>'edit document type'],
            ['name'=>'delete document type'],
            ['name'=>'manage policy'],
            ['name'=>'create policy'],
            ['name'=>'edit policy'],
            ['name'=>'delete policy'],
            ['name'=>'show policy'],
            ['name'=>'manage customer'],
            ['name'=>'create customer'],
            ['name'=>'show customer'],
            ['name'=>'manage agent'],
            ['name'=>'create agent'],
            ['name'=>'edit agent'],
            ['name'=>'delete agent'],
            ['name'=>'show agent'],
            ['name'=>'edit customer'],
            ['name'=>'delete customer'],
            ['name'=>'manage insurance'],
            ['name'=>'create insurance'],
            ['name'=>'edit insurance'],
            ['name'=>'delete insurance'],
            ['name'=>'show insurance'],
            ['name'=>'create insured detail'],
            ['name'=>'delete insured detail'],
            ['name'=>'create nominee'],
            ['name'=>'delete nominee'],
            ['name'=>'create document'],
            ['name'=>'delete document'],
            ['name'=>'manage payment'],
            ['name'=>'create payment'],
            ['name'=>'delete payment'],
            ['name'=>'manage claim'],
            ['name'=>'create claim'],
            ['name'=>'edit claim'],
            ['name'=>'delete claim'],
            ['name'=>'show claim'],
            ['name'=>'manage tax'],
            ['name'=>'create tax'],
            ['name'=>'edit tax'],
            ['name'=>'delete tax'],


        ];
        $systemOwnerRole->givePermissionTo($systemOwnerPermission);

        // Default Owner Create
        $ownerData = [
            'name' => 'Owner',
            'email' => 'owner@gmail.com',
            'password' => Hash::make('123456'),
            'type' => 'owner',
            'lang' => 'english',
            'profile' => 'avatar.png',
            'subscription' => 1,
            'parent_id' => $systemSuperAdmin->id,
        ];
        $systemOwner = User::create($ownerData);
        // Default Owner Role Assign
        $systemOwner->assignRole($systemOwnerRole);


        // Default Owner Role
        $managerRoleData = [
            'name' => 'manager',
            'parent_id' => $systemOwner->id,
        ];
        $systemManagerRole = Role::create($managerRoleData);
        // Default Manager All Permissions
        $systemManagerPermission = [
            ['name' => 'manage user'],
            ['name' => 'create user'],
            ['name' => 'edit user'],
            ['name' => 'delete user'],
            ['name' => 'manage contact'],
            ['name' => 'create contact'],
            ['name' => 'edit contact'],
            ['name' => 'delete contact'],
            ['name' => 'manage note'],
            ['name' => 'create note'],
            ['name' => 'edit note'],
            ['name' => 'delete note'],
            ['name'=>'manage policy type'],
            ['name'=>'create policy type'],
            ['name'=>'edit policy type'],
            ['name'=>'delete policy type'],
            ['name'=>'manage policy sub type'],
            ['name'=>'create policy sub type'],
            ['name'=>'edit policy sub type'],
            ['name'=>'delete policy sub type'],
            ['name'=>'manage policy duration'],
            ['name'=>'create policy duration'],
            ['name'=>'edit policy duration'],
            ['name'=>'delete policy duration'],
            ['name'=>'manage policy for'],
            ['name'=>'create policy for'],
            ['name'=>'edit policy for'],
            ['name'=>'delete policy for'],
            ['name'=>'manage document type'],
            ['name'=>'create document type'],
            ['name'=>'edit document type'],
            ['name'=>'delete document type'],
            ['name'=>'manage policy'],
            ['name'=>'create policy'],
            ['name'=>'edit policy'],
            ['name'=>'delete policy'],
            ['name'=>'show policy'],
            ['name'=>'manage customer'],
            ['name'=>'create customer'],
            ['name'=>'show customer'],
            ['name'=>'manage agent'],
            ['name'=>'create agent'],
            ['name'=>'edit agent'],
            ['name'=>'delete agent'],
            ['name'=>'show agent'],
            ['name'=>'edit customer'],
            ['name'=>'delete customer'],
            ['name'=>'manage insurance'],
            ['name'=>'create insurance'],
            ['name'=>'edit insurance'],
            ['name'=>'delete insurance'],
            ['name'=>'show insurance'],
            ['name'=>'create insured detail'],
            ['name'=>'delete insured detail'],
            ['name'=>'create nominee'],
            ['name'=>'delete nominee'],
            ['name'=>'create document'],
            ['name'=>'delete document'],
            ['name'=>'manage payment'],
            ['name'=>'create payment'],
            ['name'=>'delete payment'],
            ['name'=>'manage claim'],
            ['name'=>'create claim'],
            ['name'=>'edit claim'],
            ['name'=>'delete claim'],
            ['name'=>'show claim'],
            ['name'=>'manage tax'],
            ['name'=>'create tax'],
            ['name'=>'edit tax'],
            ['name'=>'delete tax'],


        ];
        $systemManagerRole->givePermissionTo($systemManagerPermission);
        // Default Manager Create
        $managerData = [
            'name' => 'Manager',
            'email' => 'manager@gmail.com',
            'password' => Hash::make('123456'),
            'type' => 'manager',
            'lang' => 'english',
            'profile' => 'avatar.png',
            'subscription' => 0,
            'parent_id' => $systemOwner->id,
        ];
        $systemManager = User::create($managerData);
        // Default Manager Role Assign
        $systemManager->assignRole($systemManagerRole);

        // Default Customer role
        defaultCustomerCreate($systemOwner->id);
        defaultAgentCreate($systemOwner->id);

        // Subscription default data
        $subscriptionData = [
            'title' => 'Basic',
            'package_amount' => 0,
            'interval' => 'Unlimited',
            'user_limit' => 10,
            'customer_limit' => 20,
            'agent_limit' => 20,
            'enabled_logged_history' => 1,
        ];
        \App\Models\Subscription::create($subscriptionData);
    }
}
