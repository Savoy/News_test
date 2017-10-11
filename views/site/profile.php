<?php
/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form ActiveForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Alert;

$this->title = 'Профиль';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-profile">
	<h1><?= Html::encode($this->title) ?></h1>

	<?php if (Yii::$app->session->hasFlash('successSaved')) echo Alert::widget([
		'options' => ['class' => 'alert-success'],
		'body' => 'Изменения профиля успешно сохранены.'
	]); ?>

	<?php $form = ActiveForm::begin([
		'id' => 'profile-form',
		'layout' => 'horizontal',
		'fieldConfig' => [
			'template' => "{label}\n<div class=\"col-lg-4\">{input}</div>\n<div class=\"col-lg-6\">{error}</div>",
			'labelOptions' => ['class' => 'col-lg-2 control-label'],
		],
		'enableAjaxValidation' => true
	]); ?>

		<?=$form->field($model, 'name') ?>

        <?=$form->field($model, 'email') ?>

        <?=$form->field($model, 'newPassword')->passwordInput() ?>

		<?=$form->field($model, 'confirmPassword')->passwordInput() ?>

        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
        </div>

    <?php ActiveForm::end(); ?>
</div><!-- site-profile -->
