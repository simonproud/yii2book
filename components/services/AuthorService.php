<?php

namespace app\components\services;

use app\models\Author;
use app\models\Book;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

class AuthorService
{
    public function getDataProvider(): ActiveDataProvider
    {
        return new ActiveDataProvider([
            'query' => Author::find(),
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

    public function findModel(int $id): Author
    {
        if (($model = Author::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function create(array $postData): ?Author
    {
        $model = new Author();
        if ($model->load($postData) && $model->save()) {
            return $model;
        }
        return null;
    }

    public function update(Author $model, array $postData): bool
    {
        return $model->load($postData) && $model->save();
    }

    public function delete(int $id): bool
    {
        return $this->findModel($id)->delete() > 0;
    }

    public function assignBooks(int $authorId, array $bookIds): void
    {
        $author = $this->findModel($authorId);
        $author->linkAll('books', Book::findAll($bookIds));
    }

    public function removeBooks(int $authorId, array $bookIds): void
    {
        $author = $this->findModel($authorId);
        $author->unlinkAll('books', Book::findAll($bookIds));
    }
}

