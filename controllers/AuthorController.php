<?php

namespace app\controllers;

use app\components\events\UserSubscribed;
use app\components\handlers\UserSubscribedSmsHandler;
use app\components\services\AuthorService;
use app\components\services\NotificationService;
use app\components\services\SubscribtionService;
use app\models\Author;
use app\models\Subscription;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\Response;

class AuthorController extends Controller
{
    public function __construct(
        $id,
        $module,
        private AuthorService $authorService,
        private SubscribtionService $subscribtionService,
        $config = []
    )
    {
        parent::__construct($id, $module, $config);
    }

    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                        'subscribe' => ['POST'],
                    ],
                ],
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'actions' => ['create'],
                            'allow' => true,
                            'roles' => ['createBook'],
                        ],
                        [
                            'actions' => ['update'],
                            'allow' => true,
                            'roles' => ['updateBook'],
                        ],
                        [
                            'actions' => ['delete'],
                            'allow' => true,
                            'roles' => ['deleteBook'],
                        ],
                        [
                            'actions' => ['unsubscribe'],
                            'allow' => true,
                            'roles' => ['user'],
                        ],
                        [
                            'actions' => ['index', 'view', 'subscribe'],
                            'allow' => true,
                        ],
                    ],
                ],
            ]
        );
    }

    public function actionIndex(): string
    {
        return $this->render('index', [
            'dataProvider' => $this->authorService->getDataProvider(),
        ]);
    }

    public function actionView($id): string
    {
        return $this->render('view', [
            'model' => $this->authorService->findModel($id),
        ]);
    }

    public function actionCreate(): Response|string
    {
        $model = new Author();

        if ($this->request->isPost) {
            $author = $this->authorService->create($this->request->post());
            if ($author !== null) {
                return $this->redirect(['view', 'id' => $author->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id): Response|string
    {
        $model = $this->authorService->findModel($id);

        if ($this->request->isPost && $this->authorService->update($model, $this->request->post())) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id): Response
    {
        $this->authorService->delete($id);

        return $this->redirect(['index']);
    }

    public function actionSubscribe(): Response
    {
        if ($this->request->isPost) {
            $authorId = $this->request->post('author_id');
            $phone = $this->request->post('phone');
            if ($this->subscribtionService->subscribe($authorId, $phone)) {
                Yii::$app->session->setFlash('success', 'Successfully subscribed to author updates');
            } else {
                Yii::$app->session->setFlash('error', 'Subscription failed');
            }
        }
        return $this->redirect(['view', 'id' => $authorId]);
    }

    public function actionUnsubscribe(): Response
    {
        if ($this->request->isPost) {
            $authorId = $this->request->post('author_id');
            if ($this->subscribtionService->unsubscribe($authorId)) {
                Yii::$app->session->setFlash('success', 'Successfully subscribed to author updates');
            } else {
                Yii::$app->session->setFlash('error', 'Subscription failed');
            }
        }

        return $this->redirect(['view', 'id' => $authorId]);
    }
}
