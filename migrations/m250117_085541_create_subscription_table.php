<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%subscription}}`.
 */
class m250117_085541_create_subscription_table extends Migration
{
    public function up()
    {
        $this->createTable('subscription', [
            'id' => $this->primaryKey(),
            'author_id' => $this->integer(),
            'phone' => $this->string()->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        $this->addForeignKey(
            'fk-subscription-author_id',
            'subscription',
            'author_id',
            'author',
            'id',
            'CASCADE'
        );
    }

    public function down()
    {
        $this->dropTable('subscription');
    }
}
