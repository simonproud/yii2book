<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%book}}`.
 */
class m250117_084612_create_book_table extends Migration
{
    public function up()
    {
        $this->createTable('book', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'year' => $this->integer()->notNull(),
            'description' => $this->text(),
            'isbn' => $this->string(),
            'cover_image' => $this->string(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);
    }

    public function down()
    {
        $this->dropTable('book');
    }
}
