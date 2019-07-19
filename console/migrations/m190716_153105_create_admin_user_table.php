<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%admin_user}}`.
 */
class m190716_153105_create_admin_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        
        $this->createTable('{{%admin_user}}', [
            'id' => $this->primaryKey(),
            'email' => $this->string()->notNull()->unique(),
            'username' => $this->string()->notNull()->unique(),
            'first_name' => $this->string()->notNull()->unique(),
            'last_name' => $this->string()->notNull()->unique(),
            'status' => $this->/*smallInteger*/boolean()->notNull()->defaultValue(0),
            'role' => $this->/*smallInteger*/boolean()->notNull()->defaultValue(0),
            'avatar' => $this->string()->defaultValue(NULL),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'verification_token' => $this->string()->defaultValue(null),
            'password_reset_token' => $this->string()->unique(),
    
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%admin_user}}');
    }
}
