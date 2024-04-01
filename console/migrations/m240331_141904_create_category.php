<?php

use yii\db\Migration;

class m240331_141904_create_category extends Migration
{
    public function safeUp()
    {
        $this->createTable('category', [
            'category_id' => $this->primaryKey(),
            'title'       => $this->string()->notNull(),
            'rank'        => $this->integer(),
            'created_by'  => $this->integer(),
            'updated_by'  => $this->integer(),
            'created_at'  => $this->integer(),
            'updated_at'  => $this->integer()
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('category');
    }
}
