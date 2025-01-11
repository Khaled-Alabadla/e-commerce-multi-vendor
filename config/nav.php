<?php

return [
    [
        'icon' => 'nav-icon fas fa-tachometer-alt',
        'route' => 'dashboard.index',
        'label' => 'Dashboard',
        'active' => 'dashboard.index',
    ],
    [
        'icon' => 'fas fa-tags nav-icon',
        'route' => 'dashboard.categories.index',
        'label' => 'Categories',
        'badge' => 'New',
        'active' => 'dashboard.categories.*',
        'ability' => 'categories.view',
    ],
    [
        'icon' => 'fas fa-box nav-icon',
        'route' => 'dashboard.products.index',
        'label' => 'Products',
        'active' => 'dashboard.products.*',
        'ability' => 'products.view',
    ],
    [
        'icon' => 'fas fa-receipt nav-icon',
        'route' => 'dashboard.categories.index',
        'label' => 'Orders',
        'active' => 'dashboard.orders.*',
        'ability' => 'orders.view',
    ],
    [
        'icon' => 'fas fa-receipt nav-icon',
        'route' => 'dashboard.roles.index',
        'label' => 'Roles',
        'active' => 'dashboard.roles.*',
        'ability' => 'roles.view',
    ],
    // [
    //     'icon' => 'fas fa-users nav-icon',
    //     // 'route' => 'dashboard.users.index',
    //     'label' => 'Users',
    //     'active' => 'dashboard.users.*',
    //     'ability' => 'users.view',
    // ],
    // [
    //     'icon' => 'fas fa-users nav-icon',
    //     // 'route' => 'dashboard.admins.index',
    //     'label' => 'Admins',
    //     'active' => 'dashboard.admins.*',
    //     'ability' => 'admins.view',
    // ],
];
