<?php

/* @var $this yii\web\View */
/* @var $model app\models\News */

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\Alert;

$this->title = $model->title;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="news-view">
    <h1><?= Html::encode($this->title) ?></h1>

	<?php if (Yii::$app->session->hasFlash('successCreated')) echo Alert::widget([
		'options' => ['class' => 'alert-success'],
		'body' => 'Новость была успешно добавлена.'
	]); ?>

	<?php if (Yii::$app->session->hasFlash('successUpdated')) echo Alert::widget([
		'options' => ['class' => 'alert-success'],
		'body' => 'Изменения были успешно сохранены.'
	]); ?>

	<?php if (Yii::$app->user->identity->type >= \app\models\User::TYPE_MODERATOR): ?><p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить этот элемент?',
                'method' => 'post',
            ],
        ]) ?>
    </p><?php endif; ?>

    <?=DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
	        'date_creation',
	        'user.name',
	        'title',
            'about',
            'text:ntext'
        ]
    ]) ?>
</div>
