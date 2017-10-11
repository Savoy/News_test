<?php
/* @var $this yii\web\View */
/* @var $model app\models\User */

use yii\helpers\Html;

$this->title = 'Добавить пользователя';
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create">
    <h1><?= Html::encode($this->title) ?></h1>

    <?=$this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
