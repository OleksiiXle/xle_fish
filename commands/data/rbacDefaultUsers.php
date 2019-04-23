<?php
return [
    [
        'username' => 'defaultAdmin',
        'email' => 'admin@email.com',
        'password' => '12345',
        'retypePassword' => '12345',
        'first_name' => 'Главный',
        'middle_name' => 'Системный',
        'last_name' => 'Администратор',
        'userRoles' => [
            'admin'
        ],
    ],
    [
        'username' => 'defaultUser',
        'email' => 'user@email.com',
        'password' => '12345',
        'retypePassword' => '12345',
        'first_name' => 'Дефолтный',
        'middle_name' => 'Первый',
        'last_name' => 'Пользователь',
        'userRoles' => [
            'user'
        ],
    ],
];
