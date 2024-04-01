<?php

use yii\db\Migration;

class m240331_161005_create_file extends Migration
{
    public function safeUp()
    {
        $this->createTable('file', [
            'file_id'    => $this->primaryKey(),
            'type'       => $this->string()->notNull(),
            'file'       => $this->string()->notNull(),
            'module'     => $this->string()->notNull(),
            'item_id'    => $this->integer(),
            'year'       => $this->string()->notNull(),
            'month'      => $this->string()->notNull(),
            'created_at' => $this->integer()->notNull()
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('file');
    }
}
