<?php
/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form ActiveForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Alert;

$this->title = 'Регистрация';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-register">
	<h1><?= Html::encode($this->title) ?></h1>

	<?php if (Yii::$app->session->hasFlash('successRegistered')) echo Alert::widget([
		'options' => ['class' => 'alert-success'],
		'body' => 'Регистрация прошла успешно.<br> Вам выслано письмо со ссылкой для активации профиля.'
	]); ?>

	<?php $form = ActiveForm::begin([
		'id' => 'register-form',
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
            <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- site-register -->
