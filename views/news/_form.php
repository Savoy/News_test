<?php

/* @var $this yii\web\View */
/* @var $model app\models\News */
/* @var $form yii\widgets\ActiveForm */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="news-form">
    <?php $form = ActiveForm::begin(); ?>

    <?=$form->field($model, 'title')->textInput(['maxlength' => true]) ?>

	<?=$form->field($model, 'about')->textarea(['rows' => 4]) ?>

    <?=$form->field($model, 'text')->textarea(['rows' => 8]) ?>

    <div class="form-group">
        <?=Html::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
