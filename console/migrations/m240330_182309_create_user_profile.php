<?php

use yii\db\Migration;

class m240330_182309_create_user_profile extends Migration
{
    public function up()
    {
        $this->createTable('user_profile', [
            'user_id'    => $this->integer()->notNull(),
            'first_name' => $this->string(255),
            'last_name'  => $this->string(255),
            'birthday'   => $this->date(),
            'photo'      => $this->string(255),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull()
        ]);

        $this->addForeignKey('fk-user_profile', 'user_profile', 'user_id', 'user', 'user_id', 'CASCADE', 'RESTRICT');

        $this->createIndex('idx-user_profile-created_by', 'user_profile', 'created_by');
        $this->createIndex('idx-user_profile-updated_by', 'user_profile', 'updated_by');
    }

    public function down()
    {
        $this->dropTable('user_profile');
    }
}
