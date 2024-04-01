<?php

use yii\db\Migration;

class m240331_122100_create_settings_social extends Migration
{
    public function safeUp()
    {
        $this->createTable('settings_social', [
            'social_id'   => $this->primaryKey(),
            'settings_id' => $this->integer()->notNull(),
            'social'      => $this->string()->notNull(),
            'url'         => $this->string(),
            'rank'        => $this->integer()
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('settings_social');
    }
}
