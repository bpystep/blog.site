<?php

use yii\db\Migration;

class m240330_182934_insert_admin extends Migration
{
    public function up()
    {
        $now = time();

        $this->insert('user', [
            'username'      => 'admin',
            'email'         => 'admin@blog.site',
            'role'          => 1025,
            'password_hash' => Yii::$app->security->generatePasswordHash('1234567890', 10),
            'auth_key'      => Yii::$app->security->generateRandomString(),
            'created_at'    => $now,
            'updated_at'    => $now
        ]);

        $this->insert('user_profile', [
            'user_id'    => $this->getDb()->getLastInsertID(),
            'first_name' => 'Админ',
            'last_name'  => 'Админыч',
            'created_at' => $now,
            'updated_at' => $now
        ]);
    }

    public function down()
    {
        $this->delete('user', ['username' => 'admin']);
    }
}
