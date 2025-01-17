<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%author}}`.
 */
class m250117_085525_create_author_table extends Migration
{
    public function up()
    {
        $this->createTable('author', [
            'id' => $this->primaryKey(),
            'full_name' => $this->string()->notNull(),
        ]);
    }

    public function down()
    {
        $this->dropTable('author');
    }
}
