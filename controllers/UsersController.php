<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\data\ActiveDataProvider;
use app\models\User;

/**
 * UsersController implements the CRUD actions for User model.
 */
class UsersController extends Controller {
    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
	        'access' => [
		        'class' => AccessControl::className(),
		        'rules' => [
			        [
				        'allow' => true,
				        'matchCallback' => function($rule, $action) {
					        return Yii::$app->user->identity->type >= User::TYPE_ADMIN;
				        },
				        'roles' => ['@']
			        ]
		        ],
	        ],
	        'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex() {
        $dataProvider = new ActiveDataProvider([
            'query' => User::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new User();

        $postData = Yii::$app->request->post();
        if ($model->load($postData) && $model->validate()) {
	        $model->type = $postData['User']['type'];

        	if ($model->save()) {
		        Yii::$app->session->setFlash('successCreated');
		        return $this->redirect(['view', 'id' => $model->id]);
	        }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

	    $postData = Yii::$app->request->post();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
	        $model->type = $postData['User']['type'];

	        if ($model->save()) {
		        Yii::$app->session->setFlash('successUpdated');
		        return $this->redirect(['view', 'id' => $model->id]);
	        }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
	        throw new NotFoundHttpException('Запрашиваемая Вами страница не найдена!');
        }
    }

}
