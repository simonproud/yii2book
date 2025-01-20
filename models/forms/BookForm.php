<?php
namespace app\models\forms;

use app\models\Book;
use yii\base\Model;

/**
 * @property mixed $title
 * @property mixed $description
 * @property mixed $year
 * @property mixed $coverImage
 * @property mixed $isbn
 * @property mixed $id
 */
class BookForm extends Model
{
    public ?int $id = null;
    public ?string $title = null;
    public ?string $description = null;
    public ?int $year = null;
    public ?string $coverImage = null;
    public ?string $isbn = null;
    public ?array $authorIds = null;


    public function rules(): array
    {
        return [
            [['title', 'year'], 'required'],
            [['year', 'id'], 'integer'],
            [['description'], 'string'],
            [['title', 'isbn'], 'string', 'max' => 255],
            [['coverImage'], 'string', 'max' => 255],
            ['authorIds', 'safe']
        ];
    }

    public function loadDefaultValues()
    {
    }

    public function loadFromBook(Book $book)
    {
        $this->id = $book->id;
        $this->title = $book->title;
        $this->description = $book->description;
        $this->year = $book->year;
        $this->coverImage = $book->cover_image;
        $this->isbn = $book->isbn;
        $this->authorIds = $book->authorIds;
    }
}
