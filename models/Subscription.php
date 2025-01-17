<?php
namespace app\models;

use yii\db\ActiveRecord;

class Subscription extends ActiveRecord
{
    public static function tableName()
    {
        return 'subscription';
    }

    public function rules()
    {
        return [
            [['author_id', 'phone'], 'required'],
            [['author_id'], 'integer'],
            [['phone'], 'string', 'max' => 255],
            [['author_id'], 'exist', 'skipOnError' => true,
                'targetClass' => Author::class,
                'targetAttribute' => ['author_id' => 'id']],
        ];
    }

    public function getAuthor()
    {
        return $this->hasOne(Author::class, ['id' => 'author_id']);
    }
}
