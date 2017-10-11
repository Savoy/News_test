<?php

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Пользователи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить пользователя', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            'email:email',
            ['attribute'=>'type', 'value'=>function ($model) { return $model->types[$model->type]; }],
	        ['attribute'=>'status', 'value'=>function ($model) { return $model->statuses[$model->status]; }],
            'date_creation',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
