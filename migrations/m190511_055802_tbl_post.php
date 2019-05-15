<?php

use yii\db\Migration;

/**
 * Class m190511_055802_tbl_post
 */
class m190511_055802_tbl_post extends Migration
{
    const TABLE_USER = '{{%user}}';
    const TABLE_POST = '{{%post}}';
    const TABLE_MEDIA = '{{%post_media}}';

    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        $this->createTable(self::TABLE_POST, [
            'id' => $this->primaryKey()->comment('ID'),
            'user_id' => $this->integer(11)->notNull()->comment('Владелец'),
            'target' => $this->integer(11)->defaultValue(0)->comment('Цель'),
            'type' => $this->integer()->defaultValue(0)->comment('Тип'),
            'name' => $this->string(250)->comment('Название'),
            'content' => $this->binary()->defaultValue(null)->comment('Содержимое'),
            'created_at' => $this->integer()->notNull()->comment('Создано'),
            'updated_at' => $this->integer()->notNull()->comment('Изменено'),
        ], $tableOptions);
        $this->createTable(self::TABLE_MEDIA, [
            'id' => $this->primaryKey()->comment('ID'),
            'post_id' => $this->integer(11)->notNull()->comment('Post_id'),
            'type' => $this->integer()->defaultValue(0)->comment('Тип'),
            'name' => $this->string(250)->notNull()->comment('Название'),
            'file_name' => $this->binary()->defaultValue(null)->comment('Имя файла'),
            'created_at' => $this->integer()->notNull()->comment('Создано'),
            'updated_at' => $this->integer()->notNull()->comment('Изменено'),
        ], $tableOptions);

        $this->addForeignKey('fk_user_post', self::TABLE_POST,'user_id',
            self::TABLE_USER, 'id', 'cascade', 'cascade');
        $this->addForeignKey('fk_post_post_media', self::TABLE_MEDIA,'post_id',
            self::TABLE_POST, 'id', 'cascade', 'cascade');

    }


    public function safeDown()
    {
        $this->dropTable(self::TABLE_POST);

        return false;
    }

}
