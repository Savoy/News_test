<?php
/* @var $this yii\web\View */
/* @var $model app\models\User */

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\Alert;

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">
    <h1><?= Html::encode($this->title) ?></h1>

	<?php if (Yii::$app->session->hasFlash('successCreated')) echo Alert::widget([
		'options' => ['class' => 'alert-success'],
		'body' => 'Пользователь был успешно добавлен.'
	]); ?>

	<?php if (Yii::$app->session->hasFlash('successUpdated')) echo Alert::widget([
		'options' => ['class' => 'alert-success'],
		'body' => 'Изменения были успешно сохранены.'
	]); ?>

	<p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить этот элемент?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?=DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
	        ['attribute'=>'type', 'value'=>$model->types[$model->type]],
            'name',
            'email:email',
	        ['attribute'=>'status', 'value'=>$model->statuses[$model->status]],
            'date_creation',
            'date_modification',
        ],
    ]) ?>

</div>
