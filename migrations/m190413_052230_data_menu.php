<?php

use yii\db\Migration;

/**
 * Class m190413_052230_data_menu
 */
class m190413_052230_data_menu extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
         INSERT INTO `menu_x`(`parent_id`, `sort`, `name`, `route`, `role`) 
         VALUES
          (0, 0, 'Адміністрування', '@app/modules/admin' , 'admin'),
          (1, 1, 'Користувачі', '@app/modules/admin/user' , 'admin'),
          (1, 2, 'Реєстрація нового користувача', '@app/modules/admin/user/signup' , 'admin'),
          (1, 3, 'Призачення ролі', '@app/modules/admin/assignment' , 'admin'),
          (1, 4, 'Робота з ролями', '@app/modules/admin/role' , 'admin'),
          (1, 5, 'Маршрути', '@app/modules/admin/route' , 'admin');
        ");

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190413_052230_data_menu cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190413_052230_data_menu cannot be reverted.\n";

        return false;
    }
    */
}
