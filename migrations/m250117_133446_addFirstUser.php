<?php

use yii\db\Migration;

/**
 * Class m250117_133446_addFirstUser
 */
class m250117_133446_addFirstUser extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('user', [
            'username' => 'admin',
            'email' => 'admin@example.com',
            'phone' => '79952252675',
            'password_hash' => Yii::$app->security->generatePasswordHash('admin'),
            'auth_key' => Yii::$app->security->generateRandomString(),
            'created_at' => time(),
            'updated_at' => time(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m250117_133446_addFirstUser cannot be reverted.\n";

        return false;
    }
}
