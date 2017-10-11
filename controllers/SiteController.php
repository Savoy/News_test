<?php

namespace app\controllers;

use app\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller {
    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'profile'],
                'rules' => [
                    [
                        'actions' => ['logout', 'profile'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex() {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin() {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', ['model' => $model]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout() {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionRegister() {
	    $model = new User();
	    $model->scenario = 'register';

	    if ($model->load(Yii::$app->request->post())) {
		    if (Yii::$app->request->isAjax && isset(Yii::$app->request->bodyParams['ajax'])) {
			    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
			    return \yii\bootstrap\ActiveForm::validate($model);
		    }

		    $bcrypt = new \app\components\Bcrypt();
		    $model->password = $bcrypt->hash($model->newPassword);
		    $model->hash = md5($model->email.$model->newPassword.Time());
		    $model->type = User::TYPE_USER;

		    if ($model->save()) {
		        $activation_url = Url::to(['site/activate', 'key'=>$model->hash], 'http');
			    Yii::$app->mailer->compose()
				    ->setFrom(['mail4news.test@gmail.com'])
				    ->setTo($model->email)
				    ->setSubject('Добро пожаловать на '.Yii::$app->name)
				    ->setTextBody('Ваша ссылка для активации профиля: '.$activation_url)
				    ->setHtmlBody('<b>Ваша ссылка для активации профиля: </b> '.Html::a($activation_url, $activation_url))
				    ->send();

			    Yii::$app->mailer->compose()
				    ->setFrom(['mail4news.test@gmail.com'])
				    ->setTo(User::findOne(User::SUPER_ADMIN_ID)->email)
				    ->setSubject('Регистрация нового пользователя на '.Yii::$app->name)
				    ->setTextBody('Новый пользователь на сайте: '.$model->email)
				    ->setHtmlBody('<b>Новый пользователь на сайте: </b> '.$model->email)
				    ->send();

			    Yii::$app->session->setFlash('successRegistered');
			    return $this->refresh();
		    }
	    }

	    return $this->render('register', ['model' => $model]);
    }

    public function actionActivate($key) {
	    $model = User::findIdentityByAccessToken($key);
	    if ($model !== null) {
	    	$model->status = User::STATUS_ACTIVE;
	    	$model->hash = null;
	    	$model->save();

		    Yii::$app->user->login($model);
		    return $this->goHome();
	    } else throw new HttpException(404, 'Запрашиваемая Вами страница не найдена!');
    }

    public function actionProfile() {
	    $model = User::findIdentity(Yii::$app->user->id);

	    if ($model->load(Yii::$app->request->post())) {
		    if (Yii::$app->request->isAjax && isset(Yii::$app->request->bodyParams['ajax'])) {
			    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
			    return \yii\bootstrap\ActiveForm::validate($model);
		    }

		    if ($model->newPassword) {
			    $bcrypt = new \app\components\Bcrypt();
			    $model->password = $bcrypt->hash($model->newPassword);
		    }

		    if ($model->save()) {
			    Yii::$app->session->setFlash('successSaved');
			    return $this->refresh();
		    }
	    }

	    return $this->render('profile', ['model'=>$model]);
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact() {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout() {
        return $this->render('about');
    }

}
