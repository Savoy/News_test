<?php

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ListView;

$this->title = Yii::$app->name;
?>
<div class="news-index">
	<h1><?= Html::encode($this->title) ?></h1>

	<?php if (Yii::$app->user->identity->type >= \app\models\User::TYPE_MODERATOR): ?>
		<p><?= Html::a('Добавить новость', ['news/create'], ['class' => 'btn btn-success']) ?></p>
	<?php endif; ?>

	<div class="pull-right">Количество новостей на странице:
		<?=Html::a(1, Url::current(['per-page' => 1])) ?>
		<?=Html::a(5, Url::current(['per-page' => 5])) ?>
		<?=Html::a(10, Url::current(['per-page' => 10])) ?>
		<?=Html::a(50, Url::current(['per-page' => 50])) ?>
		<?=Html::a(100, Url::current(['per-page' => 100])) ?>
	</div>
	<?=ListView::widget([
		'dataProvider' => $dataProvider,
		'itemView' => '_news_item',
		'summary' => false
	]); ?>
</div>
