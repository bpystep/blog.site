<?php

use yii\db\Migration;

class m240331_185757_create_post_tag_assn extends Migration
{
    public function safeUp()
    {
        $this->createTable('post_tag_assn', [
            'post_id' => $this->integer()->notNull(),
            'tag_id'  => $this->integer()->notNull()
        ]);

        $this->addPrimaryKey('', 'post_tag_assn', ['post_id', 'tag_id']);
    }

    public function safeDown()
    {
        $this->dropTable('post_tag_assn');
    }
}
