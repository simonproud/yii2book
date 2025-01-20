<?php

namespace app\controllers;

use app\components\events\BookCreated;
use app\components\handlers\BookCreatedSmsHandler;
use app\components\services\BookService;
use app\models\forms\BookForm;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * BookController implements the CRUD actions for Book model.
 */
class BookController extends Controller
{
    private BookService $bookService;

    public function __construct($id, $module, BookService $bookService, $config = [])
    {
        $this->bookService = $bookService;
        parent::__construct($id, $module, $config);
    }

    public function init()
    {
        parent::init();

        $this->on(
            BookCreated::NAME,
            [
                new BookCreatedSmsHandler(),
                'handle',
            ]
        );
    }

    /**
     * @inheritDoc
     */
    public function behaviors(): array
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
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
                            'actions' => ['index', 'view'],
                            'allow' => true,
                        ],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Book models.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        return $this->render('index', [
            'dataProvider' => $this->bookService->getDataProvider(),
        ]);
    }

    /**
     * Displays a single Book model.
     * @param int $id
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView(int $id): string
    {
        return $this->render('view', [
            'model' => $this->bookService->findModel($id),
        ]);
    }

    /**
     * Creates a new Book model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     */
    public function actionCreate()
    {
        $model = new BookForm();

        if ($this->request->isPost) {
            $model->load($this->request->post());
            $book = $this->bookService->saveFromForm($model);
            if ($book !== null) {
                return $this->redirect(['view', 'id' => $book->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Book model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id
     * @return string|Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate(int $id)
    {
        $model = new BookForm();
        $book = $this->bookService->findModel($id);
        $model->loadFromBook($book);

        if ($this->request->isPost && $model->load($this->request->post())) {
            $book = $this->bookService->saveFromForm($model);
            return $this->redirect(['view', 'id' => $book->id]);
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Book model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id
     * @return Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->bookService->delete($id);
        return $this->redirect(['index']);
    }
}
