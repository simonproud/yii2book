<?php
namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * @property mixed|null $authors
 * @property mixed|null $id
 * @property mixed|null $title
 * @property mixed|null $description
 * @property mixed|null $year
 * @property mixed|null $cover_image
 * @property mixed|null $isbn
 * @property mixed|null $authorIds
 * @property mixed|null $authorFios
 */
class Book extends ActiveRecord
{
    public static function tableName(): string
    {
        return 'book';
    }

    public function rules(): array
    {
        return [
            [['title', 'year'], 'required'],
            [['year'], 'integer'],
            [['description'], 'string'],
            [['title', 'isbn'], 'string', 'max' => 255],
            [['cover_image'], 'string', 'max' => 255],
            ['authorIds', 'safe']
        ];
    }

    public function getAuthors(): ActiveQuery
    {
        return $this->hasMany(Author::class, ['id' => 'author_id'])
            ->viaTable('book_author', ['book_id' => 'id']);
    }

    public function getAuthorIds(): array
    {
        return ArrayHelper::getColumn($this->authors, 'id');
    }

    public function getAuthorFios(): array
    {
        return ArrayHelper::getColumn($this->authors, 'full_name');
    }
}
