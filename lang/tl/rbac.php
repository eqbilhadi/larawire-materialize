<?php

return [
    'nav' => [
        'entity' => 'Navegasaun',
        'title' => [
            'list' => 'Jestaun Navegasaun',
            'add' => 'Aumenta Navegasaun',
            'edit' => 'Edita Navegasaun',
            'sort' => 'Ordenamentu Navegasaun',
        ],
        'subtitle' => [
            'list' => 'Jere menu aplikasaun, organiza estrutura no konfigura ligasaun navegasaun sira.',
            'add' => 'Preexe dadus iha kraik hodi kria item menu foun.',
            'edit' => 'Atualiza dadus navegasaun no konfigurasaun sira iha kraik.',
            'sort' => 'Organiza item menu ho tarik no tama tuir ordem ne’ebé ita hakarak.',
        ],
        'filter' => [
            'ph_status' => 'Hili Estadu Navegasaun',
            'lb_status' => 'Estadu Navegasaun',
            'ph_search' => 'Buka tuir naran',
            'lb_search' => 'Buka',
        ],
        'table' => [
            'header_name' => 'Naran Etiketa',
            'header_controller' => 'Kontroladór',
            'header_route' => 'Rota',
            'header_url' => 'URL',
            'header_status' => 'Estadu',
            'header_actions' => 'Aksaun',
        ],
        'form' => [
            'lb' => [
                'name_en' => 'Naran etiketa Inglés',
                'name_pt' => 'Naran etiketa Portugés',
                'name_tl' => 'Naran etiketa Tetun',
                'nav_parent' => 'Navegasaun Inan',
                'controller' => 'Naran Kontroladór',
                'route' => 'Naran Rota',
                'url' => 'URL',
                'icon' => 'Íkone Navegasaun',
                'divider' => 'Divizór Navegasaun',
                'active' => 'Navegasaun Ativu',
            ],
            'ph' => [
                'name_en' => 'Etiketa navegasaun nian iha Inglés',
                'name_pt' => 'Etiketa navegasaun nian iha Portugés',
                'name_tl' => 'Etiketa navegasaun nian iha Tetun',
                'nav_parent' => 'Hili navegasaun inan',
                'controller' => 'Naran kontroladór ne\'ebé jere navegasaun',
                'route' => 'Naran rota ne\'ebé jere navegasaun',
                'url' => 'URL ne\'ebé jere navegasaun',
                'icon' => 'Íkone bazeia ba https://icon-sets.iconify.design/ri',
            ],
        ],
    ],
    'permission' => [
        'entity' => 'Permisaun',
        'title' => [
            'list' => 'Jestaun Permisaun',
            'add' => 'Aumenta Permisaun',
            'edit' => 'Edita Permisaun',
        ],
        'subtitle' => [
            'list' => 'Haree no organiza permisaun ne\'ebé kontrola asesu utilizador ba funsaun sira.',
            'add' => 'Permisaun ne\'ebé Ita bele uza no atribui ba Ita-nia utilizador sira.',
            'edit' => 'Atualiza dadus permisaun iha kraik.',
        ],
        'filter' => [
            'ph_group' => 'Hili Grupu',
            'lb_group' => 'Grupu Permisaun',
            'ph_search' => 'Buka tuir naran',
            'lb_search' => 'Buka',
        ],
        'table' => [
            'header_no' => 'Nu.',
            'header_name' => 'Naran Permisaun',
            'header_group' => 'Grupu',
            'header_actions' => 'Aksaun',
        ],
        'form' => [
            'lb' => [
                'name' => 'Naran Permisaun',
                'group' => 'Grupu Permisaun',
            ],
            'ph' => [
                'name' => 'Naran permisaun (ez: create menu)',
                'group' => 'Grupu permisaun (ez: Navigation)',
            ],
        ],
    ],
    'role' => [
        'entity' => 'Funsaun',
        'title' => [
            'list' => 'Jestaun Funsaun',
            'add' => 'Aumenta Funsaun',
            'edit' => 'Edita Funsaun',
        ],
        'subtitle' => [
            'list' => 'Haree, atualiza, ka hamos funsaun no kontrola nível asesu iha aplikasaun laran.',
            'add' => 'Fornese detallu funsaun no hili permisaun ne\'ebé presiza iha.',
            'edit' => 'Atualiza detallu funsaun no modifika nível asesu iha kraik.',
        ],
        'filter' => [
            'ph_search' => 'Buka tuir naran',
            'lb_search' => 'Buka',
        ],
        'table' => [
            'header_no' => 'Nu.',
            'header_name' => 'Naran Funsaun',
            'header_guard' => 'Guarda',
            'header_actions' => 'Aksaun',
        ],
        'form' => [
            'lb' => [
                'name' => 'Naran Funsaun',
                'permissions' => 'Permisaun Funsaun',
                'menus' => 'Menu Asesivel',
            ],
            'ph' => [
                'name' => 'Naran funsaun',
                'permissions' => 'Hili saida mak funsaun ne\'e bele halo iha sistema.',
                'menus' => 'Determina menu ida-ne\'ebé mak funsaun ne\'e bele asesu.',
            ],
        ],
    ],
    'user' => [
        'entity' => 'Utilizador',
        'title' => [
            'list' => 'Jestaun Utilizador',
            'add' => 'Aumenta Utilizador',
            'edit' => 'Edita Utilizador',
        ],
        'subtitle' => [
            'list' => 'Haree, buka, no jere utilizador hotu ne\'ebé rejista iha sistema, inklui direitu asesu no estadu konta',
            'add' => 'Favór preexe formuláriu hodi kria utilizador sistema foun.',
            'edit' => 'Modifika perfil utilizador hodi preexe formuláriu iha kraik.',
        ],
        'filter' => [
            'ph_search' => 'Buka tuir naran, email, ka username',
            'lb_search' => 'Buka',
            'ph_status' => 'Hili Estadu',
            'lb_status' => 'Estadu Utilizador',
            'ph_gender' => 'Hili Jéneru',
            'lb_gender' => 'Jéneru Utilizador',
        ],
        'table' => [
            'header_no' => 'Nu.',
            'header_user' => 'Info Utilizador',
            'header_account' => 'Info Konta',
            'header_gender' => 'Jéneru',
            'header_status' => 'Estadu',
            'header_actions' => 'Aksaun',
        ],
        'form' => [
            'lb' => [
                'email' => 'Enderesu Email',
                'username' => 'Username',
                'password' => 'Password',
                'role' => 'Funsaun',
                'status' => 'Estadu Utilizador',
                'name' => 'Naran Kompletu',
                'birthplace' => 'Fatin Moris',
                'birthdate' => 'Data Moris',
                'phone' => 'Númeru Telefone',
                'address' => 'Hela Fatin',
                'district' => 'Distritu',
            ],
            'ph' => [
                'email' => 'Enderesu email utilizador nian',
                'username' => 'Username utilizador nian',
                'password' => 'Password utilizador nian',
                'role' => 'Hili funsaun ba utilizador',
                'name' => 'Naran kompletu utilizador nian',
                'birthplace' => 'Fatin moris utilizador nian',
                'phone' => 'Númeru telefone utilizador nian',
                'address' => 'Hela fatin utilizador nian',
                'district' => 'Distritu Hotu-hotu',
            ],
        ],
    ],
];
