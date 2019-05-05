<?php

use yii\db\Migration;

/**
 * Class m190430_055550_tbl_config
 */
class m190430_055550_tbl_config extends Migration
{
    const TABLE_ = '{{%configs}}';

    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable(self::TABLE_, [
            'id' => $this->primaryKey(),
            'owner' => $this->string(255)->notNull(),
            'name' => $this->string(250)->notNull()->unique(),
            'content' => $this->text()->defaultValue(null),
        ], $tableOptions);

    }


    public function safeDown()
    {
        $this->dropTable(self::TABLE_);

        return false;
    }

}
