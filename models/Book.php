<?php
namespace app\models;

use yii\db\ActiveRecord;

class Book extends ActiveRecord
{
    public static function tableName()
    {
        return 'book';
    }

    public function rules()
    {
        return [
            [['title', 'year'], 'required'],
            [['year'], 'integer'],
            [['description'], 'string'],
            [['title', 'isbn'], 'string', 'max' => 255],
            [['cover_image'], 'string', 'max' => 255],
        ];
    }

    public function getAuthors()
    {
        return $this->hasMany(Author::class, ['id' => 'author_id'])
            ->viaTable('book_author', ['book_id' => 'id']);
    }
}
