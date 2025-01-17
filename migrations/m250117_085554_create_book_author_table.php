<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%book_author}}`.
 */
class m250117_085554_create_book_author_table extends Migration
{
    public function up()
    {
        $this->createTable('book_author', [
            'book_id' => $this->integer(),
            'author_id' => $this->integer(),
            'PRIMARY KEY(book_id, author_id)'
        ]);

        $this->addForeignKey(
            'fk-book_author-book_id',
            'book_author',
            'book_id',
            'book',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-book_author-author_id',
            'book_author',
            'author_id',
            'author',
            'id',
            'CASCADE'
        );
    }

    public function down()
    {
        $this->dropTable('book_author');
    }
}
