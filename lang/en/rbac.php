<?php

return [
    'nav' => [
        'entity' => 'Navigation',
        'title' => [
            'list' => 'Navigation Management',
            'add' => 'Add Navigation',
            'edit' => 'Edit Navigation',
            'sort' => 'Sorting Navigation',
        ],
        'subtitle' => [
            'list' => 'Manage application navigations, organize structure, and configure navigation links.',
            'add' => 'Create a new navigation item by filling in the details below.',
            'edit' => 'Update the navigation details and configuration settings below.',
            'sort' => 'Arrange the menu items by dragging and dropping them into the desired order.',
        ],
        'filter' => [
            'ph_status' => 'Select Navigation Status',
            'lb_status' => 'Navigation Status',
            'ph_search' => 'Search by name',
            'lb_search' => 'Search',
        ],
        'table' => [
            'header_name' => 'Label Name',
            'header_controller' => 'Controller',
            'header_route' => 'Route',
            'header_url' => 'URL',
            'header_status' => 'Status',
            'header_actions' => 'Actions',
        ],
        'form' => [
            'lb' => [
                'name_en' => 'English label name',
                'name_pt' => 'Portuguese label name',
                'name_tl' => 'Tetun label name',
                'nav_parent' => 'Navigation Parent',
                'controller' => 'Controller Name',
                'route' => 'Route Name',
                'url' => 'URL',
                'icon' => 'Icon Navigation',
                'divider' => 'Divider Navigation',
                'active' => 'Active Navigation',
            ],
            'ph' => [
                'name_en' => 'English label of the navigation',
                'name_pt' => 'Portuguese label of the navigation',
                'name_tl' => 'Tetun label of the navigation',
                'nav_parent' => 'Select parent navigation',
                'controller' => 'The name of the controller that handles the navigation',
                'route' => 'The name of the route that handles the navigation',
                'url' => 'The url that handles the navigation',
                'icon' => 'Icon base from https://icon-sets.iconify.design/ri',
            ],
        ],
    ],
    'permission' => [
        'entity' => 'Permission',
        'title' => [
            'list' => 'Permission Management',
            'add' => 'Add Permission',
            'edit' => 'Edit Permission',
        ],
        'subtitle' => [
            'list' => 'View and organize permissions that control user access to features.',
            'add' => 'Permissions you may use and assign to your users.',
            'edit' => 'Update the permission details below.',
        ],
        'filter' => [
            'ph_group' => 'Select Permission Group',
            'lb_group' => 'Permission Group',
            'ph_search' => 'Search by name',
            'lb_search' => 'Search',
        ],
        'table' => [
            'header_no' => 'No',
            'header_name' => 'Permission Name',
            'header_group' => 'Group',
            'header_actions' => 'Actions',
        ],
        'form' => [
            'lb' => [
                'name' => 'Permission Name',
                'group' => 'Permission Group',
            ],
            'ph' => [
                'name' => 'Name of the permission',
                'group' => 'Group of the permission',
            ],
        ],
    ],
    'role' => [
        'entity' => 'Role',
        'title' => [
            'list' => 'Role Management',
            'add' => 'Add Role',
            'edit' => 'Edit Role',
        ],
        'subtitle' => [
            'list' => 'View, update, or remove roles and control access levels across the application.',
            'add' => 'Provide the role details and select the permissions it should have.',
            'edit' => 'Update the role details and modify access levels below.',
        ],
        'filter' => [
            'ph_search' => 'Search by name',
            'lb_search' => 'Search',
        ],
        'table' => [
            'header_no' => 'No',
            'header_name' => 'Role Name',
            'header_guard' => 'Guard',
            'header_actions' => 'Actions',
        ],
        'form' => [
            'lb' => [
                'name' => 'Role Name',
                'permissions' => 'Role Permissions',
                'menus' => 'Accessible Menus',
            ],
            'ph' => [
                'name' => 'Role name',
                'permissions' => 'Choose what this role is allowed to do in the system.',
                'menus' => 'Determine which menus this role can access.',
            ],
        ],
    ],
    'user' => [
        'entity' => 'User',
        'title' => [
            'list' => 'User Management',
            'add' => 'Add User',
            'edit' => 'Edit User',
        ],
        'subtitle' => [
            'list' => 'View, search, and manage all registered users within the system, including access rights and account status',
            'add' => 'Please complete the form to create a new system user.',
            'edit' => 'Modify user profile by completing the form below.',
        ],
        'filter' => [
            'ph_search' => 'Search by name, email, or username',
            'lb_search' => 'Search',
            'ph_status' => 'Select User Status',
            'lb_status' => 'User Status',
            'ph_gender' => 'Select Gender',
            'lb_gender' => 'User Gender',
        ],
        'table' => [
            'header_no' => 'No',
            'header_user' => 'User Info',
            'header_account' => 'Account Info',
            'header_gender' => 'gender',
            'header_status' => 'status',
            'header_actions' => 'Actions',
        ],
        'form' => [
            'lb' => [
                'email' => 'E-mail Address',
                'username' => 'Username',
                'password' => 'Password',
                'role' => 'Role',
                'status' => 'Status User',
                'name' => 'Fullname',
                'birthplace' => 'Birthplace',
                'birthdate' => 'Birthdate',
                'phone' => 'Phone Number',
                'address' => 'Address',
                'district' => 'District',
            ],
            'ph' => [
                'email' => 'Email address of the user',
                'username' => 'Username of the user',
                'password' => 'Password of the user',
                'role' => 'Select role for the user',
                'name' => 'Fullname of the user',
                'birthplace' => 'Birth place address of the user',
                'phone' => 'Phone number of the user',
                'address' => 'Address of the user',
                'district' => 'All districts',
            ],
        ],
    ],
];
