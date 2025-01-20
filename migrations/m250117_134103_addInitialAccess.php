<?php

use yii\db\Migration;

/**
 * Class m250117_134103_addInitialAccess
 */
class m250117_134103_addInitialAccess extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $auth = Yii::$app->authManager;

        $auth->removeAll(); //На всякий случай удаляем старые данные из БД...

        // Создадим роли админа, пользователя и гостя
        $admin = $auth->createRole('admin');
        $user = $auth->createRole('user');
        $guest = $auth->createRole('guest');

        // запишем их в БД
        $auth->add($admin);
        $auth->add($user);
        $auth->add($guest);

        // Создаем разрешения
        $viewBooks = $auth->createPermission('viewBooks');
        $viewBooks->description = 'Просмотр книг';

        $subscribeAuthor = $auth->createPermission('subscribeAuthor');
        $subscribeAuthor->description = 'Подписка на новые книги автора';

        $createBook = $auth->createPermission('createBook');
        $createBook->description = 'Добавление книги';

        $updateBook = $auth->createPermission('updateBook');
        $updateBook->description = 'Редактирование книги';

        $deleteBook = $auth->createPermission('deleteBook');
        $deleteBook->description = 'Удаление книги';

        $viewAdminPage = $auth->createPermission('viewAdminPage');
        $viewAdminPage->description = 'Просмотр админки';

        // Добавляем разрешения в БД
        $auth->add($viewBooks);
        $auth->add($subscribeAuthor);
        $auth->add($createBook);
        $auth->add($updateBook);
        $auth->add($deleteBook);
        $auth->add($viewAdminPage);

        // Назначаем разрешения для гостя
        $auth->addChild($guest, $viewBooks);
        $auth->addChild($guest, $subscribeAuthor);

        // Назначаем разрешения для пользователя
        $auth->addChild($user, $viewBooks);
        $auth->addChild($user, $subscribeAuthor);
        $auth->addChild($user, $createBook);
        $auth->addChild($user, $updateBook);
        $auth->addChild($user, $deleteBook);

        // Админ наследует все права пользователя
        $auth->addChild($admin, $user);
        $auth->addChild($admin, $viewAdminPage);

        // Назначаем роль admin пользователю с ID 1
        $auth->assign($admin, 1);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m250117_134103_addInitialAccess cannot be reverted.\n";

        return false;
    }
}
