<?php

use yii\db\Migration;

/**
 * Class m190412_171406_add_tbl_user
 */
class m190412_171406_add_tbl_user extends Migration
{
    const TABLE_USER = '{{%user}}';
    const TABLE_USER_DATA = '{{%user_data}}';

    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        $this->createTable(self::TABLE_USER, [
            'id' => $this->primaryKey(),
            'username' => $this->string(32)->notNull(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string(),
            'email' => $this->string()->notNull(),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);
        $this->createIndex('user_id', self::TABLE_USER, 'id');
        $this->createIndex('user_username', self::TABLE_USER, 'username', true);
        $this->createIndex('user_email', self::TABLE_USER, 'email', true);

        $this->createTable(self::TABLE_USER_DATA, [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(11)->notNull(),
            'first_name' => $this->string(50)->defaultValue('')->comment('Имя'),
            'middle_name' => $this->string(50)->defaultValue('')->comment('Отчество'),
            'last_name' => $this->string(50)->defaultValue('')->comment('Фамилия'),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);


        $this->addForeignKey('fk_user_user_data', self::TABLE_USER_DATA,'user_id',
            self::TABLE_USER, 'id', 'cascade', 'cascade');
    }


    public function safeDown()
    {
        $this->dropForeignKey('fk_user_user_data', self::TABLE_USER_DATA);
        $this->dropIndex('user_id', self::TABLE_USER);
        $this->dropIndex('user_username', self::TABLE_USER);
        $this->dropIndex('user_email', self::TABLE_USER);
        $this->dropTable(self::TABLE_USER_DATA);
        $this->dropTable(self::TABLE_USER);

        return false;
    }

}