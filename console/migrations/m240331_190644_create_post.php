<?php

use yii\db\Migration;

class m240331_190644_create_post extends Migration
{
    public function safeUp()
    {
        $this->createTable('post', [
            'post_id'      => $this->primaryKey(),
            'title'        => $this->string(),
            'lead'         => $this->string(),
            'text'         => $this->text(),
            'image'        => $this->string(),
            'category_id'  => $this->integer(),
            'is_public'    => $this->integer(),
            'published_dt' => $this->dateTime(),
            'on_main'      => $this->boolean(),
            'in_slider'    => $this->boolean(),
            'is_temp'      => $this->boolean(),
            'created_by'   => $this->integer(),
            'updated_by'   => $this->integer(),
            'created_at'   => $this->integer(),
            'updated_at'   => $this->integer()
        ]);

        $this->createIndex('idx-post-category_id', 'post', 'category_id');
        $this->createIndex('idx-post-published_dt', 'post', 'published_dt');

        $sql = 'ALTER TABLE `post` MODIFY `text` MEDIUMTEXT';
        $this->execute($sql);
    }

    public function safeDown()
    {
        $this->dropTable('post');
    }
}
