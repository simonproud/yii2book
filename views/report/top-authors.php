<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Top 10 Authors Report';
/** @var int $year */
/** @var array $topAuthors */
?>

<h1><?= Html::encode($this->title) ?></h1>

<div class="report-form">
    <?= Html::beginForm(Url::to(['top-authors']), 'get', ['id' => 'year-form']) ?>
    <?= Html::label('Select Year: ', 'year') ?>
    <?= Html::input('number', 'year', $year, [
        'class' => 'form-control d-inline-block w-auto',
        'min' => '1900',
        'max' => date('Y')
    ]) ?>
    <?= Html::submitButton('Show Report', ['class' => 'btn btn-primary']) ?>
    <?= Html::endForm() ?>
</div>

<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Author</th>
                <th>Books Published</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($topAuthors as $index => $author): ?>
            <tr>
                <td><?= $index + 1 ?></td>
                <td><?= Html::encode($author['author']) ?></td>
                <td><?= $author['book_count'] ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
