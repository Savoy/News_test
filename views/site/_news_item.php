<?php
/**
 * Author: SAnV
 * Date: 11.10.2017
 * Time: 16:14
 *
 * @var $model app\models\News
 */
?>

<div class="row">
	<hr>
	<h2><?=$model->title ?> <small>добавлено пользователем <?=$model->user->name ?> (<?=date_create($model->date_creation)->format('d.m.Y H:i') ?>)</small></h2>
	<p><?=$model->about ?></p>
	<div><?=\yii\helpers\Html::a('Подробнее', ['/news/view', 'id'=>$model->id]) ?></div>
</div>
