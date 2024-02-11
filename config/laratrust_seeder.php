<?php

return [
    /**
     * Control if the seeder should create a user per role while seeding the data.
     */
    'create_users' => false,

    /**
     * Control if all the laratrust tables should be truncated before running the seeder.
     */
    'truncate_tables' => true,

    'roles_structure' => [
        'super_admin' => [
            'product' => 'c,r,u,d,i',
            'client' =>'c,r,u,d,rs,i',
            'document'=>'c,r,u,d,s,i',
            'bank'=>'c,r,u,d,i',
            'po'=>'c,r,u,d,rs,i',
            'user'=>'c,r,u,d,i',
            'company'=>'c,r,u,d,i',
            'country'=>'c,r,u,d,i',
            'city'=>'c,r,u,d'
        ],
        'admin' => [
            'product' => 'c,r,u,d,i',
            'client' =>'c,r,u,d,rs,i',
            'document'=>'c,r,u,d,s,i',
            'bank'=>'c,r,u,d,i',
            'po'=>'c,r,u,d,rs,i',
         ],
         'show_invoices' => [
            'document'=>'i',
         ],
         'normal' => [
            'product' => 'c,r,i',
            'client' =>'c,r,i',
            'document'=>'c,r,i',
            'bank'=>'c,r,i',
            'po'=>'c,r,i',
         ],
         'moderator ' => [
            'product' => 'c,r,u,d,i',
            'client' =>'c,r,u,d,rs,i',
            'document'=>'c,r,u,d,i',
            'bank'=>'c,r,u,d,i',
            'po'=>'c,r,u,d,rs,i',
         ],
    ],

    'permissions_map' => [
        'c' => 'create',
        'r' => 'read',
        'u' => 'update',
        'd' => 'delete',
        's' => 'send',
        'i'=>'invoice',
        'rs'=>'restore'
    ]
];
