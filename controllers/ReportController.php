<?php

namespace app\controllers;

use app\components\services\BookService;
use Yii;
use yii\web\Controller;

class ReportController extends Controller
{
    private BookService $bookService;

    public function __construct($id, $module, BookService $bookService, $config = [])
    {
        $this->bookService = $bookService;
        parent::__construct($id, $module, $config);
    }

    /**
     * Displays top 10 authors report
     * @return string
     */
    public function actionTopAuthors()
    {
        $year = Yii::$app->request->get('year', date('Y'));
        $topAuthors = $this->bookService->getTopAuthors($year, 10);

        return $this->render('top-authors', [
            'topAuthors' => $topAuthors,
            'year' => $year
        ]);
    }
}
