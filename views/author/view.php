<?php

use app\models\Subscription;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Author $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Authors'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="author-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'full_name',
        ],
    ]) ?>

</div>

<?php
$isSubscribed = false;
if (!Yii::$app->user->isGuest) {
    $isSubscribed = Subscription::find()
        ->where(['author_id' => $model->id, 'phone' => Yii::$app->user->identity->phone])
        ->exists();
}
?>

<?php if (!Yii::$app->user->isGuest): ?>
    <?php if ($isSubscribed): ?>
        <?= Html::beginForm(['author/unsubscribe'], 'post', ['class' => 'subscription-form']) ?>
        <?= Html::hiddenInput('author_id', $model->id) ?>
        <?= Html::hiddenInput('phone', Yii::$app->user->identity->phone) ?>
        <?= Html::submitButton('Unsubscribe from SMS notifications', ['class' => 'btn btn-danger']) ?>
        <?= Html::endForm() ?>
    <?php else: ?>
        <?= Html::beginForm(['author/subscribe'], 'post', ['class' => 'subscription-form']) ?>
        <?= Html::hiddenInput('author_id', $model->id) ?>
        <?= Html::hiddenInput('phone', Yii::$app->user->identity->phone) ?>
        <?= Html::submitButton('Subscribe to SMS notifications', ['class' => 'btn btn-primary']) ?>
        <?= Html::endForm() ?>
    <?php endif; ?>
<?php else: ?>
    <?= Html::beginForm(['author/subscribe'], 'post', ['class' => 'subscription-form']) ?>
    <?= Html::hiddenInput('author_id', $model->id) ?>
    <div class="form-group">
        <?= Html::textInput('phone', '', [
            'class' => 'form-control',
            'placeholder' => 'Enter your phone number',
            'pattern' => '[0-9]{11}',
            'required' => true
        ]) ?>
    </div>
    <?= Html::submitButton('Subscribe to SMS notifications', ['class' => 'btn btn-primary']) ?>
    <?= Html::endForm() ?>
<?php endif; ?>
