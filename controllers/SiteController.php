<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\User;

class SiteController extends Controller {
    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['login', 'register', 'logout', 'profile'],
                'rules' => [
	                [
		                'actions' => ['login', 'register'],
		                'allow' => true,
		                'roles' => ['?'],
	                ],
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
	 * Lists all News models.
	 * @return mixed
	 */
    public function actionIndex() {
	    $dataProvider = new \yii\data\ActiveDataProvider([
		    'query' => \app\models\News::find(),
		    'pagination' => ['pageSizeLimit' => [1, 100]]
	    ]);

	    return $this->render('index', [
		    'dataProvider' => $dataProvider,
	    ]);
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

		    $model->type = User::TYPE_USER;

		    if ($model->save()) {
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

		    if ($model->password) return $this->goHome();
		    else return $this->redirect(['profile']);
	    } else throw new NotFoundHttpException('Запрашиваемая Вами страница не найдена!');
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
