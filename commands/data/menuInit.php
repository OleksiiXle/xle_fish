<?php
$t = [
    [
        'name' => 'Администрирование',
        'route' => '',
        'role' => 'menuAdmin',
        'children' => [
            [
                'name' => 'Разрешения, роли, пользователи',
                'route' => '',
                'role' => 'menuAdmin',
                'children' => [
                    [
                        'name'       => 'Правила',
                        'route'      => '/adminx/rule',
                        'role' => 'menuAdmin',
                        'children' => []
                    ],
                    [
                        'name'       => 'Разрешения',
                        'route'      => '/adminx/auth-item',
                        'role' => 'menuAdmin',
                        'children' => []
                    ],
                    [
                        'name'       => 'Пользователи',
                        'route'      => '/adminx/user',
                        'role' => 'menuAdmin',
                        'children' => []
                    ],
                    [
                        'name'       => 'Активность пользователей',
                        'route'      => '/adminx/check/user-control',
                        'role' => 'menuAdmin',
                        'children' => []
                    ],
                    [
                        'name'       => 'Активность гостей',
                        'route'      => '/adminx/check/guest-control',
                        'role' => 'menuAdmin',
                        'children' => []
                    ],
                ]
            ],
            [
                'name' => 'Настройки',
                'route' => '',
                'role' => 'menuAdmin',
                'children' => [
                    [
                        'name'       => 'Редактор меню',
                        'route'      => '/adminx/menux/menu',
                        'role' => 'menuAdmin',
                        'children' => []
                    ],
                    [
                        'name'       => 'Системные настройки',
                        'route'      => '/adminx/configs/update',
                        'role' => 'menuAdmin',
                        'children' => []
                    ],
                    [
                        'name'       => 'PHP-info',
                        'route'      => 'adminx/user/php-info',
                        'role' => 'menuAdmin',
                        'children' => [],
                    ],
                    [
                        'name'       => 'Переводы',
                        'route'      => 'adminx/translation',
                        'role' => 'menuAdmin',
                        'children' => [],
                    ],
                ],
            ]

        ]
    ],
    [
        'name' => 'Кабинет',
        'route' => '',
        'role' => 'menuAll',
        'children' => [
            [
                'name'       => 'Профиль',
                'route'      => '/adminx/user/update-profile',
                'role' => 'menuAll',
                'children' => [],
            ],
            [
                'name'       => 'Смена пароля',
                'route'      => '/adminx/user/change-password',
                'role' => 'menuAll',
                'children' => [],
            ],

        ]
    ],
    [
        'name' => 'Посты',
        'route' => '',
        'role' => 'menuAll',
        'children' => [
            [
                'name'       => 'Список постов',
                'route'      => '/post/post',
                'role' => 'menuAll',
                'children' => [],
            ],
        ]
    ],
    [
        'name'       => 'Восстановление пароля',
        'route'      => '/adminx/user/request-password-reset',
        'role' => '',
        'children' => [],
    ],
    [
        'name'       => 'Вход',
        'route'      => '/adminx/user/login',
        'role' => '',
        'children' => [],
    ],
    [
        'name'       => 'Регистрация',
        'route'      => '/adminx/user/signup',
        'role' => '',
        'children' => [],
    ]
];

return $t;