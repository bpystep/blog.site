<?php

use yii\db\Migration;

class m240330_182303_create_user extends Migration
{
    public function safeUp()
    {
        $this->createTable('user', [
            'user_id'       => $this->primaryKey(),
            'username'      => $this->string()->notNull(),
            'email'         => $this->string()->notNull(),
            'role'          => $this->integer()->notNull(),
            'password_hash' => $this->string(60)->notNull(),
            'auth_key'      => $this->string(32)->notNull(),
            'created_by'    => $this->integer(),
            'updated_by'    => $this->integer(),
            'created_at'    => $this->integer(),
            'updated_at'    => $this->integer()
        ]);

        $this->createIndex('unique-user-username', 'user', 'username', true);
        $this->createIndex('unique-user-email',    'user', 'email', true);

        $this->createIndex('idx-user-created_by', 'user', 'created_by');
        $this->createIndex('idx-user-updated_by', 'user', 'updated_by');
    }

    public function safeDown()
    {
        $this->dropTable('user');
    }
}
