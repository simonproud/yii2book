<?php

namespace app\components\services;

use app\components\events\BookCreated;
use app\components\events\UserSubscribed;
use app\models\Author;
use app\models\Book;
use app\models\forms\BookForm;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\web\NotFoundHttpException;

class BookService
{
    public function getDataProvider(): ActiveDataProvider
    {
        return new ActiveDataProvider([
            'query' => Book::find(),
            'pagination' => [
                'pageSize' => 50
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
        ]);
    }

    public function findModel(int $id): Book
    {
        if (($model = Book::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function create(array $postData): ?Book
    {
        $model = new Book();
        if ($model->load($postData) && $model->save()) {
            return $model;
        }
        return null;
    }

    public function update(Book $model, array $postData): bool
    {
        return $model->load($postData) && $model->save();
    }

    public function delete(int $id): bool
    {
        return $this->findModel($id)->delete() > 0;
    }

    public function getAuthors(int $bookId): array
    {
        $book = $this->findModel($bookId);
        return $book->authors;
    }

    public function saveFromForm(BookForm $form): Book|false
    {
        if (!$form->validate()) {
            return false;
        }

        $book = $form->id ? $this->findModel($form->id) : new Book();
        $book->title = $form->title;
        $book->year = $form->year;
        $book->isbn = $form->isbn;
        $book->cover_image = $form->coverImage;
        $book->description = $form->description;

        if (!$book->save()) {
            return $book;
        }
        $this->updateBookAuthors($book, $form->authorIds);

        if($form->id === null){
            $event = new BookCreated();
            $event->bookId = $book->id;
            Yii::$app->trigger(BookCreated::NAME, $event);
        }

        return $book;
    }

    private function updateBookAuthors(Book $book, array $authorIds): void
    {
        $book->unlinkAll('authors', true);
        foreach ($authorIds as $authorId) {
            $book->link('authors', Author::findOne($authorId));
        }
    }

    public function getTopAuthors(int $year, int $limit = 10): array
    {
        return (new Query())
            ->select(['author.full_name as author', 'COUNT(*) as book_count'])
            ->from('author')
            ->innerJoin('book_author', 'book_author.author_id = author.id')
            ->innerJoin('book', 'book.id = book_author.book_id')
            ->where(['book.year' => $year])
            ->groupBy(['author.id', 'author.full_name'])
            ->orderBy(['book_count' => SORT_DESC])
            ->limit($limit)
            ->all();
    }


}