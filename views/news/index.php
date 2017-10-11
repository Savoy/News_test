<?php

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Новости';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="news-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Html::a('Добавить новость', ['create'], ['class' => 'btn btn-success']) ?></p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'title',
            'about',
            'user.name',
            'date_creation',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
