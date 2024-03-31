<?php

use yii\db\Migration;

class m240331_122023_create_settings extends Migration
{
    public function safeUp()
    {
        $this->createTable('settings', ['id' => $this->primaryKey()]);
        $this->insert('settings', ['id' => 1]);
    }

    public function safeDown()
    {
        $this->dropTable('settings');
    }
}
