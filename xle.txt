1. 'cookieValidationKey' =>
2. Создать БД и прописать в db.php
3. Инициализировать таблицы RBAC
     php yii migrate --migrationPath=@yii/rbac/migrations
5. Начальные роли и разрешения Инициализировать таблицу меню
     php yii rbac/init

recapcha
6LfU-p8UAAAAAOSjC2aMujiIuD9K8zw7tP4IJQrp  ключ сайта

6LfU-p8UAAAAAJIytAMOw7CMnd8K5HmVaP0vT49- секретный ключ

6. Инициализация словаря
php yii translate/init