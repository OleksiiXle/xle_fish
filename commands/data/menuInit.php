<?php
return [
    'menu1' => [
        'name' => 'Пункты администратора',
        'permission' => 'menuAdmin',
        'children' => [
          'name' => 'Администрирование',
          'permission' => 'menuAdmin',
          'children' => [
              'child0' => [
                'name'       => 'Пользователи',
                'route'      => '/adminx/user',
                'permission' => 'menuAdmin'
              ],
              'child1' => [
                'name'       => 'Разрешения',
                'route'      => '/adminx/auth-item',
                'permission' => 'menuAdmin'
              ],
              'child3' => [
                'name'       => 'Правила',
                'route'      => '/adminx/rule',
                'permission' => 'menuAdmin'
              ],
              'child5' => [
                  'name'       => 'Редактор меню',
                  'route'      => '/adminx/menux/menu',
                  'permission' => 'menuAdmin'
              ],
              'child6' => [
                  'name'       => 'PHP-info',
                  'route'      => 'adminx/user/php-info',
                  'permission' => 'menuAdmin'
              ],
          ]
      ]
    ],
    'menu5' => [
        'name' => 'Общие пункты',
        'permission' => 'menuAll',
        'children' => [
          'name' => 'Кабинет',
          'permission' => 'menuAll',
          'children' => [
              'child0' => [
                'name'       => 'Смена пароля',
                'route'      => '/adminx/user/change-password',
                'permission' => 'menuAll'
              ],
          ]
      ]
    ],
    'menu_guest' => [
        'name' => 'menu_guest',
        'permission' => '',
        'children' => [
              'child0' => [
                'name'       => 'Восстановление пароля',
                'route'      => '/adminx/user/forget-password',
                'permission' => ''
              ],
              'child1' => [
                'name'       => 'Вход',
                'route'      => '/adminx/user/login',
                'permission' => ''
              ],
          ]
    ],
        /*
    'menu7' => [
        'name' => 'Освіта-робота СТАРАЯ',
        'permission' => 'menuOsvitaWork',
        'children' => [
          'name' => 'Освіта СТАРАЯ',
          'permission' => 'menuOsvitaWork',
          'children' => [
              'child0' => [
                'name'       => 'Особовий склад',
                'route'      => '/education/main/personal-main-grid',
                'permission' => 'menuOsvitaWork'
              ],
              'child1' => [
                'name'       => 'Підрозділи',
                'route'      => '/education/main/tree',
                'permission' => 'menuOsvitaWork'
              ],
          ]
      ]
    ],
    'menu8' => [
        'name' => 'Освіта-повна СТАРАЯ',
        'permission' => 'menuOsvitaMain',
        'children' => [
          'name' => 'Освіта -повна СТАРАЯ',
          'permission' => 'menuOsvitaMain',
          'children' => [
              'child0' => [
                'name'       => 'Особовий склад',
                'route'      => '/education/main/personal-main-grid',
                'permission' => 'menuOsvitaMain'
              ],
              'child1' => [
                'name'       => 'Підрозділи',
                'route'      => '/education/main/tree',
                'permission' => 'menuOsvitaMain'
              ],
              'child2' => [
                'name'       => 'Словники',
                'route'      => '/structure/dictionary/dictionary-edit',
                'permission' => 'menuOsvitaMain'
              ],
          ]
      ]
    ],
    */
];