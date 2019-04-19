<?php
return [
    'permissions' => [
        'menuAdmin' => 'Меню администрирования',
        'login' => 'Вход в систему',
        'logout' => 'Выход из системы',
        'adminCRUD' => 'Редактирование админданных',
        'adminView' => 'Просмотр админданных',
    ],
    'roles' => [
        'admin'  => 'Администратор',
        'user'   => 'Пользователь',
    ],
    'rolesPermissions' => [
        'user' => [
            'menuAdmin',
            'adminView',
        ],
        'admin' => [
            'menuAdmin',
            'adminCRUD',
        ],
    ],
    'rolesChildren' => [
        'admin' => [
            'user',
        ],
    ]
];