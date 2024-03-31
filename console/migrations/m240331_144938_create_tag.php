<?php

use yii\db\Migration;

class m240331_144938_create_tag extends Migration
{
    public function safeUp()
    {
        $this->createTable('tag', [
            'tag_id'     => $this->primaryKey(),
            'title'      => $this->string()->notNull(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('tag');
    }
}
