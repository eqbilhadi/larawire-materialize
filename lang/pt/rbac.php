<?php

return [
    'nav' => [
        'entity' => 'Navegação',
        'title' => [
            'list' => 'Gestão de Navegação',
            'add' => 'Adicionar Navegação',
            'edit' => 'Editar Navegação',
            'sort' => 'Ordenação de Navegação',
        ],
        'subtitle' => [
            'list' => 'Gerencie os menus da aplicação, organize a estrutura e configure os links de navegação.',
            'add' => 'Preencha os detalhes abaixo para criar um novo item de menu.',
            'edit' => 'Atualize os detalhes da navegação e as configurações abaixo.',
            'sort' => 'Organize os itens do menu arrastando e soltando na ordem desejada.',
        ],
        'filter' => [
            'ph_status' => 'Selecione o Estado da Navegação',
            'lb_status' => 'Estado da Navegação',
            'ph_search' => 'Pesquisar por nome',
            'lb_search' => 'Pesquisar',
        ],
        'table' => [
            'header_name' => 'Nome da Etiqueta',
            'header_controller' => 'Controlador',
            'header_route' => 'Rota',
            'header_url' => 'URL',
            'header_status' => 'Estado',
            'header_actions' => 'Ações',
        ],
        'form' => [
            'lb' => [
                'name_en' => 'Nome da etiqueta em Inglês',
                'name_pt' => 'Nome da etiqueta em Português',
                'name_tl' => 'Nome da etiqueta em Tétum',
                'nav_parent' => 'Navegação Pai', // 'Pai' digunakan untuk konsep Parent/Child
                'controller' => 'Nome do Controlador',
                'route' => 'Nome da Rota',
                'url' => 'URL',
                'icon' => 'Ícone de Navegação',
                'divider' => 'Divisor de Navegação',
                'active' => 'Navegação Ativa',
            ],
            'ph' => [
                'name_en' => 'Etiqueta de navegação em Inglês',
                'name_pt' => 'Etiqueta de navegação em Português',
                'name_tl' => 'Etiqueta de navegação em Tétum',
                'nav_parent' => 'Selecione a navegação pai',
                'controller' => 'O nome do controlador que gere a navegação',
                'route' => 'O nome da rota que gere a navegação',
                'url' => 'O URL que gere a navegação',
                'icon' => 'Ícone base de https://icon-sets.iconify.design/ri',
            ],
        ],
    ],
    'permission' => [
        'entity' => 'Permissão',
        'title' => [
            'list' => 'Gestão de Permissões',
            'add' => 'Adicionar Permissão',
            'edit' => 'Editar Permissão',
        ],
        'subtitle' => [
            'list' => 'Visualize e organize permissões que controlam o acesso dos utilizadores às funcionalidades.',
            'add' => 'Permissões que pode usar e atribuir aos seus utilizadores.',
            'edit' => 'Atualize os detalhes da permissão abaixo.',
        ],
        'filter' => [
            'ph_group' => 'Selecione o Grupo',
            'lb_group' => 'Grupo de Permissão',
            'ph_search' => 'Pesquisar por nome',
            'lb_search' => 'Pesquisar',
        ],
        'table' => [
            'header_no' => 'Nº',
            'header_name' => 'Nome da Permissão',
            'header_group' => 'Grupo',
            'header_actions' => 'Ações',
        ],
        'form' => [
            'lb' => [
                'name' => 'Nome da Permissão',
                'group' => 'Grupo de Permissão',
            ],
            'ph' => [
                'name' => 'Nome da permissão (ex: create menu)',
                'group' => 'Grupo da permissão (ex: Navigation)',
            ],
        ],
    ],
    'role' => [
        'entity' => 'Função',
        'title' => [
            'list' => 'Gestão de Funções',
            'add' => 'Adicionar Função',
            'edit' => 'Editar Função',
        ],
        'subtitle' => [
            'list' => 'Visualize, atualize ou remova funções e controle os níveis de acesso na aplicação.',
            'add' => 'Forneça os detalhes da função e selecione as permissões que deve ter.',
            'edit' => 'Atualize os detalhes da função e modifique os níveis de acesso abaixo.',
        ],
        'filter' => [
            'ph_search' => 'Pesquisar por nome',
            'lb_search' => 'Pesquisar',
        ],
        'table' => [
            'header_no' => 'Nº',
            'header_name' => 'Nome da Função',
            'header_guard' => 'Guarda',
            'header_actions' => 'Ações',
        ],
        'form' => [
            'lb' => [
                'name' => 'Nome da Função',
                'permissions' => 'Permissões da Função',
                'menus' => 'Menus Acessíveis',
            ],
            'ph' => [
                'name' => 'Nome da função',
                'permissions' => 'Escolha o que esta função pode fazer no sistema.',
                'menus' => 'Determine a que menus esta função pode aceder.',
            ],
        ],
    ],
    'user' => [
        'entity' => 'Utilizador',
        'title' => [
            'list' => 'Gestão de Utilizadores',
            'add' => 'Adicionar Utilizador',
            'edit' => 'Editar Utilizador',
        ],
        'subtitle' => [
            'list' => 'Visualize, pesquise e gerencie todos os utilizadores registados no sistema, incluindo direitos de acesso e estado da conta',
            'add' => 'Por favor, preencha o formulário para criar um novo utilizador do sistema.',
            'edit' => 'Modifique o perfil do utilizador preenchendo o formulário abaixo.',
        ],
        'filter' => [
            'ph_search' => 'Pesquisar por nome, email ou nome de utilizador',
            'lb_search' => 'Pesquisar',
            'ph_status' => 'Selecione o Estado',
            'lb_status' => 'Estado do Utilizador',
            'ph_gender' => 'Selecione o Género',
            'lb_gender' => 'Género do Utilizador',
        ],
        'table' => [
            'header_no' => 'Nº',
            'header_user' => 'Info do Utilizador',
            'header_account' => 'Info da Conta',
            'header_gender' => 'Género',
            'header_status' => 'Estado',
            'header_actions' => 'Ações',
        ],
        'form' => [
            'lb' => [
                'email' => 'Endereço de Email',
                'username' => 'Nome de Utilizador',
                'password' => 'Palavra-passe',
                'role' => 'Função',
                'status' => 'Estado do Utilizador',
                'name' => 'Nome Completo',
                'birthplace' => 'Naturalidade',
                'birthdate' => 'Data de Nascimento',
                'phone' => 'Número de Telefone',
                'address' => 'Endereço',
                'district' => 'Distrito',
            ],
            'ph' => [
                'email' => 'Endereço de email do utilizador',
                'username' => 'Nome de utilizador do utilizador',
                'password' => 'Palavra-passe do utilizador',
                'role' => 'Selecione a função para o utilizador',
                'name' => 'Nome completo do utilizador',
                'birthplace' => 'Naturalidade do utilizador',
                'phone' => 'Número de telefone do utilizador',
                'address' => 'Endereço do utilizador',
                'district' => 'Todos os Distritos',
            ],
        ],
    ],
];
